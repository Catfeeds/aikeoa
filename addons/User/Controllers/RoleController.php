<?php namespace Aike\User\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;
use App\License;

use Module;

use Aike\User\User;
use Aike\User\Role;
use Aike\User\UserAsset;

use Aike\Index\Controllers\DefaultController;

class RoleController extends DefaultController
{
    public $permission = ['index', 'dialog'];

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
                    'dataIndex' => 'name',
                    'width'     => 200,
                    'text'      => '标签',
                ],[
                    'dataIndex' => 'description',
                    'width'     => 200,
                    'text'      => '描述',
                ],[
                    'dataIndex' => 'count',
                    'text'      => '用户数',
                    'align'     => 'center',
                ],[
                    'dataIndex' => 'id',
                    'text'      => '编号',
                    'align'     => 'center',
                    'width'     => 70,
                ]
            ]
        ];

        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');

            foreach ($sorts as $id => $sort) {
                $role = Role::find($id);
                $role->sort = $sort;
                $role->save();
            }
            Role::treeRebuild();

            return $this->back('恭喜你，操作成功。');
        }
        $modules = Module::allWithDetails();

        $modules = array_sort($modules, function ($value) {
            return $value['order'];
        });

        $search = search_form([
            'referer' => 1,
        ], []);

        $count = User::groupBy('role_id')->get(array(DB::raw('count(id) AS count'), 'role_id'))->pluck('count', 'role_id');
        
        $rows = Role::orderBy('lft', 'asc')->get()->toArray();
        foreach ($rows as $key => $row) {
            $rows[$key]['count'] = $count[$row['id']];
        }
        $rows = array_nest($rows);

        return $this->display([
            'rows'  => $rows,
            'count' => $count,
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
            //return $this->back('恭喜您，操作成功。');
            return $this->success('config', $query, '恭喜您，操作成功。', 0);
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

    // 角色编辑
    public function addAction()
    {
        $gets = Input::get();

        $count   = Role::count('id');
        $license = License::check('role', $count);

        if ($gets['id'] == 0 && $license) {
            return $this->error('无法新建角色授权许可不足。');
        }
        
        $row = Role::findOrNew($gets['id']);

        if (Request::method() == 'POST') {
            $rules = array(
                'name' => 'required',
                'title' => 'required'
            );
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            $row->fill($gets)->save();

            // 重构树形结构
            Role::treeRebuild();

            return $this->success('index', '恭喜你，操作成功。');
        }
        
        $roles = Role::orderBy('lft', 'asc')->get()->toNested();
        return $this->display([
            'row'   => $row,
            'roles' => $roles,
        ]);
    }

    public function dialogAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $model = DB::table('role')
            ->orderBy('lft', 'asc');

            $rows = $model->get(['id', 'parent_id', 'title']);

            $rows = array_nest($rows, 'title');

            foreach ($rows as $row) {
                $json[] = [
                    'id'    => $row['id'],
                    'sid'   => 'r'.$row['id'],
                    'title' => $row['title'],
                    'text'  => $row['layer_space'].$row['title'],
                ];
            }
            return response()->json($json);
        }

        return $this->render([
            'rows' => $rows,
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
                return $this->error('最少选择一行记录。');
            }

            $has = Role::whereIn('parent_id', $id)->count();
            if ($has) {
                return $this->error('存在子节点不允许删除。');
            }

            // 删除角色
            Role::whereIn('id', $id)->delete();

            // 重构树形结构
            Role::treeRebuild();

            return $this->back('删除成功。');
        }
    }
}
