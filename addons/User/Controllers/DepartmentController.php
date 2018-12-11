<?php namespace Aike\User\Controllers;

use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\User\Department;

use Aike\Index\Controllers\DefaultController;

class DepartmentController extends DefaultController
{
    public $permission = ['dialog'];
    
    public function indexAction()
    {
        $metaData = [
            'columns' => [[
                    'dataIndex' => 'text',
                    'xtype'     => 'treecolumn',
                    'flex'      => 1,
                    'minWidth'  => 200,
                    'sortable'  => true,
                    'text'      => '名称',
                    'search'    => [
                        'name'  => 'title',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'description',
                    'width'     => 200,
                    'text'      => '描述',
                ],[
                    'dataIndex' => 'id',
                    'sortable'  => true,
                    'text'      => '编号',
                    'align'     => 'center',
                    'width'     => 70,
                ]
            ]
        ];
        
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');

            foreach ($sorts as $id => $sort) {
                $department = Department::find($id);
                $department->sort = $sort;
                $department->save();
            }
            Department::treeRebuild();
            return $this->back('恭喜你，操作成功。');
        }

        $search = search_form([
            'referer' => 1,
        ], []);

        $rows = Department::orderBy('lft', 'asc')->get()->toArray();
        $rows = array_nest($rows);

        return $this->display([
            'res'   => $rows,
            'color' => $color,
        ]);
    }

    public function addAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $rules = [
                'title' => 'required'
            ];
            $v = Validator::make($gets, $rules);

            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            $model = Department::findOrNew($gets['id']);
            $model->fill($gets)->save();

            // 重构树形结构
            $model->treeRebuild();
            return $this->success('index', '恭喜您，操作成功。');
        }

        $res = Department::find($gets['id']);

        return $this->display([
            'res' => $res,
        ]);
    }

    public function dialogAction()
    {
        if (Request::method() == 'POST') {
            $model = \DB::table('department')
            ->orderBy('lft', 'asc');

            $rows = $model->get(['id', 'title', 'parent_id']);
            $rows = array_nest($rows, 'title');

            foreach ($rows as $row) {
                $json[] = [
                    'id'    => $row['id'],
                    'sid'   => 'd'.$row['id'],
                    'title' => $row['title'],
                    'text'  => $row['layer_space'].$row['title'],
                ];
            }

            $json[] = [
                'id'    => 0,
                'sid'   => 'all',
                'title' => '全体人员',
                'text'  => '全体人员',
            ];

            return response()->json($json);
        }
        return $this->render([
            'get' => Input::get()
        ]);
    }

    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $id = array_filter((array)$id);

            if (empty($id)) {
                return $this->error('最少选择一行记录。');
            }

            $has = Department::whereIn('parent_id', $id)->count();
            if ($has) {
                return $this->error('存在子节点不允许删除。');
            }

            // 删除部门
            Department::whereIn('id', $id)->delete();
            
            // 重构树形结构
            Department::treeRebuild();

            return $this->back('删除成功。');
        }
    }
}
