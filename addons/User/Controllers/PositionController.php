<?php namespace Aike\User\Controllers;

use DB;
use Input;
use Request;

use Aike\User\UserPosition;
use Aike\Index\Controllers\DefaultController;

class PositionController extends DefaultController
{
    // 职位列表
    public function indexAction()
    {
        $search = search_form();
        $query  = $search['query'];

        $model = UserPosition::query();

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->paginate();

        return $this->display([
            'search'    => $search,
            'query'     => $query,
            'rows'      => $rows,
            'positions' => $positions,
        ]);
    }

    // 编辑职位
    public function editAction()
    {
        if (Request::method() == 'POST') {
            $get = Input::get();
            $position = UserPosition::findOrNew($get['id']);
            $position->fill($get)->save();
            return $this->json($position->id, true);
        }
    }

    // 新建职位
    public function addAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $gets = Input::get();

            if (empty($gets['title'])) {
                return $this->back()->with('error', '很抱歉，职位名称必须填写。');
            }

            if ($gets['id']) {
                DB::table('user_position')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('user_position')->insert($gets);
            }
            return $this->to('index')->with('message', '恭喜你，职位更新成功。');
        }
        
        $row = DB::table('user_position')->where('id', $id)->first();

        return $this->display([
            'row' => $row,
        ]);
    }

    // 删除职位
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            if (empty($id)) {
                return $this->back()->with('error', '最少选择一行记录。');
            }
            UserPosition::whereIn('id', $id)->delete();
            return $this->back()->with('message', '恭喜你，操作成功。');
        }
    }
}
