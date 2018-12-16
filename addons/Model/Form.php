<?php namespace Aike\Model;

use DB;
use Request;
use Input;
use Validator;
use URL;
use Auth;
use AES;
use Hook;
use Module;

use Aike\Model\Model;
use Aike\Model\Field;
use Aike\Model\Permission;
use Aike\Model\Template;
use Aike\Model\Step;
use Aike\Model\StepLog;
use Aike\Index\Attachment;
use Aike\Index\Access;

class Form
{
    public static function make($options)
    {
        // 权限查询类型
        // $type_sql = [db_instr('type', 'create'), db_instr('type', 'edit')];
        $exclude = (array)$options['exclude'];
        $table   = $options['table'];
        $row     = $options['row'];

        $type_sql = '('.join(' or ', [db_instr('type', 'create')]).')';
        $html = '';
        // 表数据
        $master = DB::table('model')
        ->where('table', $table)
        ->first();

        $fields = DB::table('model_field')
        ->where('model_id', $master['id'])
        ->orderBy('sort', 'asc')
        ->get()->keyBy('field');

        $_permission = DB::table('model_permission')
        ->permission('receive_id')
        ->whereRaw($type_sql)
        ->where('model_id', $master['id'])
        ->first();
        $permission = json_decode($_permission['data'], true);

        $template = DB::table('model_template')
        ->permission('receive_id')
        ->whereRaw($type_sql)
        ->where('model_id', $master['id'])
        ->first();
        $views = json_decode($template['tpl'], true);

        $first = false;

        $js = '<script type="text/javascript">jqgridFormList.'.$master['table'].' = [];';
        $js .= '$(function() {';

        foreach ($views as $k => $group) {
            $tpl = '';
            foreach ($group['fields'] as $view) {
                $field = $fields[$view['field']];

                // 跳过排除字段
                if (in_array($table.'.'.$view['field'], $exclude)) {
                    continue;
                }

                // 是多行子表
                if ($view['type'] == 1) {
                    $sublist[] = $view;
                }

                if ($col == 0) {
                    $tpl .= '<div class="form-group '.($first == false ? 'no-border' : '').'">';
                }

                $first = true;

                // 补全错位
                if ($col > 0 && $view['col'] == 12) {
                    $tpl .= '<label class="col-sm-2 control-label"></label>';
                    $tpl .= '<div class="col-sm'.($col - 10).' control-text"></div>';
                    $tpl .= '</div><div class="form-group">';
                    $col = 0;
                }

                $col += $view['col'];

                if ($view['type'] == 0) {
                    $tpl .= '<label class="col-sm-2 control-label">{'.$view['name'].'}</label>';
                }

                if ($view['type'] == 1) {
                    $right_col = $view['col'];
                } else {
                    $right_col = $view['col'] - 2;
                }

                $tpl .= '<div class="col-sm-'.$right_col.' control-text">{'.$view['field'].'}</div>';
                
                if ($col == 12) {
                    $col = 0;
                    $tpl .= '</div>';
                }

                $field['model'] = $master;

                $attribute = [];

                $p = $permission[$master['table']][$field['field']];
                $field['is_read'] = $p['w'] == 1 ? 0 : 1;
                $field['is_auto'] = $p['m'] == 1 ? 1 : 0;
                $field['is_hide'] = $p['s'] == 1 ? 1 : $field['is_hide'];

                // 单据编码规则
                $field['data_sn_rule'] = $master['data_sn_rule'];
                $field['data_sn']      = $master['data_sn'];

                $validate = (array)$p['v'];

                $required = '';
                if (in_array('required', $validate)) {
                    $required = '<span class="red">*</span> ';
                    $attribute['required'] = 'required';
                }

                $field['verify']    = $validate;
                $field['attribute'] = $attribute;
                $field['table']     = $table;

                $tooltip = $field['tips'] ? ' <a class="hinted" href="javascript:;" title="'.$field['tips'].'"><i class="fa fa-question-circle"></i></a>' : '';

                $_replace['{'. $field['name'].'}'] = $required.$field['name'].$tooltip;
                if ($field['form_type']) {
                    $_replace['{'. $field['field'].'}'] = Field::{'content_'.$field['form_type']}($field, $row[$field['field']], $row);
                }
            }

            // 有子表
            if ($sublist) {
                foreach ($sublist as $_view) {

                    $model = DB::table('model')->where('table', $_view['field'])->first();
    
                    $fields = Field::where('model_id', $model['id'])
                    ->orderBy('sort', 'asc')
                    ->get();
                    $fields = array_by($fields, 'field');
    
                    $editoptions = $counts = $rowCounts = [];
    
                    $columns = [
                        ['name' => "id", 'hidden' => true],
                    ];
    
                    $permission_table  = $permission[$model['table']];
                    $permission_option = $permission_table['@option'];
                    if ($permission_option['w']) {
                        $columns[] = ['name' => 'op', 'label' => '&nbsp;', 'formatter' => 'options', 'width' => 60, 'sortable' => false, 'align' => 'center'];
                    }

                    // 查询子表数据
                    $q = DB::table($model['table'])->where($model['relation'], $row['id']);

                    $views = $_view['fields'];
    
                    $_data = Hook::fire($model['table'].'.onBeforeForm', ['q' => $q, 'model' => $model, 'fields' => $fields, 'views' => $views]);
                    extract($_data);
                    
                    foreach ($views as $view) {
                        $field = $fields[$view['field']];

                        $column = [];

                        // 数据类型格式化
                        switch ($field['type']) {
                            case 'DECIMAL':
                                list($_, $len) = explode(',', $field['length']);
                                $column['formatter'] = 'number';
                                $column['formatoptions'] = [
                                    'decimalSeparator'   => '.',
                                    'thousandsSeparator' => ',',
                                    'decimalPlaces'      => (int)$len,
                                    'defaultValue'       => number_format(0, $len),
                                ];
                                break;
                        }

                        $setting = json_decode($field['setting'], true);

                        if ($setting['align']) {
                            $column['align'] = $setting['align'];
                        }

                        // 合计事件
                        if ($setting['total_count']) {
                            $counts[] = ['field' => $field['field'], 'type' => $setting['total_count']];
                        }

                        // 行计事件
                        if ($setting['row_count']) {
                            $rowCounts[] = ['field' => $field['field'], 'rule' => $setting['row_count']];
                        }

                        $permission_field = $permission_table[$field['field']];
                        $validates = $permission_field['v'];

                        $required = '';

                        if ($validates) {
                            $rules = [];

                            foreach ($validates as $validate) {
                                // 设置验证规则
                                $rules[$validate] = 1;
                            }

                            // 整形规则格式化
                            if ($rules['integer']) {
                                $column['formatter'] = 'integer';
                            }

                            // 如果规则有必填和整形设置大于0
                            if ($rules['required'] && $rules['integer']) {
                                $rules['minValue'] = 1;
                            }
                            $column['rules'] = $rules;
                            $required = isset($rules['required']) ? '<span class="red">*</span> ' : '';
                        }

                        $column['label'] = $required.$field['name'];
                        $column['name']  = $field['field'];
                        
                        if ($field['form_type'] == 'label') {
                            $column['editable'] = false;
                        } else {
                            $column['editable'] = $permission_field['w'] == 1 ? true : false;
                        }

                        // 是否隐藏
                        $column['hidden'] = $permission_field['s'] == 1 ? true : (bool)$view['hidden'];

                        // 字段宽度
                        if ($setting['width']) {
                            if ($setting['width'] == 'auto') {
                                $column['minWidth'] = 280;
                            } else {
                                $column['width'] = $setting['width'];
                            }
                        }

                        if ($field['form_type'] == 'date') {
                            $editoptions[$field['field']] = [
                                'form_type' => $field['form_type'],
                                'type'      => $setting['type'],
                                'field'     => $field['field'],
                            ];
                        }

                        if ($field['form_type'] == 'option') {
                            $_option = option($setting['type'])->toArray();
                            foreach ($_option as $k => $v) {
                                $_option[$k]['text'] = $v['name'];
                            }
                            $editCombo[$field['field']] = $_option;
                            $editoptions[$field['field']] = [
                                'form_type' => $field['form_type'],
                                'field'     => $field['field'],
                            ];
                            $column['formatter'] = 'dropdown';
                        }

                        if ($field['form_type'] == 'dataset') {

                            // 映射列表选择的字段
                            $map = [];
                            $_id   = explode(':', $setting['id']);
                            $_name = explode(':', $setting['name']);

                            $map[$_id[0]]   = $_id[1];
                            $map[$_name[0]] = $_name[1];

                            $maps = explode("\n", $setting['map']);
                            foreach ($maps as $_map) {
                                $_map   = explode(':', $_map);
                                $map[trim($_map[0])] = trim($_map[1]);
                            }

                            $dialog = Module::dialogs($setting['type']);

                            $editoptions[$field['field']] = [
                                'form_type' => $field['form_type'],
                                'title'     => $dialog['name'],
                                'type'      => $setting['type'],
                                'field'     => $field['field'],
                                'srcField'  => $_id[0],
                                'textField' => $field['field'],
                                'mapField'  => $map,
                                'display'   => $setting['display'],
                                'url'       => $dialog['url'],
                            ];
                        }
                        $columns[] = $column;
                    }
                }

                $buttons .= '<input type="hidden" name="models['.$model['table'].'][type]" value="'.$model['type'].'">';
                $buttons .= '<input type="hidden" name="models['.$model['table'].'][relation]" value="'.$model['relation'].'">';
                
                // 子表权限
                $multiselect = false;

                // 子表查询
                $rows = $q->get();

                $_data = Hook::fire($model['table'].'.onAfterForm', ['rows' => $rows, 'gets' => $gets, 'id' => $id, 'multiselect' => $multiselect]);
                extract($_data);

                $_options = [
                    'autoOption'  => $permission_option['w'],
                    'multiselect' => $multiselect,
                    'editCombo'   => $editCombo,
                    'columns'     => $columns,
                    'editoptions' => $editoptions,
                    'counts'      => $counts,
                    'rowCounts'   => $rowCounts,
                    'data'        => $rows,
                    'title'       => $model['name'],
                ];

                $js .= 'jqgridForm("'.$table.'","'.$model['table'].'", '.json_encode($_options, JSON_UNESCAPED_UNICODE).');';
                
                $_replace['{'.$model['table'].'}'] = '<div id="jqgrid-editor-container" class="form-jqgrid"><table id="grid_'.$model['table'].'"></table></div>';
            }
            
            $html .= strtr($tpl, $_replace);
            if ($row['id']) {
                $html .= '<input type="hidden" name="'.$table.'[id]" id="'.$table.'_id" value="'.$row['id'].'">';
            }
            $html .= '<input type="hidden" name="uri" value="'.Request::module().'/'.Request::controller().'">';
            $html .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
        }

        $js .= '});</script>';

        $html .= $js;
        $html .= '<script type="text/javascript">
        $.each(select2List, function(k, v) {
            select2List[k].el = $("#" + k).select2Field(v.options);
        });</script>';
        
        return $html;
    }

