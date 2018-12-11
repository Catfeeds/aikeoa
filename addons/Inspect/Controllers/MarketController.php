<?php namespace Aike\Inspect\Controllers;

use DB;
use Input;
use Request;
use Validator;
use Auth;
use URL;

use select;

use Aike\User\User;
use Aike\Product\Product;
use Aike\Product\ProductCategory;

use Aike\Inspect\InspectMarket;
use Aike\Inspect\InspectMarketCategory;

use Aike\Index\Controllers\DefaultController;

class MarketController extends DefaultController
{
    public $permission = ['create','product_category','product','customer','store'];

    public function indexAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','c.nickname','客户名称'],
            ['text','c.username','客户代码'],
            ['text','user.nickname','创建者'],
            ['second','a.add_time','创建时间'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        $search = search_form([
            'status'   => 1,
            'referer'  => 1,
        ], $columns);

        $query = $search['query'];

        $model = DB::table('inspect_market as a');
        $model->leftJoin('inspect_market_category as mc', 'mc.id', '=', 'a.category_id')
        ->leftJoin('user as c', 'c.id', '=', 'a.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('user', 'user.id', '=', 'a.add_user_id')
        ->orderBy('a.id', 'desc')
        ->select(['user.nickname','c.nickname as company_name','a.*','mc.title as category_name']);

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

        $rows = $model->paginate()->appends($query);
        
        // 返回json
        if (Request::wantsJson()) {
            return response()->json($rows);
        }

        $categorys = DB::table('inspect_market_category')->get();
        
        return $this->display(array(
            'selects'   => $selects,
            'categorys' => $categorys,
            'rows'      => $rows,
            'query'     => $query,
            'search'    => $search,
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

        $model = DB::table('inspect_market');
    
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
            $rows[$row['add_user_id']]['count']++;
            $rows[$row['add_user_id']]['customer_count'][$row['customer_id']] = 1;
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
  
        $main = DB::table('inspect_market as a')
        ->leftJoin('user as c', 'c.id', '=', 'a.customer_id')
        ->leftJoin('inspect_market_category as imc', 'imc.id', '=', 'a.category_id')
        ->where('a.id', $id)
        ->select(['imc.title as category_name','c.nickname as company_name', 'a.*'])->first();

        $attachments = attachment_view('inspect_attachment', $main['attachment']);
        $attachments2 = attachment_view('inspect_attachment', $main['attachment2']);

        // 返回 json
        if (Request::wantsJson()) {
            $_attachments = [];
            foreach ($attachments['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $main['attachments'] = $_attachments;
            
            $_attachments = [];
            foreach ($attachments2['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $main['attachments2'] = $_attachments;

            return response()->json($main);
        }

        return $this->render(array(
            'main'         => $main,
            'attachments'  => $attachments,
            'attachments2' => $attachments2,
        ));
    }

    public function printAction()
    {
        $id = (int)Input::get('id');
  
        $main = DB::table('inspect_market as a')
        ->leftJoin('user as c', 'c.id', '=', 'a.customer_id')
        ->leftJoin('inspect_market_category as imc', 'imc.id', '=', 'a.category_id')
        ->where('a.id', $id)
        ->select(['imc.title as category_name','c.nickname as company_name', 'a.*'])->first();

        $attachments = attachment_view('inspect_attachment', $main['attachment']);
        $attachments2 = attachment_view('inspect_attachment', $main['attachment2']);

        // 返回 json
        if (Request::wantsJson()) {
            $_attachments = [];
            foreach ($attachments['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $main['attachments'] = $_attachments;
            
            $_attachments = [];
            foreach ($attachments2['view'] as $attachment) {
                $_attachments[] = $attachment;
            }
            $main['attachments2'] = $_attachments;

            return response()->json($main);
        }

        $_attachments = [];
        foreach ($attachments['view'] as $attachment) {
            $_attachments[] = $attachment;
        }
        foreach ($attachments2['view'] as $attachment) {
            $_attachments[] = $attachment;
        }

        // $this->layout = 'layouts.print';
        return $this->display(array(
            'main'         => $main,
            'attachments'  => $attachments,
            'attachments2' => $attachments2,
            '_attachments' => $_attachments,
        ));
    }

    // 保存巡店数据
    public function storeAction()
    {
        if (Input::isJson()) {
            $gets = json_decode(Request::getContent(), true);
        } else {
            $gets = Input::get();
        }

        $rules = [
            'salesman'    => 'required',
            'customer_id' => 'required',
            'title'       => 'required',
        ];
        
        $v = Validator::make($gets, $rules);
        if ($v->fails()) {
            return $this->json($v->errors());
        }

        $gets['attachment'] = attachment_images('inspect_attachment', 'image', 'inspect');
        // $gets['attachment2'] = attachment_images('inspect_attachment', 'image2', 'inspect');
        
        $gets['add_user_id'] = Auth::id();
        $gets['add_time'] = time();

        $row = new InspectMarket;
        $row->fill($gets)->save();
        return $this->json('数据上传成功。', true);
    }
    

    public function deleteAction()
    {
        $id = (int)Input::get('id');

        $row = DB::table('inspect_market')->where('id', $id)->first();

        if ($row['id']) {
            DB::table('inspect_market')->where('id', $id)->delete();
 
            attachment_delete('inspect_attachment', $row['attachment']);
            attachment_delete('inspect_attachment', $row['attachment2']);
            
            return $this->success('index', '恭喜您，操作成功。');
        }
        return $this->error('编号不正确，无法删除。');
    }

    // 市场巡查类别
    public function categoryAction()
    {
        // 更新排序
        if ($post = $this->post('sort')) {
            foreach ($post as $k => $v) {
                $data['sort'] = $v;
                DB::table('inspect_market_category')->where('id', $k)->update($data);
            }
        }

        $rows = InspectMarketCategory::get(['*','title as text']);

        // 返回 json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        if (Input::get('data_type') == 'json') {
            return response()->json($rows);
        }

        return $this->display(array(
            'rows' => $rows,
        ));
    }

    // 添加市场类别
    public function category_addAction()
    {
        $id = (int)Input::get('id');
        
        if ($post = $this->post()) {
            if (empty($post['title'])) {
                return $this->error('很抱歉，类别名称必须填写。');
            }
            
            unset($post['past_parent_id']);

            if ($post['id'] > 0) {
                DB::table('inspect_market_category')->where('id', $id)->update($post);
            } else {
                DB::table('inspect_market_category')->insert($post);
            }
            return $this->success('category', '恭喜你，类别更新成功。');
        }

        $row = DB::table('inspect_market_category')->where('id', $id)->first();
        
        return $this->display(array(
            'row'  => $row,
        ));
    }

    // 删除市场类别
    public function category_deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('很抱歉，编号不正确。');
        }
        DB::table('inspect_market_category')->where('id', $id)->delete();
        return $this->success('category', '恭喜你，类别删除成功。');
    }

    public function product_categoryAction()
    {
        $rows = ProductCategory::type('sale')
        ->orderBy('lft', 'asc')
        ->get()->toNested();
        
        $json = array();
        foreach ($rows as $row) {
            $json[] = [
                'id'    => $row['id'],
                'lft'   => $row['lft'],
                'title' => $row['name'],
                'name'  => $row['layer_space'].$row['name'],
            ];
        }
        return response()->json($json);
    }

    public function productAction()
    {
        $category_id = Input::get('category_id');
        $customer_id = Input::get('customer_id');
    
        $model = Product::where('status', 1);
        if ($category_id > 0) {
            $model->where('category_id', $category_id);
        }

        $rows = $model->get(['id', 'category_id', 'name', 'spec']);

        // 当前时间的上次发货数量
        $res = DB::table('order_data')
        ->leftJoin('order', 'order.id', '=', 'order_data.order_id')
        ->where('order.delivery_time', '>', 0)
        ->where('order.delivery_time', '<', time())
        ->where('order.customer_id', $customer_id)
        ->whereIn('order_data.product_id', $rows->pluck('id'))
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

        foreach ($rows as &$row) {
            $order = $orderDatas[$row['id']];
            $row['last_order'] = $order['fact_amount'] > 0 ? $order['fact_amount'].' ('.date('Y-m-d H:i', $order['delivery_time']).')' : '';
            $row['name'] = $row['spec'] ? $row['name'].' - '.$row['spec'] : $row['name'];
        }
        return response()->json($rows);
    }

    public function customerAction()
    {
        $model = User::where('group_id', 2)
        ->leftJoin('customer', 'customer.user_id', '=', 'user.id')
        ->where('status', 1);

        // 客户圈权限
        $circle = select::circleCustomer();
        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        $rows = $model->get(['user.id', 'user.username as number', 'user.nickname as company_name', 'user.nickname as text']);

        if ($rows->isEmpty()) {
            $rows[] = User::where('id', Auth::id())
            ->first(['id', 'username as number', 'nickname as company_name', 'nickname as text']);
        }
        return response()->json($rows);
    }
}
