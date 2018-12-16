<?php namespace Aike\Model;

use DB;
use Auth;
use Input;
use Request;
use URL;

use Module;
use Hook;
use AES;

use Aike\User\User;
use Aike\Index\Access;
use Aike\Model\Model;
use Aike\Model\Field;
use Aike\Model\Step;
use Aike\Model\StepLog;

class Grid
{
    public static function dataFilter($items, $haeder, $callback = null)
    {
        $dialogs = [];

        $items->transform(function ($item) use ($haeder, $callback, &$dialogs) {

            if (is_callable($callback)) {
                $item = $callback($item);
            }

            foreach ($haeder['columns'] as $column) {

                $field   = $column['field'];
                $setting = $column['setting'];
                $type    = $setting['type'];
                $value   = $item[$field];

                if ($column['form_type'] == 'address') {
                    $value = str_replace("\n", ' ', $value);
                }

                if ($column['form_type'] == 'dialog' || $column['form_type'] == 'select2') {
                    $_field = str_replace('_'.$type, '', $field);
                    $value = $item[$_field];
                    $v = $dialogs[$field]['value'];
                    $v = empty($v) ? $value : $v.','.$value;
                    $dialogs[$field] = ['type' => $type, 'value' => $v];
                }

                if ($column['form_type'] == 'date') {
                    if ($setting['save'] == 'u') {
                        $value = date($type, $value);
                    }
                }

                if ($column['form_type'] == 'option') {
                    $value = option($type, $value);
                }

                if ($column['form_type'] == 'select') {
                    $select = explode("\n", $setting['content']);
                    $res = [];
                    foreach ($select as $t) {
                        $n = $v = $selected = '';
                        list($n, $v) = explode('|', $t);
                        $v = is_null($v) ? trim($n) : trim($v);
                        if($v == $value) {
                            $res[] = $n;
                        }
                    }
                    $value = join(',', $res);
                }

                if ($field == 'step_sn') {
                    $steps = $haeder['steps'];
                    $value = $steps[$value]['name'];
                }

                if($column['type'] == 'DECIMAL') {
                    $value = number_format($value, 2);
                }

                $item[$field] = $value;
            }
            return $item;
        });

        foreach ($dialogs as $field => $dialog) {
            $option = Module::dialogs($dialog['type']);
            $ids = explode(',', $dialog['value']);
            if ($option['model']) {
                $dialogs[$field]['rows'] = $option['model']($ids);
            }
        }

        $index = 0;
        $rows = [];
        foreach ($items as $item) {
            foreach ($dialogs as $field => $dialog) {
                $item[$field] = $dialog['rows'][$item[$field]];
            }
            $rows[$index] = $item;
            $index++;
        }

        $rows = collect($rows);
        if ($items instanceof \Illuminate\Pagination\AbstractPaginator) {
            $items->setCollection($rows);
        } else {
            $items = $rows;
        }
        /*
        $items->transform(function ($item) use ($dialogs, $rows) {
            foreach ($dialogs as $field => $dialog) {
                $item[$field] = $dialog['rows'][$item[$field]];
            }
            return $item;
        });
        */
        return $items;
    }

    public function addCols($cols, $key, $new)
    {
        $rows = [];
        foreach ($cols as $key => $col) {
            $rows[$key] = $col;
            if ($key == $key) {
                $rows[$new['name']] = $new;
            }
        }
        return $rows;
    }