    public static function show($options)
    {
        // 权限查询类型
        $exclude = (array)$options['exclude'];
        $table   = $options['table'];
        $row     = $options['row'];

        $type_sql = '('.join(' or ', [db_instr('type', 'show')]).')';
        $html = '';
        // 表数据
        $master = DB::table('model')
        ->where('table', $table)
        ->first();

        $fields = DB::table('model_field')
        ->where('model_id', $master['id'])
        ->orderBy('sort', 'asc')
        ->get()->keyBy('field');

        $_permission = DB::table('model_permission')
        ->permission('receive_id')
        ->whereRaw($type_sql)
        ->where('model_id', $master['id'])
        ->first();
        $permission = json_decode($_permission['data'], true);

        $template = DB::table('model_template')
        ->permission('receive_id')
        ->whereRaw($type_sql)
        ->where('model_id', $master['id'])
        ->first();
        $views = json_decode($template['tpl'], true);

        $first = false;
        foreach ($views as $k => $group) {
            $tpl = '';
            foreach ($group['fields'] as $view) {
                // 跳过排除字段
                if (in_array($table.'.'.$view['field'], $exclude)) {
                    continue;
                }

                if ($col == 0) {
                    $tpl .= '<div class="show-group '.($first == false ? 'no-border' : '').'">';
                }

                $first = true;

                // 补全错位
                if ($col > 0 && $view['col'] == 12) {
                    $tpl .= '<label class="col-sm-2 control-label" for=""></label>';
                    $tpl .= '<div class="col-sm'.($col - 10).' control-text"></div>';
                    $tpl .= '</div><div class="clearfix"></div><div class="show-group">';
                    $col = 0;
                }

                $col += $view['col'];

                if ($view['type'] == 0) {
                    $tpl .= '<label class="col-sm-2 control-label" for="">{'.$view['name'].'}</label>';
                }

                if ($view['type'] == 1) {
                    $right_col = $view['col'];
                } else {
                    $right_col = $view['col'] - 2;
                }

                $tpl .= '<div class="col-sm-'.$right_col.' control-text">{'.$view['field'].'}</div>';
                
                if ($col == 12) {
                    $col = 0;
                    $tpl .= '</div><div class="clearfix"></div>';
                }

                if (isset($fields[$view['field']])) {
                    $field = $fields[$view['field']];

                    $attribute = [];

                    $p = $permission[$master['table']][$field['field']];
                    $field['is_read'] = $p['w'] == 1 ? 0 : 0;
                    $field['is_auto'] = $p['m'] == 1 ? 1 : 0;
                    $field['is_hide'] = $p['s'] == 1 ? 1 : $field['is_hide'];
                    $field['is_show'] = 1;
                    
                    // 单据编码规则
                    $field['data_sn_rule'] = $master['data_sn_rule'];
                    $field['data_sn']      = $master['data_sn'];

                    $validate = (array)$p['v'];

                    $required = '';
                    if (in_array('required', $validate)) {
                        $required = '<span class="red">*</span> ';
                        $attribute['required'] = 'required';
                    }

                    $field['verify']    = $validate;
                    $field['attribute'] = $attribute;
                    $field['table']     = $table;

                    $tooltip = $field['tips'] ? ' <a class="hinted" href="javascript:;" title="'.$field['tips'].'"><i class="fa fa-question-circle"></i></a>' : '';

                    $_replace['{'. $field['name'].'}']  = $required.$field['name'].$tooltip;
                    $_replace['{'. $field['field'].'}'] = '<div>'.Field::{'content_'.$field['form_type']}($field, $row[$field['field']], $row).'</div>';
                }
            }
            $html .= strtr($tpl, $_replace);
        }
        return $html;
    }

    public static function rules($options)
    {
        // 权限查询类型
        $exclude = (array)$options['exclude'];
        $table   = $options['table'];

        $type_sql = '('.join(' or ', [db_instr('type', 'create')]).')';

        $rules = $messages = $attributes = [];
        $tables = is_string($table) ? [$table] : $table;

        foreach ($tables as $table) {
            $master = DB::table('model')
            ->where('table', $table)
            ->first();

            $fields = DB::table('model_field')
            ->where('model_id', $master['id'])
            ->orderBy('sort', 'asc')
            ->get()->keyBy('field');

            $_permission = DB::table('model_permission')
            ->permission('receive_id')
            ->whereRaw($type_sql)
            ->where('model_id', $master['id'])
            ->first();
            $permissions = json_decode($_permission['data'], true);
            foreach ($permissions[$table] as $k => $v) {
                if ($v['v']) {
                    // 跳过排除字段
                    if (in_array($table.'.'.$k, $exclude)) {
                        continue;
                    }
                    $rules[$table.'.'.$k] = join('|', (array)$v['v']);
                    $attributes[$table.'.'.$k] = $fields[$k]['name'];
                }
            }
        }
        return ['rules' => $rules, 'messages' => $messages, 'attributes' => $attributes];
    }
}
