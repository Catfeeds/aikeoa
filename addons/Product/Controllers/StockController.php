<?php namespace Aike\Product\Controllers;

use DB;
use Input;
use Request;
use Validator;
use Cache;

use Aike\User\User;
use Aike\Product\Warehouse;
use Aike\Product\Stock;
use Aike\Product\StockType;
use Aike\Product\ProductCategory;

use Aike\Index\Controllers\DefaultController;

class StockController extends DefaultController
{
    public $permission = ['store'];

    public $validate = [
        'rules' => [
            'number'  => 'required',
            'type_id' => 'required',
            'date'    => 'required'
        ],
        'attrs' => [
            'number'  => '单号',
            'type_id' => '库存类型',
            'date'    => '单据日期'
        ],
    ];

    // 进出库列表
    public function indexAction()
    {
        $search = search_form([
            'type'    => 0,
            'status'  => 1,
            'referer' => 1,
        ], [
            ['text','stock.number','单号'],
            ['type','stock.type_id','库存类型'],
            ['second','stock.add_time','单据日期'],
        ]);

        $query = $search['query'];

        $model = Stock::with('datas', 'type1');

        if ($query['type']) {
            $model->where('stock.type', $query['type']);
        }

        if ($query['type_id']) {
            $model->where('stock.type_id', $query['type_id']);
        }
        
        // 显示自己的入库或者出库单
        $access = User::authoriseAccess('index');
        if ($access) {
            // $model->whereIn('stock.add_user_id', $access);
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }
        
        $rows = $model->orderBy('stock.id', 'desc')
        ->paginate();

        foreach ($rows as &$row) {
            $row['quantity'] = $row->datas->sum('amount');
        }

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $types = StockType::orderBy('lft', 'asc')->get(['id', 'title as name']);

        $tabs = [
            'name'  => 'type',
            'items' => [
                ['id' => 0, 'name' => '全部'],
                ['id' => 1, 'name' => '入库'],
                ['id' => 2, 'name' => '出库'],
            ]
        ];

        return $this->display([
            'rows'   => $rows,
            'types'  => $types,
            'search' => $search,
            'tabs'   => $tabs,
        ]);
    }

    // 合并出入库单
    public function mergeAction()
    {
        if (Request::method() == 'POST') {
            $ids = Input::get('id');

            if (count($ids) <= 1) {
                return $this->error('合并单据必须两个以上。');
            }

            $datas = DB::table('stock_data')
            ->whereIn('stock_id', $ids)
            ->groupBy('product_id')
            ->selectRaw('*,SUM(amount) as count_amount')
            ->get();

            // 删除全部子数据
            DB::table('stock_data')->whereIn('stock_id', $ids)->delete();

            foreach ($ids as $i => $id) {
                if ($i) {
                    // 删除主表但是不删除第一个
                    DB::table('stock')->where('id', $id)->delete();
                }
            }

            foreach ($datas as $i => $data) {
                $data['stock_id'] = $ids[0];
                $data['amount']   = $data['count_amount'];
                DB::table('stock_data')->insert($data);
            }
            
            return $this->success('index', '恭喜你，合并成功。');
        }
    }

    // 成品出入库单
    public function createAction()
    {
        // 统计数量
        $stock_count = DB::table('stock')->count('id');

        // 写入库或出入主表
        $stock['number'] = 'CN'.date('ym-').($stock_count + 1);

        $types = StockType::orderBy('lft', 'asc')->get()->toNested();

        $models = [
            ['name' => "id", 'hidden' => true],
            ['name' => 'os', 'label' => '&nbsp;', 'formatter' => 'options', 'width' => 60, 'sortable' => false, 'align' => 'center'],
            ['name' => "product_id", 'hidden' => true, 'label' => '产品编号'],
            ['name' => "product_name", 'width' => 280, 'label' => '产品', 'rules' => ['required'=>true], 'sortable' => false, 'editable' => true],
            ['name' => "stock_total", 'label' => '现存量', 'width' => 140, 'sortable' => false, 'align' => 'right'],
            ['name' => "quantity", 'label' => '数量', 'width' => 140, 'rules' => ['required' => true, 'minValue' => 1,'integer' => true], 'formatter' => 'integer', 'sortable' => false, 'editable' => true, 'align' => 'right'],
            ['name' => "batch", 'label' => '生产批号', 'width' => 140, 'rules' => ['required'=>true, 'integer' => true], 'sortable' => false, 'editable' => true],
            ['name' => "remark", 'label' => '备注', 'width' => 200, 'sortable' => false, 'editable' => true]
        ];

        return $this->display([
            'types'    => $types,
            'stock'    => $stock,
            'validate' => $this->validate,
            'models'   => $models,
        ]);
    }

