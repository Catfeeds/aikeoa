<?php namespace Aike\Model\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;

use Aike\Model\Model;
use Aike\Model\Field;
use Aike\Model\Permission;

use Aike\Index\Controllers\DefaultController;

class PermissionController extends DefaultController
{
    public $permission = ['index','create','delete'];

    public function indexAction()
    {
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            $i = 0;
            foreach ($sorts as $sort) {
                Permission::where('id', $sort)->update(['sort' => $i]);
                $i ++;
            }
            return $this->json('恭喜你，操作成功。', true);
        }

        $model_id = Input::get('model_id');

        $rows = Permission::where('model_id', $model_id)
        ->orderBy('sort', 'asc')
        ->get();

        $models = DB::table('model')->where('parent_id', 0)->orderBy('lft', 'asc')->get();
        $model = Model::find($model_id);

        return $this->display([
            'rows'     => $rows,
            'model_id' => $model_id,
            'models'   => $models,
            'model'    => $model,
        ]);
    }

    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'name' => 'required',
            ];
            $v = Validator::make($gets, $rules);

            $gets['data'] = json_encode($gets['data']);
            $gets['type'] = join(',', (array)$gets['type']);

            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            if ($gets['id']) {
                Permission::where('id', $gets['id'])->update($gets);
            } else {
                Permission::insert($gets);
            }

            return $this->success('index', ['model_id' => $gets['model_id']], '恭喜你，操作成功。');
        }

        $id          = Input::get('id');
        $model_id    = Input::get('model_id');

        $permission  = Permission::find($id);

        $permission['data'] = json_decode($permission['data'], true);

        $model = Model::with(['fields' => function ($q) {
            $q->orderBy('sort', 'asc')->orderBy('id', 'asc');
        }])
        ->where('id', $model_id)->first();

        $sublist = Model::with(['fields' => function ($q) {
            $q->orderBy('sort', 'asc')->orderBy('id', 'asc');
        }])->where('parent_id', $model->id)->get();

        $columns[$model->table]['master'] = 1;
        $columns[$model->table]['fields'] = $model->fields;

        foreach ($sublist as $children) {
            $columns[$children->table]['master'] = 0;
            $columns[$children->table]['name']   = $children['name'];
            $columns[$children->table]['fields'] = $children->fields;
        }

        $regulars = Model::regulars();

        $permission['model_id'] = $model_id;
        $permission['type'] = explode(',', $permission['type']);

        $models = DB::table('model')->where('parent_id', 0)->orderBy('lft', 'asc')->get();

        $model = Model::find($model_id);

        return $this->display([
            'permission' => $permission,
            'columns'    => $columns,
            'regulars'   => $regulars,
            'model_id'   => $model_id,
            'models'     => $models,
            'model'      => $model,
        ]);
    }

    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id > 0) {
            Permission::where('id', $id)->delete();
            return $this->success('index', '恭喜你，操作成功。');
        }
    }
}
