<?php namespace Aike\User\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Model\Grid;
use Aike\Model\Form;
use Aike\User\User;
use Aike\User\UserPosition;
use Aike\Index\Controllers\DefaultController;

class PositionController extends DefaultController
{
    public $permission = ['dialog'];

    public function indexAction()
    {
        $header = Grid::header([
            'table'     => 'user_position',
            'referer'   => 1,
            'search'    => ['by' => ''],
            'trash_btn' => 0,
        ]);

        $cols = $header['cols'];

        $cols['actions']['options'] = [[
            'name'    => '编辑',
            'action'  => 'edit',
            'display' => $this->access['edit'],
        ]];

        $search = $header['search_form'];
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = UserPosition::setBy($header);
            foreach ($header['join'] as $join) {
                $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $model->orderBy($header['sort'], $header['order'])
            ->orderBy('id', 'desc');

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            if ($query['export']) {
                $rows = $model->get($header['select']);
            } else {
                $rows = $model->paginate($search['limit'], $header['select'])->appends($query);
            }

            $items = Grid::dataFilter($rows, $header);

            if ($query['export']) {
                unset($cols['actions']);
                writeExcel($cols, $items, $header['name']. date('Y-m-d'));
            }

            return $items->toJson();
        }

        $header['buttons'] = [
            ['name' => '删除', 'icon' => 'fa-remove', 'action' => 'delete', 'display' => $this->access['delete']],
        ];
        $header['cols'] = $cols;
        $header['tabs'] = User::$tabs;
        $header['bys']  = UserPosition::$bys;
        $header['js']   = Grid::js($header);

        return $this->display([
            'header' => $header,
        ]);
    }

    // 新建客户联系人
    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = Form::rules([
                'table' => 'user_position',
            ]);
            $v = Validator::make($gets, $rules['rules'], $rules['messages'], $rules['attributes']);
            if ($v->fails()) {
                return $this->json(join('<br>',$v->errors()->all()));
            }
            $_position = $gets['user_position'];
            $position = UserPosition::findOrNew($_position['id']);
            $position->fill($_position)->save();

            return $this->json('恭喜您，操作成功。', url_referer('index'));
        }

        $id = (int)Input::get('id');
        $position = UserPosition::find($id);

        $header = [
            'table' => 'user_position',
        ];
        if ($position->id) {
            $header['row'] = $position;
        }
        $header['tpl'] = Form::make($header);
        return $this->render([
            'header' => $header,
        ], 'create');
    }

    // 创建客户联系人
    public function editAction()
    {
        return $this->createAction();
    }

    // 显示客户联系人
    public function showAction()
    {
        $id = (int)Input::get('id');
        $position = UserPosition::find($id);
        $header = [
            'table' => 'user_position',
            'row'   => $position,
        ];
        $header['tpl'] = Form::show($header);
        return $this->render([
            'header' => $header,
        ]);
    }

    // 删除
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $id = array_filter((array)$id);

            if (empty($id)) {
                return $this->json('最少选择一行记录。');
            }

            $positions = UserPosition::whereIn('id', $id)->get();
            foreach ($positions as $position) {
                // 删除数据
                $position->delete();
            }
            return $this->json('恭喜你，操作成功。', url_referer('index'));
        }
    }
}