    // 保存数据
    public function storeAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            // 检查产品是否没有选择
            $products = is_array($gets['products']) ? $gets['products'] : json_decode($gets['products'], true);

            if (empty($products)) {
                return $this->json('产品列表不能为空。');
            }

            if ($gets['number']) {
                // 写入库或出入主表
                $stock['number'] = $gets['number'];
            } else {
                // 统计数量
                $stock_count = DB::table('stock')->count('id');
                // 写入库或出入主表
                $stock['number'] = 'CN'.date('ym-').($stock_count + 1);
            }

            $stock['type_id'] = $gets['type_id'];

            $type = StockType::where('id', $gets['type_id'])->first();

            $stock['type']        = $type['type'];
            // 入库日期
            $stock['date']        = $gets['date'] == '' ? date('Y-m-d') : $gets['date'];
            $stock['add_time']    = time();
            $stock['add_user_id'] = auth()->id();
            
            $insert_id = DB::table('stock')->insertGetId($stock);
 
            foreach ($products as $row) {
                $data = [
                    'stock_id'   => $insert_id,
                    'product_id' => $row['product_id'],
                    'amount'     => $row['quantity'],
                    'batch'      => $row['batch'],
                    'remark'     => (string)$row['remark'],
                    'add_time'   => time(),
                    'add_user_id'=> auth()->id()
                ];
                // 写入库数据表
                DB::table('stock_data')->insert($data);
            }

            // 删除现存量缓存
            Cache::forget('stock-total');

