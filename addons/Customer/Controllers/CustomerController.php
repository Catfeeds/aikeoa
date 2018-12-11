<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\Customer\Customer;
use Aike\Customer\CustomerType;
use Aike\Index\Region;
use select;

use Aike\Index\Controllers\DefaultController;

class CustomerController extends DefaultController
{
    public $permission = ['dialog','owner'];

    public function indexAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','user.nickname','客户名称'],
            ['text','user.username','客户代码'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        $search = search_form([
            'status'   => 1,
            'referer'  => 1,
        ], $columns);

        $query  = $search['query'];

        $model = Customer::LeftJoin('user', 'user.id', '=', 'customer.user_id')
        ->where('user.group_id', 2)
        ->where('user.status', $query['status']);

        if ($query['order'] && $query['srot']) {
            $model->orderBy($query['srot'], $query['order']);
        }

        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->with('user')
        ->select(['customer.*'])
        ->paginate($search['limit'])->appends($query);

        $types = CustomerType::orderBy('id', 'asc')
        ->get(['id','name'])
        ->keyBy('id')->toArray();

        $region = Region::pluck('name', 'id');

        $tabs = [
            'name'  => 'status',
            'items' => [
                ['id' => 1, 'name' => '启用'],
                ['id' => 0, 'name' => '停用'],
            ]
        ];

        return $this->display(array(
            'rows'   => $rows,
            'types'  => $types,
            'search' => $search,
            'region' => $region,
            'tabs'   => $tabs,
        ));
    }

    // 负责人
    public function ownerAction()
    {
        $rows = DB::table('user')
        ->select(['user.id','user.nickname as name','user.nickname as text'])
        ->LeftJoin('role', 'role.id', '=', 'user.role_id')
        ->where('role.name', 'salesman')
        ->where('user.status', 1)
        ->get();
        return response()->json($rows);
    }

    // 客户资料查看
    public function viewAction()
    {
        $id = Input::get('id');
        
        $customer = DB::table('customer')->where('id', $id)->first();
        $row = DB::table('user')->where('id', $customer['user_id'])->first();
        $customer_bank = DB::table('customer_bank')->where('customer_id', $customer['user_id'])->get();
        $types = DB::table('customer_type')->get();

        return $this->display([
            'row'         => $row,
            'types'       => $types,
            'customer'      => $customer,
            'contact'     => $contact,
            'customer_bank' => $customer_bank,
        ]);
    }

    // 账户修改
    public function addAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $_user = $gets['user'];
            $_customer = $gets['customer'];

            $rules = [
                'user.nickname'    => 'required',
                'user.username'    => 'required|unique:user,username,'.$_user['id'],
                'user.mobile'      => 'required',
                // 'user.salesman_id' => 'required',
            ];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            // 客户角色编号
            $_user['role_id'] = 2;

            // 客户用户组
            $_user['group_id'] = 2;

            // 账户状态
            $_user['status']    = (int)$_user['status'];
            $_user['auth_totp'] = (int)$_user['auth_totp'];

            $_customer['order_approve'] = (int)$_customer['order_approve'];
            $_customer['sp_materiel']   = (int)$_customer['sp_materiel'];
            $_customer['freight_type']  = (int)$_customer['freight_type'];
            
            $user   = User::findOrNew($_user['id']);
            $customer = Customer::findOrNew($_customer['id']);

            // 账户
            $user->username = $_user['username'];

            // 账户密码
            if ($_user['password']) {
                $user->password = bcrypt($_user['password']);
            }

            $user->fill($_user)->save();

            if ($_customer['id']) {
                $customer->id = $_customer['id'];
            }

            $customer->id       = $user->id;
            $customer->user_id  = $user->id;
            $customer->owner_id = $user->salesman_id;

            $customer->fill($_customer)->save();

