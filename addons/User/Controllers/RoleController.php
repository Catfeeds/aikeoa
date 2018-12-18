<?php namespace Aike\User\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;
use Module;
use Collection;

use Aike\User\User;
use Aike\User\Role;
use Aike\User\UserAsset;
use Aike\Model\Grid;
use Aike\Model\Form;
use App\License;

use Aike\Index\Controllers\DefaultController;

class RoleController extends DefaultController
{
    public $permission = ['dialog'];

    public function indexAction()
    {
        $header = Grid::header([
            'table'   => 'role',
            'referer' => 1,
            'search'  => ['by' => ''],
        ]);

        $cols = $header['cols'];
        $cols = Grid::addCols($cols, 'name', [
            'label' => '用户数',
            'name'  => 'user_count',
            'align' => 'center',
        ]);
        /*
        $cols['checkbox'] = [
            'name'      => 'batch',
            'index'     => 'batch',
            'sortable'  => false,
            'label'     => '<input role="checkbox" data-action="checkboxAll" data-toggle="event" class="cbox" type="checkbox">', 
            'formatter' => 'checkbox', 
            'width'     => 60, 
            'align'     => 'center'
        ];
        */
        $cols['actions']['options'] = [[
            'name'    => '编辑',
            'action'  => 'edit',
            'display' => $this->access['edit'],
        ],[
            'name'    => '权限',
            'action'  => 'config',
            'display' => $this->access['config'],
        ]];

        $search = $header['search_form'];
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = Role::setBy($header);
            foreach ($header['join'] as $join) {
                $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $model->leftJoin('user', 'user.role_id', '=', 'role.id');
            $model->orderBy('role.lft', 'asc');

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $model->select($header['select'])
            ->addSelect(DB::raw('role.parent_id,count(user.id) as user_count'))
            ->groupBy('role.id');

            $items = $model->get()->toNested();
            $items = Grid::dataFilter($items, $header);
            return $items->toJson();
        }

        $header['buttons'] = [
            ['name' => '删除', 'icon' => 'fa-remove', 'action' => 'delete', 'display' => $this->access['delete']],
        ];
        $header['cols'] = $cols;
        $header['tabs'] = User::$tabs;
        $header['bys']  = Role::$bys;
        $header['js']   = Grid::js($header);

        return $this->display([
            'header' => $header,
        ]);
    }

    public function configAction()
    {
        $gets = Input::get();

        $query = [
            'role_id'  => 0,
            'clone_id' => 0,
            'key'      => '',
        ];

        foreach ($query as $key => $value) {
            $query[$key] = Input::get($key, $value);
        }

        if (Request::method() == 'POST') {
            $assets = DB::table('user_asset')->get();
            $assets = array_by($assets, 'name');
            $id     = $gets['role_id'];

            foreach ($gets['assets'] as $asset => $controllers) {
                $rules = json_decode($assets[$asset]['rules'], true);

                foreach ($controllers as $key => $actions) {
                    if ($actions['action']) {
                        $rules[$key][$id] = $actions['access'] > 0 ? $actions['access'] : $actions['action'];
                    } else {
                        unset($rules[$key][$id]);
                    }
                }

                $_asset = DB::table('user_asset')->where('name', $asset)->first();
                
                $data = [
                    'name'  => $asset,
                    'rules' => json_encode($rules),
                ];

                if (empty($_asset)) {
                    DB::table('user_asset')->insert($data);
                } else {
                    DB::table('user_asset')->where('id', $_asset['id'])->update($data);
                }
            }
            return $this->json('恭喜您，操作成功。', true);
        }

        if ($gets['clone_id']) {
            $clone_id = $gets['clone_id'];
        } else {
            $clone_id = $gets['role_id'];
        }

        $assets = UserAsset::getRoleAssets($clone_id);

        $modules = Module::allWithDetails();
        $modules = array_sort($modules, function ($value) {
            return $value['order'];
        });

        $roles = Role::orderBy('lft', 'asc')->get()->toNested();

        return $this->display(array(
            'assets'  => $assets,
            'modules' => $modules,
            'query'   => $query,
            'roles'   => $roles,
        ));
    }

    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = Form::rules([
                'table' => 'role',
            ]);
            $v = Validator::make($gets, $rules['rules'], $rules['messages'], $rules['attributes']);
            if ($v->fails()) {
                return $this->json($v->errors()->all());
            }
            $_role = $gets['role'];
            $role = Role::findOrNew($_role['id']);

            $_role['parent_id'] = (int)$_role['parent_id'];
            $role->fill($_role)->save();

            // 重构树形结构
            Role::treeRebuild();

            return $this->json('恭喜您，操作成功。', true);
        }

        $id = (int)Input::get('id');
        $role = Role::find($id);

        $header = [
            'table' => 'role',
        ];
        if ($role->id) {
            $header['row'] = $role;
        }
        $header['tpl'] = Form::make($header);
        return $this->render([
            'header' => $header,
        ], 'create');
    }

    public function editAction()
    {
        return $this->createAction();
    }

    public function dialogAction()
    {
        $search = search_form([], [
            ['text','role.name','名称'],
            ['text','role.id','ID'],
        ]);
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $rows = Role::orderBy('lft', 'asc')->get()->toNested();
            $data = [];
            foreach ($rows as $row) {
                $row['sid']  = 'r'.$row['id'];
                $row['text'] = $row['layer_space'].$row['name'];
                $data[] = $row;
            }
            return response()->json(['data' => $data]);
        }
        return $this->render([
            'get'  => Input::get(),
        ]);
    }

    // 删除角色
    public function deleteAction()
    {
        if (Request::method() == 'POST') {

            $id = Input::get('id');
            $id = array_filter((array)$id);

            if (empty($id)) {
                return $this->json('最少选择一行记录。');
            }

            $has = Role::whereIn('parent_id', $id)->count();
            if ($has) {
                return $this->json('存在子节点不允许删除。');
            }

            // 删除角色
            Role::whereIn('id', $id)->delete();

            // 重构树形结构
            Role::treeRebuild();

            return $this->json('删除成功。', true);
        }
    }
}
