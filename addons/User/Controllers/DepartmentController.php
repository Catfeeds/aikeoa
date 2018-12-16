<?php namespace Aike\User\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\User\Department;
use Aike\Model\Grid;
use Aike\Model\Form;

use Aike\Index\Controllers\DefaultController;

class DepartmentController extends DefaultController
{
    public $permission = ['dialog'];
    
    public function indexAction()
    {
        $display = $this->access;

        $haeder = Grid::haeder([
            'table'   => 'department',
            'referer' => 1,
            'search'  => ['by' => ''],
        ]);

        $cols = $haeder['cols'];
        $cols = Grid::addCols($cols, 'name', [
            'label' => '用户数',
            'name'  => 'user_count',
            'align' => 'center',
        ]);

        $cols['actionLink']['options'] = [[
            'name'    => '编辑',
            'action'  => 'edit',
            'display' => $display['edit'],
        ]];

        $search = $haeder['search_form'];
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = Department::setBy($haeder);
            foreach ($haeder['join'] as $join) {
                $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $model->leftJoin('user', 'user.department_id', '=', 'department.id');
            $model->orderBy('department.lft', 'asc');

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $model->select($haeder['select'])
            ->addSelect(DB::raw('department.parent_id,count(user.id) as user_count'))
            ->groupBy('department.id');

            $items = $model->get()->toNested();
            $items = Grid::dataFilter($items, $haeder);
            return $items->toJson();
        }

        $haeder['buttons'] = [[
            'name'    => '删除',
            'icon'    => 'fa-remove',
            'action'  => 'delete',
            'display' => $display['delete']
        ]];
        $haeder['cols'] = $cols;
        $haeder['tabs'] = User::$tabs;
        $haeder['bys']  = Department::$bys;
        $haeder['js']   = Grid::js($haeder);

        return $this->display([
            'haeder' => $haeder,
        ]);
    }

    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = Form::rules([
                'table' => 'department',
            ]);
            $v = Validator::make($gets, $rules['rules'], $rules['messages'], $rules['attributes']);
            if ($v->fails()) {
                return $this->json($v->errors()->all());
            }
            $_department = $gets['department'];
            $department = Department::findOrNew($_department['id']);

            $_department['parent_id'] = (int)$_department['parent_id'];
            $department->fill($_department)->save();

            // 重构树形结构
            Department::treeRebuild();

            return $this->json('恭喜您，操作成功。', url_referer('index'));
        }

        $id = (int)Input::get('id');
        $department = Department::find($id);

        $options = [
            'table' => 'department',
        ];
        if ($department->id) {
            $options['row'] = $department;
        }
        $tpl = Form::make($options);
        return $this->render([
            'tpl' => $tpl,
        ], 'create');
    }

    public function editAction()
    {
        return $this->createAction();
    }

    public function dialogAction()
    {
        $search = search_form([], [
            ['text','department.title','名称'],
            ['text','department.id','ID'],
        ]);

        if (Request::method() == 'POST') {
            $rows = Department::orderBy('lft', 'asc')->get()->toNested();
            $data  = [];
            foreach ($rows as $row) {
                $row['sid'] = 'd'.$row['id'];
                $row['text'] = $row['layer_space'].$row['title'];
                $data[] = $row;
            }
            $data[] = [
                'id'    => 0,
                'sid'   => 'all',
                'title' => '全体人员',
                'text'  => '全体人员',
            ];
            return response()->json(['data' => $data]);
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
                return $this->json('最少选择一行记录。');
            }

            $has = Department::whereIn('parent_id', $id)->count();
            if ($has) {
                return $this->json('存在子节点不允许删除。');
            }

            // 删除部门
            Department::whereIn('id', $id)->delete();
            
            // 重构树形结构
            Department::treeRebuild();

            return $this->json('删除成功。', true);
        }
    }
}
