<?php namespace Aike\Order\Controllers;

use DB;
use Request;
use Auth;

use select;

use Aike\Index\Controllers\DefaultController;

class WidgetController extends DefaultController
{
    public $permission = ['index', 'goods'];

    public function indexAction()
    {
        if (Request::isJson()) {
            $selects = select::head();

            $where = $selects['where'];

            // 去年
            $lastYear = date('Y') - 1;
            // 今年
            $nowYear = date('Y');

            // 计算三天未打款的订单
            $res = DB::table('order as o')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->whereRaw('(UNIX_TIMESTAMP() - o.add_time) > ?', [259200])
            ->where('o.add_time', '>', 0)
            ->where('o.pay_time', 0)
            ->whereRaw($where)
            ->selectRaw('COUNT(o.id) AS count')
            ->first();
            
            $rows[] = ['title' => '目前订单3日内没有打款的有 <span class="red">'.$res['count'].'</span> 张订单'];

            //计算款到三天未发货的订单
            $res = DB::table('order as o')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->whereRaw('(UNIX_TIMESTAMP() - o.add_time) > ?', [259200])
            ->where('o.add_time', '>', 0)
            ->where('o.delivery_time', 0)
            ->whereRaw($where)
            ->selectRaw('COUNT(DISTINCT o.id) AS count')
            ->first();
            
            $rows[] = ['title' => '目前款到3日内没有发货的有 <span class="red">'.$res['count'].'</span> 张订单'];

            //本月收到 []个客户[]]张订单，[]件货。
            $res = DB::table('order as o')
            ->leftJoin('order_data as oi', 'oi.order_id', '=', 'o.id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->where('oi.deleted_by', 0)
            ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y-%m")=?', [date('Y-m')])
            ->whereRaw($where)
            ->selectRaw('COUNT(DISTINCT o.id) AS count, COUNT(DISTINCT o.customer_id) AS customer_count, SUM(oi.amount) AS amount')
            ->first();
            
            $rows[] = ['title' => '本月收到 <span class="red">'.$res['customer_count'].'</span> 个客户 <span class="red">'.$res['count'].'</span> 张订单，<span class="red">'.(int)$res['amount'].'</span> 件货'];

            // 本月收到的订单中已发出[]张订单，[]件货
            $res = DB::table('order as o')
            ->leftJoin('order_data as oi', 'oi.order_id', '=', 'o.id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->where('oi.deleted_by', 0)
            ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y-%m")=?', [date('Y-m')])
            ->whereRaw($where)
            ->where('o.delivery_time', '>', 0)
            ->selectRaw('COUNT(DISTINCT o.id) AS count, SUM(oi.amount) AS amount')
            ->first();
            
            $rows[] = ['title' => '本月收到的订单中已发出 <span class="red">'.$res['count'].'</span> 张订单，<span class="red">'.(int)$res['amount'].'</span> 件货'];

            // 上月订单本月发出[]张，[]件货。
            $res = DB::table('order as o')
            ->leftJoin('order_data as oi', 'oi.order_id', '=', 'o.id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->where('oi.deleted_by', 0)
            ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y-%m")=?', [date("Y-m", strtotime("-1 month"))])
            ->whereRaw('FROM_UNIXTIME(o.delivery_time,"%Y-%m")=?', [date("Y-m")])
            ->where('o.delivery_time', '>', 0)
            ->whereRaw($where)
            ->selectRaw('COUNT(DISTINCT o.id) AS count, SUM(oi.amount) AS amount')
            ->first();
            
            $rows[] = ['title' => '上月订单本月发出 <span class="red">'.$res['count'].'</span> 张订单，<span class="red">'.(int)$res['amount'].' </span>件货'];

            // 订单审核状态。
            $res = DB::table('order as o')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y") BETWEEN '.$lastYear.' AND '.$nowYear)
            ->whereRaw($where)
            ->where('o.arrival_time', 0)
            ->groupBy('o.flow_step_id')
            ->selectRaw('COUNT(DISTINCT o.id) AS count, o.flow_step_id')
            ->get()->toArray();

            if (is_array($res)) {
                $audit_count = array();
                foreach ($res as $v) {
                    $audit_count[$v['flow_step_id']] = $v['count'];
                }
                $order_config = config('order');
                $audits = array();
                foreach ($order_config['audit'] as $k => $v) {
                    if ($v['role_text']) {
                        $audits[$v['role_text']] += $audit_count[$k];
                    }
                }
                $audit_text = array();
                foreach ($audits as $k => $v) {
                    if ($v) {
                        $audit_text[] = $k.' <span class="red">'.$v.'</span> 张';
                    }
                }
                $rows[] = ['title' => '订单待审核: '.join(', ', $audit_text)];
            }
            
            // 目前在途[]件。
            $res = DB::table('order as o')
            ->leftJoin('order_data as oi', 'oi.order_id', '=', 'o.id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->where('oi.deleted_by', 0)
            ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y") BETWEEN '.$lastYear.' AND '.$nowYear)
            ->whereRaw($where)
            ->where('o.delivery_time', '>', 0)
            ->where('o.arrival_time', 0)
            ->selectRaw('SUM(oi.amount) AS amount')
            ->first();
            
            $rows[] = ['title' => '目前在途订单 <span class="red">'.(int)$res['amount'].'</span> 件'];
            
            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }

    /**
     * 明日预计到货列表
     */
    public function goodsAction()
    {
        if (Request::isJson()) {
            $selects = select::head();

            $where = $selects['where'];

            // 昨天
            $lastDay = date("Y-m-d", strtotime("+1 day"));
            $rows = DB::table('order as o')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->leftJoin('order_transport as ot', 'ot.order_id', '=', 'o.id')
            ->whereRaw('FROM_UNIXTIME(ot.advance_arrival_time,"%Y-%m-%d")=?', [$lastDay])
            ->where('o.delivery_time', '>', 0)
            ->whereRaw($where)
            ->selectRaw('o.id,o.number,c.nickname,o.delivery_time')
            ->get();

            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }
}
