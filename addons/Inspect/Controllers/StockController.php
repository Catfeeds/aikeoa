<?php namespace Aike\Inspect\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;
use Paginator;
use URL;

use select;

use Aike\Inspect\InspectStock;
use Aike\Inspect\InspectStockData;
use Aike\Inspect\InspectAttachment;

use Aike\Index\Controllers\DefaultController;

class StockController extends DefaultController
{
    public $permission = [
        'stock',
        'create',
        'store',
    ];

    public function indexAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','c.nickname','客户名称'],
            ['text','c.username','客户代码'],
            ['text','user.nickname','创建者'],
            //['second','inspect_stock.add_time','创建时间'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        $search = search_form([
            'page'     => 1,
            'status'   => 1,
            'referer'  => 1,
        ], $columns);

        $start_at = Input::get('start_at');
        $end_at   = Input::get('end_at');

        $query  = $search['query'];

        $model = InspectStock::LeftJoin('inspect_stock_data', 'inspect_stock.id', '=', 'inspect_stock_data.inspect_stock_id')
        ->LeftJoin('user as c', 'c.id', '=', 'inspect_stock.customer_id')
        ->LeftJoin('customer', 'c.id', '=', 'customer.user_id')
        ->LeftJoin('user', 'user.id', '=', 'inspect_stock.add_user_id');

        if ($start_at && $end_at) {
            $model->whereRaw('FROM_UNIXTIME(inspect_stock.add_time,"%Y-%m-%d") between ? and ?', [$start_at, $end_at]);
        }

        // 销售圈
        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        // 搜索条件
        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }
        
        /*
        if($where != 1) {
            $model->whereRaw($where);
        }

        if ($query['sdate']) {
            $model->whereRaw('FROM_UNIXTIME(inspect_stock.add_time,"%Y-%m-%d") >= ?', [$query['sdate']]);
        }

        if ($query['edate']) {
            $model->whereRaw('FROM_UNIXTIME(inspect_stock.add_time,"%Y-%m-%d") <= ?', [$query['edate']]);
        }

        // 搜索客户
        if ($query['search_key'] && $query['search_value'])
        {
            $value = $query['search_condition'] == 'like' ? '%'.$query['search_value'].'%' : $query['search_value'];
            $model->whereRaw($query['search_key'].' '.$query['search_condition'].' ?', [$value]);
        }
        */

        $total = $model->distinct()->count('inspect_stock.id');

        $rows = $model->forPage($query['page'])
        ->groupBy('inspect_stock.id')
        ->orderBy('inspect_stock.id', 'desc')
        ->selectRaw('inspect_stock.*,c.nickname as company_name,inspect_stock.add_user_id,sum(inspect_stock_data.amount) as amount')
        ->get();

        $rows = Paginator::make($rows, $total)->appends($query);

        foreach ($rows as $key => $row) {
            $row['nickname'] = get_user($row['add_user_id'], 'nickname', false);
            $rows->put($key, $row);
        }

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $url = url(null, $query);

        return $this->display(array(
            'selects'  => $selects,
            'rows'     => $rows,
            'url'      => $url,
            'query'    => $query,
            'search'   => $search,
            'start_at' => $start_at,
            'end_at'   => $end_at,
        ));
    }

    public function countAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','c.nickname','客户名称'],
            ['text','c.username','客户代码'],
            ['text','user.nickname','创建者'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        $search = search_form([], $columns);

        $start_at = Input::get('start_at', date('Y-m-01'));
        $end_at   = Input::get('end_at', date('Y-m-d'));

        $query  = $search['query'];

        $model = DB::table('inspect_stock')->groupBy('add_user_id', 'customer_id');
    
        if ($start_at && $end_at) {
            $model->whereRaw('FROM_UNIXTIME(add_time,"%Y-%m-%d") between ? and ?', [$start_at, $end_at]);
        }

        // 销售圈
        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        // 搜索条件
        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }
        
        $_rows = $model->get();

        $rows = [];
        foreach ($_rows as $row) {
            $rows[$row['add_user_id']]['count'][$row['customer_id']] = 1;
            $rows[$row['add_user_id']]['region_id'] = $row['add_user_id'];
        }

        return $this->display([
            'rows'     => $rows,
            'query'    => $query,
            'search'   => $search,
            'start_at' => $start_at,
            'end_at'   => $end_at,
        ]);
    }

    public function viewAction()
    {
        $id = (int)Input::get('id');
        
        $main = DB::table('inspect_stock as a')
        ->leftJoin('user as c', 'c.id', '=', 'a.customer_id')
        ->where('a.id', $id)
        ->first(['c.nickname as company_name','a.*']);

        $rows = DB::table('inspect_stock_data as a')
        ->leftJoin('product as b', 'b.id', '=', 'a.product_id')
        ->where('a.inspect_stock_id', $id)
        ->get(['a.*','b.name','b.spec']);

        // 当前时间的上次发货数量
        $res = DB::table('order_data')
        ->leftJoin('order', 'order.id', '=', 'order_data.order_id')
        ->where('order.delivery_time', '>', 0)
        ->where('order.delivery_time', '<', $main['add_time'])
        ->where('order.customer_id', $main['customer_id'])
        ->whereIn('order_data.product_id', $rows->pluck('product_id'))
        ->groupBy('order_data.product_id', 'order_data.order_id')
        ->selectRaw('order_data.product_id,order.delivery_time,sum(order_data.fact_amount) as fact_amount')
        ->orderBy('order_data.id', 'desc')
        ->get();

        $orderDatas = [];
        foreach ($res as $v) {
            if (!isset($orderDatas[$v['product_id']])) {
                $orderDatas[$v['product_id']] = $v;
            }
        }

        // 上次发货数量
        $attachments = attachment_view('inspect_attachment', $main['attachment']);

        // 返回json
        if (Request::wantsJson()) {
            $products = [];
            foreach ($rows as $row) {
                $products[] = $row['name'].$row['spec'].'('.(int)$row['amount'].','.(int)$row['amount1'].")";
            }
            $main['products'] = join("\n", $products);

            $_attachments = [];
            foreach ($attachments['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $main['attachments'] = $_attachments;
            return response()->json($main);
        }
        
        return $this->render(array(
            'main'        => $main,
            'rows'        => $rows,
            'orderDatas'  => $orderDatas,
            'attachments' => $attachments,
        ));
    }

    public function printAction()
    {
        $id = (int)Input::get('id');
  
        $main = DB::table('inspect_stock as a')
        ->leftJoin('user as c', 'c.id', '=', 'a.customer_id')
        ->where('a.id', $id)
        ->select(['c.nickname as company_name', 'a.*'])->first();

        $rows = DB::table('inspect_stock_data as a')
        ->leftJoin('product as b', 'b.id', '=', 'a.product_id')
        ->where('a.inspect_stock_id', $id)
        ->get(['a.*','b.name','b.spec']);

         // 当前时间的上次发货数量
        $res = DB::table('order_data')
        ->leftJoin('order', 'order.id', '=', 'order_data.order_id')
        ->where('order.delivery_time', '>', 0)
        ->where('order.delivery_time', '<', $main['add_time'])
        ->where('order.customer_id', $main['customer_id'])
        ->whereIn('order_data.product_id', $rows->pluck('product_id'))
        ->groupBy('order_data.product_id', 'order_data.order_id')
        ->selectRaw('order_data.product_id,order.delivery_time,sum(order_data.fact_amount) as fact_amount')
        ->orderBy('order_data.id', 'desc')
        ->get();
        $orderDatas = [];
        foreach ($res as $v) {
            if (!isset($orderDatas[$v['product_id']])) {
                $orderDatas[$v['product_id']] = $v;
            }
        }

        $attachments = attachment_view('inspect_attachment', $main['attachment']);

        // 返回 json
        if (Request::wantsJson()) {
            $_attachments = [];
            foreach ($attachments['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $main['attachments'] = $_attachments;
            return response()->json($main);
        }

        // $this->layout = 'layouts.print';
        return $this->display([
            'main'        => $main,
            'rows'        => $rows,
            'attachments' => $attachments,
            'orderDatas'  => $orderDatas,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)Input::get('id');

        $row = DB::table('inspect_stock')->where('id', $id)->first();

        if ($row) {
            DB::table('inspect_stock')->where('id', $id)->delete();
            DB::table('inspect_stock_data')->where('inspect_stock_id', $id)->delete();
            
            attachment_delete('inspect_attachment', $row['attachment']);

            return $this->success('index', '恭喜您，操作成功。');
        }
        return $this->error('编号不正确，无法删除。');
    }

    public function stockAction()
    {
        $rows = InspectStock::LeftJoin('user', 'user.id', '=', 'inspect_stock.customer_id')
        ->LeftJoin('inspect_stock_data', 'inspect_stock.id', '=', 'inspect_stock_data.inspect_stock_id')
        ->where('inspect_stock.add_user_id', Auth::id())
        ->selectRaw('FROM_UNIXTIME(inspect_stock.add_time, "%Y-%m-%d %H:%i") as add_time,inspect_stock.customer_id,user.nickname as company_name,SUM(inspect_stock_data.amount) as amount')
        ->orderBy('inspect_stock.id', 'desc')
        ->paginate();
        return response()->json($rows);
    }

    // 上传库存数据
    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets  = Input::get();

            $files = Input::file('images');

            $products = json_decode($gets['products'], true);

            if (empty($gets['customer_id'])) {
                return $this->json('客户必须选择。');
            }

            if (empty($products)) {
                return $this->json('产品不能为空。');
            }

            if (empty($files)) {
                return $this->json('照片不能为空。');
            }

            $attachmentId = [];
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $path = 'inspect/'.date('Y/m');
                    $upload_path = upload_path().'/'.$path;
                    
                    // 文件后缀名
                    $extension = $file->getClientOriginalExtension();
                    // 文件新名字
                    $filename = date('dhis_').str_random(4).'.'.$extension;
                    $filename = mb_strtolower($filename);

                    $attach = [];
                    if ($file->move($upload_path, $filename)) {
                        $attachmentId[] = InspectAttachment::insertGetId([
                            'path'  => $path,
                            'name'  => mb_strtolower($file->getClientOriginalName()),
                            'title' => $filename,
                            'type'  => $extension,
                            'state' => 1,
                            'add_user_id' => Auth::id(),
                            'add_time' => time(),
                        ]);
                    }
                }
            }
            // 写入主表信息
            $inspectStockId = InspectStock::insertGetId([
                'customer_id' => $gets['customer_id'],
                'lat'         => $gets['lat'],
                'lng'         => $gets['lng'],
                'add_user_id' => Auth::id(),
                'add_time'    => time(),
                'attachment'  => join(',', $attachmentId),
            ]);
 
            // 写入产品明细
            foreach ($products as $product) {
                $product['inspect_stock_id'] = $inspectStockId;
                InspectStockData::insert($product);
            }
            return $this->json('数据上传成功。', true);
        }
        return $this->json('数据上传失败。');
    }

    // 保存库存数据
    public function storeAction()
    {
        if (Input::isJson()) {
            $gets = json_decode(Request::getContent(), true);
        } else {
            $gets = Input::get();
        }

        $rules = [
            'customer_id'  => 'required',
            'lat'          => 'required',
            'lng'          => 'required',
            //'products'   => 'min:1|array|required',
            //'attachment' => 'min:1|array|required',
        ];
        
        $v = Validator::make($gets, $rules);
        if ($v->fails()) {
            return $this->json($v->errors());
        }

        if (is_array($gets['products'])) {
            $products = array_pull($gets, 'products');
        } else {
            $products = array_pull($gets, 'product');
            $products = json_decode($products, true);
        }
        
        if (is_array($gets['attachment'])) {
            $gets['attachment'] = attachment_base64('inspect_attachment', $gets['attachment'], 'inspect');
        } else {
            $gets['attachment'] = attachment_images('inspect_attachment', 'image', 'inspect');
        }

        $gets['add_user_id'] = Auth::id();
        $gets['add_time'] = time();

        $row = new InspectStock;

        $row->fill($gets)->save();

        // 写入产品明细
        foreach ($products as $product) {
            $product['inspect_stock_id'] = $row->id;
            InspectStockData::insert($product);
        }
        return $this->json('数据上传成功。', true);
    }
}
