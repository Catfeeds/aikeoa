<?php namespace Aike\Model\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;

use Aike\Model\Model;
use Aike\Model\Field;
use Aike\Model\Step;
use Aike\Model\StepLog;

use Aike\Index\Controllers\DefaultController;

class ModelController extends DefaultController
{
    public function indexAction()
    {
        $parent_id = Input::get('parent_id', 0);
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            foreach ($sorts as $id => $sort) {
                $field = Model::find($id);
                $field->sort = $sort;
                $field->save();
            }
            return $this->success('index', '恭喜你，操作成功。');
        }

        $model = Model::where('parent_id', $parent_id)->orderBy('sort', 'asc');
        $rows = $model->paginate();

        return $this->display([
            'rows'      => $rows,
            'parent_id' => $parent_id,
        ]);
    }

    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'name'  => 'required',
                'table' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->json(join("<br>", $v->errors()->all()));
            }
            $model = Model::findOrNew($gets['id']);
            $model->fill($gets);
            $model->save();
            $model->treeRebuild();
            return $this->json('恭喜你，操作成功。', url('index'));
        }

        $id     = Input::get('id');
        $row    = Model::find($id);
        $models = Model::where('parent_id', 0)->get();
        
        return $this->display([
            'row'    => $row,
            'models' => $models,
        ]);
    }

    public function editAction()
    {
        return $this->createAction();
    }

    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id > 0) {
            Model::find($id)->delete();
            Field::where('model_id', $id)->delete();
            Step::where('model_id', $id)->delete();
            StepLog::where('model_id', $id)->delete();
            return $this->success('index', '恭喜你，操作成功。');
        }
    }
}