            return $this->success('index', '恭喜您，客户信息提交成功。');
        }

        $id = (int)Input::get('id');

        $customer = DB::table('customer')->where('id', $id)->first();
        $row    = DB::table('user')->where('id', $customer['user_id'])->first();

        $types  = DB::table('customer_type')->orderBy('id', 'asc')->get();
        
        return $this->display([
            'row'    => $row,
            'types'  => $types,
            'prices' => $prices,
            'query'  => $query,
            'customer' => $customer,
        ]);
    }

    // 客户对话框
    public function dialogAction()
    {
        $search = search_form([
            'circle_id' => '',
            'advanced'  => '',
            'province'  => '',
            'city'      => '',
            'county'    => '',
            'sort'      => '',
            'order'     => '',
            'limit'     => 25,
        ], [
            ['text','user.nickname','姓名'],
            ['text','user.username','账号'],
            ['text','user.id','编号'],
            ['region','user.province_id','地址'],
        ]);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = User::leftJoin('customer', 'customer.user_id', '=', 'user.id')
            ->where('user.group_id', 2);

            // 客户圈权限
            $circle = select::circleCustomer();
            if ($circle['whereIn']) {
                foreach ($circle['whereIn'] as $key => $where) {
                    $model->whereIn($key, $where);
                }
            }

            if ($query['circle_id']) {
                $model->where('customer.circle_id', $query['circle_id']);
            }

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy($query['sort'], $query['order']);
            }

            // 搜索条件
            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }
            /*
            $json['total'] = $model->count();
            $rows = $model->skip($query['offset'])->take($query['limit'])
            ->get(['user.id', 'user.role_id', 'user.status', 'user.username', 'user.nickname as text', 'user.email', 'user.mobile']);
            */
            $rows = $model->paginate($query['limit'], ['user.id', 'user.role_id', 'user.status', 'user.username', 'user.nickname as text', 'user.email', 'user.mobile']);
            return response()->json($rows);
        }

        $gets = Input::get();
        return $this->render([
            'search' => $search,
            'gets'   => $gets,
        ]);
    }

    // 删除客户档案
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $id = array_filter((array)$id);

            if (empty($id)) {
                return $this->error('请先选择客户。');
            }

            DB::table('user')->whereIn('id', $id)->delete();
            DB::table('customer')->whereIn('id', $id)->delete();
            DB::table('customer_bank')->whereIn('customer_id', $id)->delete();
            DB::table('customer_contract')->whereIn('customer_id', $id)->delete();

            return $this->success('index', '恭喜你，操作成功。');
        }
    }

    // 导入客户信息
    public function exportAction()
    {
        $columns = [[
            'name'  => 'username',
            'index' => 'user.username',
            'label' => '客户代码',
        ],[
            'name'  => 'nickname',
            'index' => 'user.nickname',
            'label' => '客户名称',
        ],[
            'name'  => 'type_name',
            'index' => 'customer_type.name as type_name',
            'label' => '客户类型',
        ],[
            'name'  => 'salesman_name',
            'index' => 'salesman.nickname as salesman_name',
            'label' => '负责人',
        ],[
            'name'  => 'circle_name',
            'index' => 'customer_circle.name as circle_name',
            'label' => '销售圈',
        ],[
            'name'  => 'fullname',
            'index' => 'user.fullname',
            'label' => '经营负责人',
        ],[
            'name'  => 'birthday',
            'index' => 'user.birthday',
            'label' => '经营负责人生日',
        ],[
            'name'  => 'mobile',
            'index' => 'user.mobile',
            'label' => '经营负责人手机',
        ],[
            'name'  => 'tel',
            'index' => 'user.tel',
            'label' => '公司电话',
        ],[
            'name'  => 'fax',
            'index' => 'user.fax',
            'label' => '公司传真',
        ],[
            'name'  => 'email',
            'index' => 'user.email',
            'label' => '邮箱地址',
        ],[
            'name'  => 'region',
            'index' => ['user.province_id', 'user.city_id', 'user.county_id'],
            'label' => '公司省市区',
        ],[
            'name'  => 'address',
            'index' => 'user.address',
            'label' => '公司地址',
        ],[
            'name'  => 'warehouse_contact',
            'index' => 'customer.warehouse_contact',
            'label' => '收货人',
        ],[
            'name'  => 'warehouse_mobile',
            'index' => 'customer.warehouse_mobile',
            'label' => '收货人手机',
        ],[
            'name'  => 'warehouse_tel',
            'index' => 'customer.warehouse_tel',
            'label' => '收货电话',
        ],[
            'name'  => 'warehouse_address',
            'index' => 'customer.warehouse_address',
            'label' => '收货地址',
        ]];

        $_columns = [];
        foreach ($columns as $column) {
            if (is_array($column['index'])) {
                array_merge($_columns, $column['index']);
            } else {
                $_columns[] = $column['index'];
            }
        }

        $model = Customer::LeftJoin('user', 'user.id', '=', 'customer.user_id')
        ->LeftJoin('customer_type', 'user.post', '=', 'customer_type.id')
        ->LeftJoin('user as salesman', 'salesman.id', '=', 'user.salesman_id')
        ->LeftJoin('customer_circle', 'customer_circle.id', '=', 'customer.circle_id')
        ->where('user.group_id', 2);

        $status = Input::get('status', 1);
        $model->where('user.status', $status);
        
        $rows = $model->get($_columns);

        // 公司地址
        $regions = DB::table('region')->pluck('name', 'id');
        foreach ($rows as $row) {
            $row['province_id'] = $regions[$row['province_id']].' '.$regions[$row['city_id']].' '.$regions[$row['county_id']];
        }

        writeExcel($columns, $rows, date('y-m-d').'-客户档案');
    }

    // 导出客户信息
    public function importAction()
    {
        return $this->display([]);
    }

    // 客户开票列表
    public function invoice_typeAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();
        $columns = [
            ['text','user.nickname','客户名称'],
            ['text','user.username','客户代码'],
            ['status','user.status','客户状态'],
            ['customer.invoice','customer.invoice_type','开票类型'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        /*
        if($filter['role_type'] == 'salesman') {
            $columns[] = ['region','user.province_id','客户地址'];
            $columns[] = ['status','user.status','客户状态'];
            $columns[] = ['post','user.post','客户类型'];
            $columns[] = ['customer.invoice','customer.invoice_type','开票类型'];
        }

        if($filter['role_type'] == 'all') {
            $columns[] = ['owner','user.salesman_id','负责人'];
            $columns[] = ['region','user.province_id','客户地址'];
            $columns[] = ['status','user.status','客户状态'];
            $columns[] = ['post','user.post','客户类型'];
            $columns[] = ['customer.invoice','customer.invoice_type','开票类型'];
        }
        */

        $search = search_form([
            'referer' => 1,
        ], $columns);

        $query = $search['query'];

        $model = Customer::LeftJoin('user', 'user.id', '=', 'customer.user_id')
        ->where('user.group_id', 2);

        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->with('user')
        ->select(['customer.*'])
        ->paginate()->appends($query);

        $types = CustomerType::orderBy('id', 'asc')->get(['id','title as name'])->keyBy('id');

        return $this->display([
            'rows'   => $rows,
            'types'  => $types,
            'search' => $search,
        ]);
    }

    // 编辑开票类型
    public function invoice_editAction()
    {
        if (Request::method() == 'POST') {
            $post = Input::get();
            DB::table('customer')->where('id', $post['id'])->update($post);
            return $this->success('invoice_type', '保存成功。');
        }

        $id = (int)Input::get('id');

        $row = Customer::find($id);

        $selects['select']['id'] = $id;
        $query = url().'?'.http_build_query($selects['select']);

        $types = DB::table('customer_type')->get();

        return $this->display([
            'row'     => $row,
            'types'   => $types,
            'prices'  => $prices,
            'selects' => $selects,
            'query'   => $query,
        ]);
    }
}
