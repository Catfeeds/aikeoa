<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\Customer\Customer;
use Aike\Customer\Account;
use Aike\Customer\CustomerType;
use Aike\Index\Region;
use select;

use Aike\Index\Controllers\DefaultController;

class AccountController extends DefaultController
{
    public $permission = [];

    public function indexAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','user.nickname','客户名称'],
            ['text','user.username','客户代码'],
            ['date','customer_account.date','单据日期'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        $search = search_form([
            'status'  => 1,
            'referer' => 1,
        ], $columns);

        $query  = $search['query'];

        $model = Account::with('customer.user')
        ->leftJoin('customer', 'customer.id', '=', 'customer_account.customer_id')
        ->leftJoin('user', 'user.id', '=', 'customer.user_id');

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

        $rows = $model->select(['customer_account.*'])
        ->paginate($query['limit'])->appends($query);

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
            'tabs'   => $tabs,
        ]);
    }

    // 单客户查询对账数据
    public function queryAction()
    {
        $search = search_form([
            'customer' => '',
            'start_at' => '',
            'end_at'   => '',
        ], []);

        $query = $search['query'];

        if ($query['customer']) {
            $customer = DB::table('customer')
            ->leftJoin('user', 'customer.user_id', '=', 'user.id')
            ->where('customer.id', $query['customer'])
            ->first();

            $ch = curl_init(env('YONYOU_URL').'/yonyou.php?do=ar&start_at='.$query['start_at'].'&end_at='.$query['end_at'].'&customer_code='.$customer['username']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($res, true);

            $lists = [];
            foreach ($data as $row) {
                $cDwCode = $row['cDwCode'];
                $lists[$cDwCode][] = $row;
            }

            $res = [];
            $total_start = $total_end = 0;
            foreach ($lists as $code => $rows) {
                $ye = 0;
                foreach ($rows as $i => $row) {
                    
                    $ye = $ye + $row['ye'];
                    $ye = $ye + $row['jf'] - $row['df'];

                    if($row['iYear'] && $row['iMonth'] && $row['iDay']) {
                        $date = date('Y-m-d', strtotime($row['iYear'].'-'.$row['iMonth'].'-'.$row['iDay']));
                        $jf = number_format($row['jf'], 2);
                        $df = number_format($row['df'], 2);
                        $res[] = [
                            'code'     => $code,
                            'date'     => $date,
                            'jmoney'   => $jf,
                            'dmoney'   => $df,
                            'balance'  => number_format($ye, 2),
                            'ccusname' => $row['ccusname'],
                            'digest'   => $row['cDigest'],
                            'ddh'      => $row['ddh'],
                            'zp'       => number_format($row['zp'], 2),
                        ];
                    } else {
                        $res[] = [
                            'balance'  => number_format($row['ye'], 2),
                            'ccusname' => $row['ccusname'],
                            'code'     => $code,
                            'digest'   => $row['cDigest'],
                        ];
                        $total_start += $row['ye'];
                    }
                }
                $res[] = [
                    'balance'  => number_format($ye, 2),
                    'ccusname' => $rows[0]['ccusname'],
                    'code'     => $code,
                    'digest'   => '期末余额'
                ];
                $total_end += $ye;
            }

            array_unshift($res, [
                'balance'  => number_format($total_start, 2),
                'digest'   => '总期初余额',
                'ccusname' => '总期初合计',
            ]);
            $res[] = [
                'balance'  => number_format($total_end, 2),
                'digest'   => '总期末余额',
                'ccusname' => '总期末合计',
            ];

            if (Request::method() == 'POST') {
                return response()->json($res);
            }
        }
        return $this->display();
    }

    // 审核对账单
    public function auditAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            DB::table('customer_account')->whereIn('id', $id)->update(['status' => 1]);
            return $this->success('index', '恭喜你，审核成功。');
        }
    }

    // 查看对账单
    public function showAction()
    {
        $id = Input::get('id');
        $account = Account::where('id', $id)->first();
        $rows    = DB::table('customer_account_data')
        ->where('account_id', $id)
        ->orderBy('id', 'asc')
        ->get();

        return $this->render([
            'rows'    => $rows,
            'account' => $account,
        ]);
    }

    // 生成对账单
    public function createAction()
    {
        set_time_limit(0);

        if (Request::method() == 'POST') {
            $gets = Input::get();
            
            $rules = [
                'start_at'  => 'required|date',
                'end_at'    => 'required|date',
            ];

            $v = Validator::make($gets, $rules, [], ['start_at' => '开始日期', 'end_at' => '结束日期']);
            if ($v->fails()) {
                return $this->json($v->errors()->all());
            }

            /** 数据同步 **/
            $ch = curl_init(env('YONYOU_URL').'/yonyou.php?do=ar&start_at='.$gets['start_at'].'&end_at='.$gets['end_at']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($res, true);

            // 获取客户数据
            $customers = DB::table('user')
            ->leftJoin('customer', 'customer.user_id', '=', 'user.id')
            ->where('group_id', 2)
            ->pluck('customer.id', 'user.username');

            $rows = [];
            foreach ($res['data'] as $row) {
                if ($row['code']) {
                    $rows[$row['code']][] = $row;
                    /*
                    if(empty($customers[$row['code']])) {
                        return $this->json('订单系统不存在此客户代码: ['.$row['code'].']');
                    }
                    */
                }
            }

            // 总余额
            $yes = $res['ye'];

            foreach ($rows as $code => $row) {
                $account_id = DB::table('customer_account')->insertGetId([
                    'sn'          => date('Ymd').$code,
                    'date'        => date('Ymd'),
                    'code'        => $code,
                    'customer_id' => (int)$customers[$code],
                    'start_at'    => $gets['start_at'],
                    'end_at'      => $gets['end_at'],
                ]);

                // 单客户余额
                $ye = $yes[$row['zcode']] > 0 ? $yes[$row['zcode']] : 0.00;

                // 写入余额数据
                DB::table('customer_account_data')->insert([
                    'code'       => $code,
                    'account_id' => $account_id,
                    'balance'    => $ye,
                    'digest'     => '期初余额小计',
                ]);
                
                foreach ($row as $i => $cell) {
                    $ye = $ye + $cell['jf'] - $cell['df'];

                    $data = [
                        'account_id' => $account_id,
                        'sn'         => date('Ymd').$i,
                        'date'       => $cell['date'],
                        'ycode'      => $cell['ycode'],
                        'zcode'      => $cell['zcode'],
                        'code'       => $cell['code'],
                        'jmoney'     => $cell['jf'],
                        'dmoney'     => $cell['df'],
                        'balance'    => $ye,
                        'digest'     => $cell['digest'],
                    ];

                    DB::table('customer_account_data')->insert($data);
                }

                DB::table('customer_account_data')->insert([
                    'code'       => $code,
                    'account_id' => $account_id,
                    'balance'    => $ye,
                    'digest'     => '期木余额小计',
                ]);

                // 更新余额
                DB::table('customer_account')->where('id', $account_id)->update([
                    'balance' => $ye,
                ]);
            }
            return $this->json('reload', true);
        }
        return $this->render();
    }

    // 删除对账单
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            DB::table('customer_account')->whereIn('id', $id)->delete();
            DB::table('customer_account_data')->whereIn('account_id', $id)->delete();
            return $this->success('index', '恭喜你，操作成功。');
        }
    }
}
