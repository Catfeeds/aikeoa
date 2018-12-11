<?php namespace Aike\User\Controllers;

use DB;
use Input;
use Request;

use Aike\User\UserGroup;

use Aike\Index\Controllers\DefaultController;

class GroupController extends DefaultController
{
    // 用户组
    public function indexAction()
    {
        $metaData = [
            'columns' => [[
                    'dataIndex' => 'id',
                    'text'      => '编号',
                    'sortable'  => true,
                    'width'     => 70,
                    'align'     => 'center',
                ],[
                    'dataIndex' => 'name',
                    'text'      => '名称',
                    'flex'      => 1,
                    'minWidth'  => 200,
                    'editor'    => [
                        'allowBlank' => false
                    ],
                    'search'    => [
                        'name'  => 'name',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'key',
                    'text'      => '标签',
                    'width'     => 200,
                    'editor'    => [
                        'allowBlank' => false
                    ],
                    'search'    => [
                        'name'  => 'name',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'description',
                    'text'      => '描述',
                    'width'     => 200,
                    'editor'    => [
                        'allowBlank' => true
                    ]
                ]
            ]
        ];

        $search = search_form();
        $query  = $search['query'];

        $model = UserGroup::query();

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->paginate();

        return $this->display([
            'rows' => $rows,
        ]);
    }

    // 添加用户组
    public function addAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $gets = Input::get();

            if (empty($gets['name'])) {
                return $this->back()->with('error', '用户组名称必须填写。');
            }

            if ($gets['id']) {
                DB::table('user_group')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('user_group')->insert($gets);
            }
            return $this->to('index')->with('message', '恭喜你，用户组更新成功。');
        }

        $row = DB::table('user_group')->where('id', $id)->first();

        return $this->display(array(
            'row'  => $row,
        ));
    }

    // 删除用户组
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            if (empty($id)) {
                return $this->back()->with('error', '最少选择一行记录。');
            }
            UserGroup::whereIn('id', $id)->delete();
            return $this->back()->with('message', '恭喜你，操作成功。');
        }
    }
}
