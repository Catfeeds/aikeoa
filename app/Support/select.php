<?php

class select
{
    /**
     * 筛选专用方法
     */
    public static function head1()
    {
        $auth = Auth::user();

        // 筛选条件
        $select = [
            'aspect_id'     => 0,
            'region_id'     => 0,
            'circle_id'     => 0,
            'customer_id'     => 0,
            'customer_type' => 0,
        ];

        foreach ($select as $k => $v) {
            $select[$k] = Input::get($k, $v);
        }

        // 登录账号类型
        switch ($auth->role['name']) {

            // 业务员角色
            case 'salesman':

                if ($select['circle_id'] == 0) {
                    $select['customer_id'] = 0;
                }

                // 圈审阅人
                $owner = DB::table('customer_circle')
                ->where('layer', 3)
                ->where('owner_user_id', $auth['id'])
                ->get()->toArray();

                if ($owner) {
                    $res['owner_user'] = $owner;
                }
                
                // 圈查阅人
                $assist = DB::table('customer_circle')
                ->where('layer', 3)
                ->whereRaw(db_instr('owner_assist', $auth['id']))
                ->get()->toArray();

                if ($assist) {
                    $res['owner_assist'] = $assist;
                }
                $res['circles'] = array_merge($owner, $assist);
                $res['circles'] = array_by($res['circles']);

                if ($select['circle_id']) {
                    $res['whereIn']['customer.circle_id'] = [$select['circle_id']];
                } else {
                    $_circle = [];
                    foreach ($res['circles'] as $circle) {
                        $_circle[] = $circle['id'];
                    }
                    // 如果圈为空设置0防止选择全部圈
                    $res['whereIn']['customer.circle_id'] = count($_circle) ? $_circle : [0];
                }
                
            break;

            // 客户角色
            case 'customer':
                $select['customer_id'] = $auth['id'];
            break;
        
            // 默认其他角色
            default:
                if ($select['aspect_id'] == 0) {
                    $select['region_id'] = 0;
                    $select['circle_id'] = 0;
                    $select['customer_id'] = 0;
                }
                if ($select['region_id'] == 0) {
                    $select['circle_id'] = 0;
                    $select['customer_id'] = 0;
                }
                if ($select['circle_id'] == 0) {
                    $select['customer_id'] = 0;
                }

                $res['aspects'] = DB::table('customer_circle')
                ->where('layer', 1)
                ->orderBy('sort', 'asc')
                ->get()->toArray();

                // 获取全部方面
                $model = DB::table('customer_circle')
                ->where('layer', 2)
                ->orderBy('sort', 'asc');
                if ($select['aspect_id']) {
                    $model->where('parent_id', $select['aspect_id']);
                }
                $res['regions'] = $model->get()->toArray();

                // 获取全部区域
                $model = DB::table('customer_circle')
                ->where('layer', 3)
                ->orderBy('sort', 'asc');

                if ($select['region_id']) {
                    $model->where('parent_id', $select['region_id']);
                } else {
                    $region_id = [];
                    foreach ($res['regions'] as $regions) {
                        $region_id[] = $regions['id'];
                    }
                    $model->whereIn('parent_id', $region_id);
                }
                $res['circles'] = $model->get()->toArray();

                if ($select['circle_id']) {
                    $res['whereIn']['customer.circle_id'] = [$select['circle_id']];
                } else {
                    if ($res['circles']) {
                        $_circle = [];
                        foreach ($res['circles'] as $circle) {
                            $_circle[] = $circle['id'];
                        }
                        $res['whereIn']['customer.circle_id'] = $_circle;
                    }
                }

                if ($select['aspect_id'] == 0) {
                    $res['regions'] = [];
                }
                if ($select['region_id'] == 0) {
                    $res['circles'] = [];
                }
        }

        if ($select['circle_id']) {
            $customer = DB::table('user')
            ->leftJoin('customer', 'customer.user_id', '=', 'user.id')
            ->where('user.group_id', 2)
            ->where('customer.circle_id', $select['circle_id'])
            ->get(['user.id','user.status','user.nickname as company_name'])->toArray();
        }

        if ($select['customer_id']) {
            $res['whereIn'] = [];
            $res['whereIn']['customer.user_id'] = [$select['customer_id']];
        }

        if ($select['customer_type']) {
            $res['whereIn']['c.post'] = [$select['customer_type']];
        }

        $res['customer_type'] = Aike\Customer\CustomerType::orderBy('id', 'asc')
        ->get(['id','name'])
        ->keyBy('id')->toArray();

        $res['where']    = $where;
        $res['select']   = $select;
        $res['customer'] = $customer;
        return $res;
    }

