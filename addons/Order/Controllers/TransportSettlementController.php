<?php namespace Aike\Order\Controllers;

use Session;
use Request;
use DB;
use Input;
use Validator;
use Auth;

use Aike\Order\Order;
use Aike\Order\OrderTransport;
use Aike\Customer\Customer;
use Aike\Supplier\ProductCategory;

use Aike\User\User;
use Aike\Index\Controllers\DefaultController;

class TransportSettlementController extends DefaultController
{
    public $permission = [];
    
    public function indexAction()
    {
        $columns = [
            ['text','order_transport_settlement.sn','单号'],
            ['logistics','order_transport_settlement.logistics_id','物流公司'],
        ];

        $search = search_form([
            'referer' => 1
        ], $columns);

        $query  = $search['query'];

        $model = DB::table('order_transport_settlement')->orderBy('id', 'desc');

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $model->leftJoin('logistics', 'logistics.id', '=', 'order_transport_settlement.logistics_id');

        $rows = $model->selectRaw('order_transport_settlement.*,logistics.name as logistics_name')
        ->paginate()->appends($query);

        $logistics = DB::table('logistics')->get(['name','id'])->toArray();

        // 视图设置
        return $this->display(array(
            'rows'      => $rows,
            'logistics' => $logistics,
            'search'    => $search,
        ));
    }

    // 计划显示
    public function showAction()
    {
        $id = Input::get('id');

        $settlement = DB::table('order_transport_settlement')
        ->where('id', $id)
        ->first();

        $logistics = DB::table('logistics')
        ->where('id', $settlement['logistics_id'])
        ->first();

        // 获取订单产品列表
        $model = OrderTransport::leftJoin('order', 'order.id', '=', 'order_transport.order_id')
        ->leftJoin('customer', 'customer.id', '=', 'order.customer_id')
        ->leftJoin('user', 'user.id', '=', 'customer.user_id')
        ->where('order_transport.settlement_id', $id);

        // 查询订单明细合计
        $model->with(['datas' => function ($q) {
            $q->leftJoin('product', 'order_data.product_id', '=', 'product.id')
            ->where('order_data.deleted_by', 0)
            ->selectRaw('order_data.order_id,order_data.fact_amount,order_data.fact_amount * product.weight as weight');
        }]);

        $rows = $model->get(['order_transport.*','order.delivery_time','order.number','user.nickname as customer_name']);

        return $this->display(array(
            'rows'       => $rows,
            'logistics'  => $logistics,
            'settlement' => $settlement,
        ));
    }

    // 计划显示
    public function printAction()
    {
        $id = Input::get('id');

        $settlement = DB::table('order_transport_settlement')
        ->where('id', $id)
        ->first();

        $logistics = DB::table('logistics')
        ->where('id', $settlement['logistics_id'])
        ->first();

        // 获取订单产品列表
        $model = OrderTransport::leftJoin('order', 'order.id', '=', 'order_transport.order_id')
        ->leftJoin('customer', 'customer.id', '=', 'order.customer_id')
        ->leftJoin('user', 'user.id', '=', 'customer.user_id')
        ->where('order_transport.settlement_id', $id);

        // 查询订单明细合计
        $model->with(['datas' => function ($q) {
            $q->leftJoin('product', 'order_data.product_id', '=', 'product.id')
            ->where('order_data.deleted_by', 0)
            ->selectRaw('order_data.order_id,order_data.fact_amount,order_data.fact_amount * product.weight as weight');
        }]);

        $rows = $model->get(['order_transport.*','order.delivery_time','order.number','user.nickname as customer_name']);

        $this->layout = 'layouts.print';
        return $this->display(array(
            'rows'      => $rows,
            'logistics' => $logistics,
        ));
    }

    // 新建物流结算
    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            $order_ids = $gets['order_id'];

            $rows = DB::table('order_transport')
            ->whereIn('order_id', $order_ids)
            ->get();

            $settlement_count = 0;
            $logistics_count  = 0;
            $logistics        = [];
            $logistics_id     = 0;
            foreach ($rows as $row) {
                if($row['settlement_id'] > 0) {
                    $settlement_count++;
                }
                if($row['logistics_id'] > 0) {
                    $logistics_count++;
                    $logistics_id = $row['logistics_id'];
                }
                $logistics[$row['logistics_id']] += 1;
            }

            if(count($logistics) > 1) {
                return $this->json('不同物流供应商不能同时生成。');
            }

            $order_count = count($order_ids);
            if($order_count > $logistics_count) {
                return $this->json('有'.($order_count - $logistics_count).'张客户订单无物流信息。');
            }

            if($settlement_count > 0) {
                return $this->json('有'.$settlement_count.'张客户订单物流已经结算。');
            }

            $data = [
                'sn'           => 'WL'.date('YmdHi'),
                'logistics_id' => $logistics_id,
            ];
            $settlement_id = DB::table('order_transport_settlement')->insertGetId($data);
            
            // 循环写入物流商ID
            foreach ($gets['order_id'] as $order_id) {
                DB::table('order_transport')
                ->where('order_id', $order_id)
                ->update(['settlement_id' => $settlement_id]);
            }
            Session::flash('message','客户订单物流结算成功。');
            return $this->json('reload', true);
        }
    }

    // 删除物流结算单
    public function deleteAction(Request $request)
    {
        $options = [];
        return Form::remove('order_transport_settlement', $options);
    }
}
