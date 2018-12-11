<?php namespace Aike\Order\Controllers;

use DB;
use Input;
use Request;
use Auth;
use Validator;
use Paginator;

use select;

use Aike\Order\Order;
use Aike\Index\Controllers\DefaultController;

class TransportController extends DefaultController
{
    /**
     * 物流列表
     */
    public function indexAction()
    {
        // 筛选专用函数
        $selects = select::head();
        $where   = $selects['where'];
        $number  = Input::get('number', '');
        $sdate   = Input::get('sdate', '');
        $edate   = Input::get('edate', date('Y-m-d'));
        $page    = Input::get('page', 1);

        $selects['select'] += array(
            'number' => $number,
            'sdate'  => $sdate,
            'edate'  => $edate,
        );

        $model = DB::table('order as o');

        if ($number) {
            $model->whereRaw('o.number LIKE ?', ['%'.$number.'%']);
        }

        if ($sdate) {
            $model->whereRaw('FROM_UNIXTIME(o.add_time,"%Y-%m-%d") >= ?', [$sdate]);
        }

        if ($edate) {
            $model->whereRaw('FROM_UNIXTIME(o.add_time,"%Y-%m-%d") <= ?', [$edate]);
        }

        $model->leftJoin('order_transport as t', 't.order_id', '=', 'o.id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->where('o.delivery_time', '>', 0)
        ->whereRaw($where)
        ->selectRaw('o.number,SUM(oi.fact_amount * oi.price) as fact_money, SUM(oi.amount * oi.price) as money, SUM(oi.fact_amount) as fact_amount, SUM(oi.amount) as amount, c.city_id, c.nickname as company_name, t.*, o.add_time, o.pay_time, o.delivery_time, o.arrival_time');

        // 在合适的时候统计
        $total = $model->count('t.order_id');

        $res = $model->leftJoin('order_data as oi', 'oi.order_id', '=', 'o.id')
        ->where('oi.deleted_by', 0)
        ->groupBy('o.id')
        ->orderBy('o.id', 'DESC')
        ->forPage($page, 15)
        ->get();
      
        $rows = Paginator::make($res, $total, 15)->appends($selects['select']);
        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'rows'   => $rows,
            'query'  => $query,
            'selects'=> $selects,
        ));
    }

    // 批号查询
    public function batchAction()
    {
        $search = search_form([
            'product_id' => '',
            'batch'      => '',
        ], []);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $rows = DB::table('order_data')
            ->leftJoin('order', 'order.id', '=', 'order_data.order_id')
            ->leftJoin('product', 'product.id', '=', 'order_data.product_id')
            ->where('order_data.product_id', $query['product_id'])
            ->where('order_data.batch_number', 'like', '%'.$query['batch'])
            ->get(['order.invoice_company','order.number','order_data.batch_number','product.name','order_data.fact_amount']);

            return response()->json($rows);
        }
        return $this->display();
    }

    // 预发列表
    public function advanceAction()
    {
        $selects = select::head();
        $where = $selects['where'];

        $sdate = Input::get('sdate', '');
        $edate = Input::get('edate', date('Y-m-d', strtotime("+3 Day")));
        $depot = Input::get('depot', '');
        $selects['select']['sdate'] = $sdate;
        $selects['select']['edate'] = $edate;
        $selects['select']['depot'] = $depot;

        // 查看预计发货信息
        $model = Order::leftJoin('order_transport', 'order.id', '=', 'order_transport.order_id')
        ->leftJoin('user as c', 'order.customer_id', '=', 'c.id')
        ->whereRaw($where)
        ->where('order_transport.advance_depot', '>', 0)
        //->where('a.delivery_time','<=', 0)
        //->groupBy('a.id')
        ->orderBy('order_transport.advance_time', 'desc')
        ->selectRaw('`order`.*, order_transport.*, c.nickname as company_name');
        
        if (!empty($sdate)) {
            $model->whereRaw('FROM_UNIXTIME(order_transport.advance_time,"%Y-%m-%d") >= ?', [$sdate]);
        }

        if (!empty($edate)) {
            $model->whereRaw('FROM_UNIXTIME(order_transport.advance_time,"%Y-%m-%d") <= ?', [$edate]);
        }

        if ($depot) {
            $model->where('order_transport.advance_depot', $depot);
        }

        // 查询订单明细合计
        $model->with(['datas' => function ($q) {
            $q->where('order_data.deleted_by', 0)->select(['order_id','order_data.amount']);
        }]);

        $res = $model->paginate()->appends($selects['select']);


        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'title'        => $templates[$selects['select']['tpl']],
            'customer'       => $customer,
            'transport'    => $transport,
            'product_type' => $product_type,
            'res'          => $res,
            'orderinfo'    => $orderinfo,
            'selects'      => $selects,
            'query'        => $query,
            'templates'    => $templates,
            'categorys'    => $categorys,
        ));
    }
}
