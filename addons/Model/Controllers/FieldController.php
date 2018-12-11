<?php namespace Aike\Model\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;

use Aike\Model\Model;
use Aike\Model\Field;
use Aike\Model\Form;

use Aike\Index\Controllers\DefaultController;

class FieldController extends DefaultController
{
    public function indexAction()
    {
        $model_id = Input::get('model_id');

        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            $i = 0;
            foreach ($sorts as $id) {
                Field::where('id', $id)->update(['sort' => $i]);
                $i ++;
            }
            return $this->json('恭喜你，操作成功。', true);
        }

        $master = Model::with(['fields' => function ($q) {
            $q->orderBy('sort', 'asc')->orderBy('id', 'asc');
        }])->find($model_id);

        $sublist = Model::with(['fields' => function ($q) {
            $q->orderBy('sort', 'asc')->orderBy('id', 'asc');
        }])->where('parent_id', $master->id)->get();

        $models = DB::table('model')->where('parent_id', 0)->orderBy('lft', 'asc')->get();
        $model = Model::find($model_id);

        return $this->display([
            'master'   => $master,
            'sublist'  => $sublist,
            'model_id' => $model_id,
            'model'    => $model,
            'models'   => $models,
        ]);
    }

    public function createAction()
    {
        $model_id  = Input::get('model_id');
        $id        = Input::get('id');

        $row      = Field::find($id);
        $model    = Model::find($model_id);

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'name'      => 'required',
                'field'     => 'required|unique:model_field,field,'.$id.',id,model_id,'.$model_id,
                'form_type' => 'required',
                'type'      => 'required',
            ];

            if ($gets['validate']) {
                $gets['validate'] = join('|', $gets['validate']);
            }
            
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            // 字段管理
            $templates = [
                'BIGINT'    => ['bigint', '10', 'NOT NULL', '0'],
                'INT'       => ['int', '10', 'NOT NULL', '0'],
                'MEDIUMINT' => ['mediumint', '8', 'NOT NULL', '0'],
                'SMALLINT'  => ['smallint', '5', 'NOT NULL', '0'],
                'TINYINT'   => ['tinyint', '3', 'NOT NULL', '0'],
                'DECIMAL'   => ['decimal', '10,2', 'NOT NULL', '0.00'],
                'DATE'      => ['date', '', 'NULL', ''],
                'DATETIME'  => ['datetime', '', 'NULL', ''],
                'CHAR'      => ['char', '50', 'NOT NULL', ''],
                'VARCHAR'   => ['varchar', '255', 'NOT NULL', ''],
                'TEXT'      => ['text', '0', 'NOT NULL', '']
            ];

            $template = $templates[$gets['type']];
            $field    = $template[0];
            $length   = $gets['length'] ? $gets['length'] : $template[1];
            $null     = ' '.$template[2];

            if($field == 'date' || $field == 'datetime') {
                $default  = '';
            } else {
                $default  = ' default '."'".$template[3]."'";
            }

            $comment  = ' COMMENT '."'".$gets['name']."'";
            $after    = ' '.$gets['after'];

            $length = $length == '' ? '' : '('.$length.')';

            $table = $model->getAttribute('table');
            $sql = [];

            $columns = DB::select('SHOW COLUMNS FROM '.$table);
            $columns = array_by($columns, 'Field');

            if ($gets['id'] && isset($columns[$row['field']])) {
                // 修改字段
                if ($row['field'] != $gets['field'] || $row['type'] != $gets['type'] || $row['length'] != $gets['length']) {
                    $sql[] = 'ALTER TABLE '.$table.' CHANGE `'.$row['field'].'` `'.$gets['field'].'` '.$field.$length.$null.$default.$comment.$after;
                }

                if ($row['field'] != $gets['field']) {
                    // 删除旧索引
                    if ($row['index']) {
                        $sql[] = 'ALTER TABLE '.$table.' DROP INDEX idx_'.$row['field'];
                    }
                    // 添加新索引
                    if ($gets['index']) {
                        $sql[] = 'ALTER TABLE '.$table.' ADD '.$gets['index'].' idx_'.$gets['field'].' (`'.$gets['field'].'`)';
                    }
                }
            } else {
                // 字段不存在
                if (!isset($columns[$gets['field']])) {
                    // 添加字段
                    $sql[] = 'ALTER TABLE '.$table.' ADD `'.$gets['field'].'` '.$field.$length.$null.$default.$comment.$after;
                    // 添加字段索引
                    if ($gets['index']) {
                        $sql[] = 'ALTER TABLE '.$table.' ADD '.$gets['index'].' idx_'.$gets['field'].' (`'.$gets['field'].'`)';
                    }
                }
            }

            // 操作数据表
            foreach ($sql as $_sql) {
                DB::statement($_sql);
            }
 
            // 写入模型数据
            $gets['setting'] = json_encode($gets['setting']);

            $model = Field::findOrNew($gets['id']);
            $model->fill($gets);
            $model->save();

            return $this->to('index', ['model_id' => $gets['parent_id']], '恭喜你，操作成功。');
        }

        if ($row['validate']) {
            $row['validate'] = explode('|', $row['validate']);
        }

        $regulars = Model::regulars();
        
        $parent_id = Input::get('parent_id');
        $models[]  = DB::table('model')->find($parent_id);
        $childrens = DB::table('model')->where('parent_id', $models[0]['id'])->get();
        foreach ($childrens as $children) {
            $models[] = $children;
        }

        return $this->display(array(
            'row'       => $row,
            'model'     => $model,
            'models'    => $models,
            'parent_id' => $parent_id,
            'model_id'  => $model_id,
            'regulars'  => $regulars,
        ));
    }

    /**
     * 获取字段类型
     */
    public function typeAction()
    {
        $type = Input::get('type');
        if ($type) {
            $act = 'form_'.$type;
            return Field::$act();
        }
    }

    public function deleteAction()
    {
        $id       = Input::get('id');
        $model_id = Input::get('model_id');
        $id       = is_array($id) ? $id : [$id];

        Field::whereIn('id', $id)->delete();
        
        return $this->success('index', ['model_id' => $model_id], '恭喜你，操作成功。');
    }
}