    /**
     * 筛选专用方法
     */
    public static function head()
    {
        $auth = Auth::user();

        // 筛选条件
        $select = [
            'salesman_id' => 0,
            'owner_id'    => 0,
            'province_id' => 0,
            'city_id'     => 0,
            'customer_id'   => 0,
            'aspect_id'   => 0,
            'region_id'   => 0,
            'circle_id'   => 0,
        ];

        $aspects = DB::table('customer_circle')
        ->where('layer', 1)
        ->orderBy('sort', 'asc')
        ->get()->toArray();

        $regions = DB::table('customer_circle')
        ->where('layer', 2)
        ->orderBy('sort', 'asc')
        ->get()->toArray();
        
        $circles = DB::table('customer_circle')
        ->where('layer', 3)->orderBy('sort', 'asc')
        ->get()->toArray();

        foreach ($select as $k => $v) {
            $select[$k] = Input::get($k, $v);
        }

        $province = $city = $client = $_sql = [];

        // 登录的是业务员
        if ($auth->role->name == 'salesman') {
            $select['salesman_id'] = $auth['id'];
        }

        // 登录的是客户
        if ($auth->role->name == 'customer') {
            $select['customer_id'] = $auth['id'];
        }

        // 销售区域列表
        $salesman = DB::table('user')
        ->leftJoin('role', 'role.id', '=', 'user.role_id')
        ->where('role.name', 'salesman')
        ->where('user.status', 1)
        ->get(['user.id','user.nickname'])->toArray();
        $salesman = array_by($salesman);
        
        // 选择业务员
        if ($select['salesman_id'] > 0) {
            $where = $sql = 'c.salesman_id='.$select['salesman_id'];
            $_sql['user.salesman_id'] = $select['salesman_id'];

            // 按选择区域显示省份
            $province = DB::table('region')
            ->LeftJoin('user', 'region.id', '=', 'user.province_id')
            ->where('user.salesman_id', $select['salesman_id'])
            ->get(['region.name','region.id'])->toArray();
            $province = array_by($province);
            $customer = DB::table('user as c')
            ->where('c.group_id', 2)
            ->whereRaw($where)
            ->get(['c.id','c.nickname as company_name'])->toArray();
        }

        // 选择了省份
        if ($select['province_id'] > 0) {
            $where = $sql.' AND c.province_id='.$select['province_id'];

            $_sql['user.province_id'] = $select['province_id'];

            // 按选择省份和业务员显示城市
            $city = DB::table('region')
            ->LeftJoin('user', 'region.id', '=', 'user.city_id')
            ->where('user.salesman_id', $select['salesman_id'])
            ->where('region.parent_id', $select['province_id'])
            ->get(['region.name','region.id'])->toArray();
            $city = array_by($city);
        }

        // 选择了城市
        if ($select['city_id'] > 0) {
            $where = $sql.' AND c.city_id = '.$select['city_id'];

            $_sql['user.city_id'] = $select['city_id'];
            
            $customer = DB::table('user as c')
            ->where('c.group_id', 2)
            ->whereRaw($where)
            ->get(['c.id','c.nickname as company_name'])->toArray();
        }

        // 选择了客户
        if ($select['customer_id'] > 0) {
            $where = 'c.id = '.$select['customer_id'];
            $_sql['user.id'] = $select['customer_id'];
        }

        if (empty($where)) {
            $where = '1';
        }

        $res['where']    = $where;
        $res['sql']      = $_sql;
        $res['select']   = $select;
        $res['salesman'] = $salesman;
        $res['province'] = $province;
        $res['city']     = $city;
        $res['customer'] = $customer;
        return $res;
    }

    /**
     * 客户方法
     */
    public static function customer()
    {
        $user = Auth::user();
        $role = DB::table('role')->find($user->role_id);

        $res['role_type'] = 'all';
        $res['where']     = [];

        // 负责人
        if ($role['name'] == 'salesman') {
            $res['role_type'] = 'salesman';
            $res['where']['user.salesman_id'] = $user->id;

        // 客户
        } elseif ($role['name'] == 'customer') {
            $res['role_type'] = 'customer';
            $res['where']['user.id'] = $user->id;
        }
        return $res;
    }

    /**
     * 选择圈负责客户列表
     */
    public static function circleCustomer()
    {
        $user = Auth::user();
        $role = DB::table('role')->find($user->role_id);

        $res['columns'] = [];

        // 登录账号类型
        switch ($role['name']) {
            // 业务员角色
            case 'salesman':
                // 圈审阅人
                $owner = DB::table('customer_circle')->where('owner_user_id', $user->id)->pluck('id')->toArray();
                if ($owner) {
                    $res['owner_user'] = $owner;
                }
                
                // 圈查阅人
                $assist = DB::table('customer_circle')->whereRaw(db_instr('owner_assist', $user->id))->pluck('id')->toArray();
                if ($assist) {
                    $res['owner_assist'] = $assist;
                }
                $circle = array_merge($owner, $assist);

                $res['whereIn']['customer.circle_id'] = $circle;
                $res['circleIn'] = $circle;

                $res['columns'] = [
                    ['circle','customer.circle_id','销售区域'],
                    ['region','user.province_id','客户地区'],
                    ['post','user.post','客户类型'],
                ];
                $res['circle'] = DB::table('customer_circle')->where('layer', 3)->orderBy('sort', 'asc')->get()->toArray();
                break;

            // 客户角色
            case 'customer':
                // 客户订单模块才显示自己的下属客户
                if (Request::module().'/'.Request::controller() == 'order/order') {
                    // 如果是服务商读取自己的经销商
                    $customer_id = $user->customer->id;
                    $users = DB::table('customer')->where('service_id', $customer_id)->pluck('user_id')->toArray();
                }
                $users[] = $user->id;
                $res['whereIn']['customer.user_id'] = $users;
                $res['circleIn'] = [];
                break;

            // 默认其他角色
            default:
                $res['columns'] = [
                    ['circle','customer.circle_id','销售区域'],
                    ['region','user.province_id','客户地区'],
                    ['post','user.post','客户类型'],
                ];
                $res['circle'] = DB::table('customer_circle')->where('layer', 3)->orderBy('sort', 'asc')->get()->toArray();
        }
        return $res;
    }
}
