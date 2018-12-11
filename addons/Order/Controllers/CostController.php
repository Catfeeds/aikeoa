<?php namespace Aike\Order\Controllers;

use DB;
use Input;

use select;

use Aike\Order\OrderType;

use Aike\Index\Controllers\DefaultController;

class CostController extends DefaultController
{
    // 订单流控
    public function indexAction()
    {
        // 审核模板
        $order = config('order');

        //筛选专用函数
        $selects = select::head();
        $where = $selects['where'];

        $page_id = Input::get('page', 1);
        $type = Input::get('type', 0);
        $number = Input::get('number', '');
        $sdate = Input::get('sdate', date('Y-m-01'));
        $edate = Input::get('edate', date('Y-m-d'));

        $selects['select']['type'] = $type;
        $selects['select']['sdate'] = $sdate;
        $selects['select']['edate'] = $edate;

        //模型实例
        $model = DB::table('order as o')
        ->leftJoin('order_data as oh', 'o.id', '=', 'oh.order_id')
        ->leftJoin('product as p', 'oh.product_id', '=', 'p.id')
        ->leftJoin('user as c', 'o.customer_id', '=', 'c.id')
        ->leftJoin('order_type as d', 'd.id', '=', 'oh.type')
        ->where('oh.deleted_by', 0)
        ->where('o.status', 1)
        ->where('d.type', 0)
        ->where('o.delivery_time', '>', 0)
        ->whereRaw($where)
        ->groupBy('o.id')
        ->groupBy('oh.type')
        ->groupBy('o.customer_id')
        ->orderBy('o.delivery_time', 'DESC');

        if ($sdate) {
            $model->whereRaw('FROM_UNIXTIME(o.delivery_time,"%Y-%m-%d") >= ?', [$sdate]);
        }

        if ($edate) {
            $model->whereRaw('FROM_UNIXTIME(o.delivery_time,"%Y-%m-%d") <= ?', [$edate]);
        }

        if ($type > 0) {
            $q_type = DB::table('order_type')->where('id', $type)->first(['lft', 'rgt']);
            $model->whereRaw('d.lft BETWEEN '.$q_type['lft'].' AND '.$q_type['rgt']);
        }
        $res = $model->get(['o.pay_time','o.delivery_time','o.arrival_time','o.number','o.plan_time','o.flow_step_id','oh.product_id','o.id','oh.type','oh.customer_id', DB::raw('SUM(oh.price*oh.fact_amount) as amount'),'c.nickname as company_name']);

        $order_type = OrderType::orderBy('lft', 'asc')->get()->toNested('title');
        $query = url().'?'.http_build_query($selects['select']);

        // 视图设置
        return $this->display(array(
            'res'       => $res,
            'selects'   => $selects,
            'order_type'=> $order_type,
            'query'     => $query,
        ));
    }
}