    public function haeder($options)
    {
        $table = $options['table'];
        $model  = Model::where('table', $table)->first();
        $fields = Field::where('model_id', $model['id'])->orderBy('sort', 'asc')->get()->toArray();
        $res = $join = $select = $search = $steps = [];

        $select = [$table.'.id', $table.'.created_by', $table.'.created_at'];

        $dialogs = [];
        $cols = [];

        foreach ($fields as $field) {
            if ($field['is_index'] == 1) {
                $setting = json_decode($field['setting'], true);

                $column = $index = '';
                $_field = $field['field'];

                if ($field['form_type'] == 'urd') {
                    $select[] = $table.'.'.$_field;
                    $field['field'] = str_replace('_id', '_text', $_field);
                }

                if ($field['form_type'] == 'autocomplete') {
                    $dialog = Module::dialogs($setting['type']);
                    $select[] = $table.'.'.$_field;
                    $join[]   = [$dialog['table'].' as '.$_field.'_'.$dialog['table'], $_field.'_'.$dialog['table'].'.id', '=', $table.'.'.$_field];
                    $select[] = $_field.'_'.$dialog['field'].' as '.$_field.'_'.$dialog['table'];
                    $index    = $_field.'_'.$dialog['field'];
                    $column   = $_field.'_'.$dialog['table'];
                    
                } else if ($field['form_type'] == 'dialog' || $field['form_type'] == 'select2') {
                    $type = $setting['type'];
                    $select[] = $table.'.'.$_field;
                    $index    = $table.'.'.$_field;
                    $column   = $_field.'_'.$type;
                    
                } else {
                    $column = $field['field'];
                    $index    = $table.'.'.$field['field'];
                    $select[] = $table.'.'.$field['field'];
                }

                $field['field']   = $column;
                $field['index']   = $index;
                $field['setting'] = $setting;

                $res['columns'][$field['field']] = $field;

                $col = [];
                $col['name']     = $column;
                $col['index']    = $index;
                $col['field']    = $_field;
                $col['label']    = $field['name'];
                $col['sortable'] = (bool)$field['is_sort'];
                $col['align']    = $setting['align'];
                if ($setting['width'] == 'auto') {
                    $col['minWidth'] = 120;
                } elseif ($setting['width']) {
                    $col['width'] = (int)$setting['width'];
                }
                $res['cols'][$field['field']] = $col;

                // 搜索字段
                if ($field['is_search'] == 1) {
                    $form_type = $field['form_type'];
                    $_options  = [];

                    if ($field['form_type'] == 'date') {
                        $form_type = 'date2';
                    }

                    if ($field['form_type'] == 'address') {
                        $form_type = 'text';
                    }

                    if ($field['form_type'] == 'dialog') {
                        $form_type = 'dialog';
                        $_options = Module::dialogs($setting['type']);
                    }

                    if ($field['form_type'] == 'select2') {
                        $form_type = 'dialog';
                        $_options = Module::dialogs($setting['type']);
                    }

                    if ($field['form_type'] == 'autocomplete') {
                        $form_type = 'text';
                    }

                    if ($field['form_type'] == 'textarea') {
                        $form_type = 'text';
                    }

                    if ($field['form_type'] == 'select') {
                        $form_type = 'text';
                    }

                    if ($field['form_type'] == 'option') {
                        $form_type = $setting['type'];
                    }

                    if ($field['form_type'] == 'auto') {
                        $form_type = 'text';
                    }

                    if ($field['form_type'] == 'sn') {
                        $form_type = 'text';
                    }

                    if ($field['form_type'] == 'urd') {
                        $form_type = 'text';
                    }

                    if ($column == 'step_sn') {
                        $form_type = 'model_step.'.$table;
                    }

                    $search[] = [
                        'form_type' => $form_type,
                        'field'     => $field['index'],
                        'name'      => $field['name'],
                        'options'   => $_options,
                    ];
                }
            }
        }

        // 动作列
        $res['cols']['actionLink'] = [
            'name'      => 'actionLink',
            'formatter' => 'actionLink',
            'options'   => [],
            'label'     => ' ',
            'width'     => 100,
            'sortable'  => false,
            'align'     => 'center',
        ];

        // 有流程
        if ($model['is_flow']) {
            $steps = Step::where('model_id', $model['id'])->orderBy('sn', 'asc')->get();
            $steps = array_by($steps, 'sn');
            $select[] = $table.'.step_status';
        }

        $search_form = search_form($options['search'], $search, $options['referer'], 'model');

        $sort  = Input::get('sort');
        $sort = $sort == '' ? $table.'.id' : $sort;
        $order = Input::get('order', 'desc');
        
        $res['join']        = $join;
        $res['dialog']      = $dialogs;
        $res['select']      = array_unique($select);
        $res['search']      = $search;
        $res['search_form'] = $search_form;
        
        $res['steps']       = $steps;
        $res['model_id']    = $model['id'];
        $res['name']        = $model['name'];
        $res['is_sort']     = $model['is_sort'];
        $res['is_flow']     = $model['is_flow'];
        $res['table']       = $table;
        $res['sort']        = $sort;
        $res['order']       = $order;

        $res['create_btn']  = isset($options['create_btn']) ? $options['create_btn'] : 1;
        $res['trash_btn']  = isset($options['trash_btn']) ? $options['trash_btn'] : 1;
    
        return $res;
    }

    public static function js($haeder)
    {
        $table = $haeder['table'];
        $cols = [];
        foreach ($haeder['cols'] as $field => $col) {
            if ($field == 'action' && empty($col['events'])) {
                continue;
            }
            if ($col['field'] == 'created_by') {
                $col['formatter'] = 'created_by';
            }
            $cols[] = $col;
        }
        $search = [
            'search' => [
                'simple' => [
                    'el'    => null,
                    'query' => (array)$haeder['search_form']["query"],
                ],
                'advanced' => [
                    'el'    => null,
                    'query' => (array)$haeder['search_form']["query"],
                ],
                'forms' => (array)$haeder['search_form']["forms"],
            ],
            'cols' => $cols,
        ];

        $mc = Request::module().'/'.Request::controller();
        $routes = [
            'create' => $mc.'/create',
            'delete' => $mc.'/delete',
            'edit'   => $mc.'/edit',
            'show'   => $mc.'/show',
        ];
        $js = 'var '.$table.' = '.json_encode($search, JSON_UNESCAPED_UNICODE).';';
        $js .= "$table.action = new jqgridAction('".$table."', '".$haeder['name']."');";
        $js .= "$table.action.routes = ".json_encode($routes, JSON_UNESCAPED_UNICODE).';';
        $res = '<script>'.$js.'</script>';
        return $res;
    }
}