            return $this->json('数据提交成功。', true);
        }
    }

    // 入库记录
    public function viewAction()
    {
        $id = Input::get('id');
        
        $rows = DB::table('stock_data')
        ->leftJoin('product', 'product.id', '=', 'stock_data.product_id')
        ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
        ->where('stock_data.stock_id', $id)
        ->orderBy('product_category.id', 'ASC')
        ->orderBy('product.id', 'ASC')
        ->selectRaw('stock_data.*,stock_data.remark stock_remark,product.stock_code,product.name,product.spec,product_category.name as category_name')
        ->get();

        // 返回json
        if (Request::wantsJson()) {
            return response()->json($rows);
        }
        
        return $this->render([
            'rows' => $rows,
        ]);
    }

    // 成品库存报表
    public function reportAction()
    {
        $query_key = array(
            'sdate'        => date('Y-m-01'),
            'edate'        => date('Y-m-d'),
            'warehouse_id' => 0,
            'type_id'      => 0,
            'category_id'  => 0,
            'product_id'   => 0,
        );
        foreach ($query_key as $k => $v) {
            $query[$k] = Input::get($k, $v);
        }
        extract($query, EXTR_PREFIX_ALL, 'q');

        $model = DB::table('product as p')
        ->LeftJoin('product_category as pc', 'p.category_id', '=', 'pc.id')
        ->LeftJoin('warehouse', 'p.warehouse_id', '=', 'warehouse.id')
        ->whereRaw('p.status=1')
        ->where('pc.type', 1)
        ->orderBy('pc.lft', 'asc')
        ->orderBy('p.sort', 'asc');

        if ($q_warehouse_id > 0) {
            $warehouse = DB::table('warehouse')->where('id', $q_warehouse_id)->first();
            $model->whereBetween('warehouse.lft', [$warehouse['lft'], $warehouse['rgt']]);
        }

        // 选择产品类别
        if ($q_category_id > 0) {
            $category = DB::table('product_category')->where('id', $q_category_id)->first();
            $model->whereBetween('pc.lft', [$category['lft'], $category['rgt']]);
        }

        // 选择产品
        if ($q_product_id > 0) {
            $model->where('p.id', $q_product_id);
        }

        // 获取产品列表
        $rows = $model->get(['p.*', 'pc.name as category_name']);

        $balance = [];
        // 获取上期库存
        $balance['last'] = Stock::gets($q_sdate, null, $q_type_id);
        // 获取本期库存
        $balance['now'] = Stock::gets($q_sdate, $q_edate, $q_type_id);

        // 循环当前产品
        $codes = [];
        foreach ($rows as $product) {
            $product_id = $product['id'];

            // 上期结存
            $balance['a'][$product_id] = (int)$balance['last'][1][$product_id] - $balance['last'][2][$product_id];

            // 本期入库
            $balance['b'][$product_id] = $balance['now'][1][$product_id];
            
            // 本期出库
            $balance['c'][$product_id] = $balance['now'][2][$product_id];
            
            // 期末结存
            $now_diff = $balance['now'][1][$product_id] - $balance['now'][2][$product_id];
            
            $balance['d'][$product_id] = $balance['a'][$product_id] + $now_diff;

            if ($product['stock_number']) {
                $codes[] = $product['stock_number'];
            }
        }

        // 读取用友成品库存数量
        $ch = curl_init(env('YONYOU_URL').'/yonyou.php?do=product_inventory_total&start_dt='.$q_sdate.'&end_dt='.$q_edate.'&codes='.join(',', $codes));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $yonyou = json_decode($res, true);

        // 读取产品类别
        $product_category = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $types = StockType::orderBy('lft', 'asc')->get()->toNested();
        $warehouse = Warehouse::type('sale')->orderBy('lft', 'asc')->get()->toNested();

        return $this->display(array(
            'yonyou'           => $yonyou,
            'yonyou_history'   => $yonyou_history,
            'types'            => $types,
            'balance'          => $balance,
            'yonyou_data'      => $yonyou_data,
            'product_category' => $product_category,
            'warehouse'        => $warehouse,
            'products'         => $products,
            'selects'          => $query,
            'rows'             => $rows,
        ));
    }

    // 删除入库或出库记录，包括明细
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            DB::table('stock')->whereIn('id', $id)->delete();
            DB::table('stock_data')->whereIn('stock_id', $id)->delete();

            // 删除现存量缓存
            Cache::forget('stock-total');

            return $this->success('index', '恭喜你，删除成功。');
        }
    }

    /**
     * 订单库存功能,将库存导入用友
     */
    public function exportAction()
    {
        $id = Input::get('id', 0);

        if ($id <= 0) {
            return $this->error('缺少编号，请检查后再导出。');
        }
        
        $main = DB::table('stock')->where('type', 1)->where('id', $id)->first();

        $data = DB::table('stock_data AS a')
        ->leftJoin('product AS b', 'b.id', '=', 'a.product_id')
        ->leftJoin('product_category AS c', 'c.id', '=', 'b.category_id')
        ->where('a.stock_id', $id)
        ->orderBy('c.id', 'ASC')
        ->orderBy('b.id', 'ASC')
        ->selectRaw('a.*,a.remark stock_remark,b.stock_number,b.name,b.spec,c.name AS categoryName')
        ->get()->toArray();
        
        if (empty($main) or empty($data)) {
            return $this->error('没有数据，请检查后再导出。');
        }

        $addTime = date('Y-m-d H:i:s', $main['add_time']);
        
        $key = md5(config('default.ufida.key'));

        // &密码|日期|单号 & 存货代码|数量 & 存货代码|数量#
        $e[] = $key.'|'.$addTime.'|'.$main['number'];
        foreach ($data as $v) {
            $e[] = $v['stock_number'].'|'.$v['amount'];
        }
        $r = mb_convert_encoding(join('&', $e).'#', "gbk", "utf-8");

        // 发送数据到用友
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('default.ufida.export_stock'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $r);
        $data = curl_exec($ch);
        curl_close($ch);

        if (!$data) {
            return $this->error('连接插件失败。');
        }
        
        // 将gbk转换成UTF-8
        $data = mb_convert_encoding($data, "utf-8", "gbk");
        $r = explode('|', $data);

        if ($r[1] == '1') {
            DB::table('stock')->where('id', $id)->increment('export');
            return $this->success('index', '恭喜你，数据导入成功。');
        } else {
            return $this->error('导入失败，错误信息：<strong>'.$r[2].'</strong>');
        }
    }
}
