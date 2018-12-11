<?php namespace Aike\Workflow\Controllers;

use Input;
use Request;
use DB;

use Aike\Workflow\Workflow;
use Aike\Workflow\WorkflowCategory;

use Aike\Index\Controllers\DefaultController;

class CategoryController extends DefaultController
{
    // 流程类别
    public function indexAction()
    {
        $metaData = [
            'columns' => [[
                    'dataIndex' => 'id',
                    'sortable'  => true,
                    'width'     => 70,
                    'align'     => 'center',
                    'text'      => '编号'
                ],[
                    'dataIndex' => 'sort',
                    'width'  => 70,
                    'text'      => '排序',
                    'editor'    => [
                        'allowBlank' => false
                    ]
                ],[
                    'dataIndex' => 'title',
                    'flex'      => 1,
                    'minWidth'  => 280,
                    'text'      => '名称',
                    'editor'    => [
                        'allowBlank' => false
                    ],
                    'search'    => [
                        'name'  => 'title',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'remark',
                    'width'     => 200,
                    'text'      => '描述',
                    'editor'    => [
                        'allowBlank' => true
                    ]
                ]
            ]
        ];

        $search = search_form();
        $query  = $search['query'];

        $model = WorkflowCategory::orderBy('sort', 'ASC');

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->paginate($search['limit']);

        return $this->display([
            'rows' => $rows,
        ]);
    }

    // 添加分类
    public function addAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $gets = Input::get();

            if (empty($gets['title'])) {
                return $this->back()->with('error', '很抱歉，分类名称必须填写。');
            }

            if ($gets['id']) {
                DB::table('work_category')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('work_category')->insert($gets);
            }
            return $this->to('index')->with('message', '恭喜你，分类操作成功。');
        }

        $category = DB::table('work_category')->get();
        $row = DB::table('work_category')->where('id', $id)->first();

        return $this->display(array(
            'category' => $category,
            'row'      => $row,
        ));
    }

    // 删除流程类别
    public function deleteAction()
    {
        //if (Request::method() == 'POST') {
            $id = Input::get('id');
            if (empty($id)) {
                return $this->back()->with('error', '最少选择一行记录。');
            }

            $row = Workflow::where('category_id', $id)->first();
            if (!empty($row)) {
                return $this->back()->with('error', '此类别存在工作数据，无法删除。');
            }

            WorkflowCategory::where('id', $id)->delete();
            return $this->back()->with('message', '恭喜你，操作成功。');
        //}
    }
}
