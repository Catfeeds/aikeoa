<?php namespace Aike\Product\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Product\Stock;

use Aike\Product\Product;
use Aike\Product\ProductCategory;
use Aike\Product\Warehouse;

use Aike\Index\Controllers\DefaultController;

class ProductController extends DefaultController
{
    public $permission = ['dialog', 'dialog_jqgrid'];

    // 产品列表
    public function indexAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get('id');
            foreach ($gets as $id => $sort) {
                Product::where('id', $id)->update(['sort' => $sort]);
            }
        }

        $search = search_form([
            'status'  => 1,
            'referer' => 1,
        ], [
            ['text','product.name','产品名称'],
            // ['text','product.stock_code','存货代码'],
            ['text','product.stock_number','存货编号'],
            ['category','product.category_id','产品类别'],
            ['warehouse','product.warehouse_id','产品仓库'],
            ['text','product.id','产品ID'],
        ]);

        $query  = $search['query'];

        $model = Product::with('boms')
        ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
        ->where('product_category.type', 1)
        ->where('product.status', $query['status'])
        ->orderBy('product_category.lft', 'asc')
        ->orderBy('product.sort', 'asc');

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                if ($where['field'] == 'product.category_id') {
                    $category = ProductCategory::where('id', $where['search'])->first();
                    $model->whereBetween('product_category.lft', [$category->lft, $category->rgt]);
                } elseif ($where['field'] == 'product.warehouse_id') {
                    $warehouse = Warehouse::where('id', $where['search'])->first();
                    // $model->whereBetween('psw.lft', [$warehouse->lft, $warehouse->rgt]);
                } else {
                    $model->search($where);
                }
            }
        }

        $rows = $model->select(['product.*', 'product_category.title as category_name'])
        ->paginate($search['limit'])->appends($query);

        $_categorys = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $warehouse = Warehouse::type('sale')->orderBy('lft', 'asc')->get()->toNested();

        $categorys = [];
        foreach ($_categorys as $_category) {
            $categorys[] = $_category;
        }

        return $this->display(array(
            'rows'      => $rows,
            'categorys' => $categorys,
            'warehouse' => $warehouse,
            'query'     => $query,
            'search'    => $search,
        ));
    }

    // 添加产品
    public function addAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            
            $model = Product::findOrNew($gets['id']);
            
            $rules = [
                'name'         => 'required',
                'category_id'  => 'required',
                //'stock_code'   => 'required|unique:product,stock_code,'.$gets['id'].',id,stock_type,1',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            
            // 上传图片
            $image = image_create('products', 'image', $model['image']);
            if ($image) {
                $gets['image'] = $image;
            }

            // 成品标记
            $gets['stock_type'] = 1;

            $model->fill($gets)->save();
            
            return $this->success('index', '恭喜你，产品更新成功。');
        }

        $id = (int)Input::get('id');
        $res = DB::table('product')->where('id', $id)->first();

        $category = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $warehouse = Warehouse::type('sale')->orderBy('lft', 'asc')->get()->toNested();

        $types = DB::table('customer_type')->get();
        
        return $this->display(array(
            'warehouse' => $warehouse,
            'category'  => $category,
            'res'       => $res,
            'types'     => $types,
        ));
    }

    // 成品出入库单
    public function bomAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            // 检查产品是否没有选择
            $products = is_array($gets['products']) ? $gets['products'] : json_decode($gets['products'], true);

            if (empty($products)) {
                return $this->json('商品列表不能为空。');
            }

            if ($gets['sn']) {
                // 写入库或出入主表
                $budget['sn'] = $gets['sn'];
            } else {
                // 统计数量
                $budget_count = DB::table('supplier_budget')->count('id');
                // 写入库或出入主表
                $budget['sn'] = date('ym-').($budget_count + 1);
            }

            // 入库日期
            $budget['date'] = $gets['date'] == '' ? date('Y-m-d') : $gets['date'];
            
            $insert_id = DB::table('supplier_budget')->insertGetId($budget);
    
            foreach ($products as $row) {
                $data = [
                    'budget_id'   => $insert_id,
                    'product_id'  => $row['product_id'],
                    'quantity'    => $row['quantity'],
                    'description' => (string)$row['description'],
                ];
                // 写入库数据表
                DB::table('supplier_budget_data')->insert($data);
            }

            notify()->sms(['18990305012'], '采购周期预算 - 有新的单据：'.$budget['sn'].'，日期：'.$budget['date'].'，请查看。');

            return $this->json('数据提交成功。', true);
        }

        // 统计数量
        $budget_count = DB::table('supplier_budget')->count('id');

        // 预算编号
        $budget['sn'] = date('ym-').($budget_count + 1);

        $models = [
            ['name' => "id", 'hidden' => true],
            ['name' => 'os', 'label' => '&nbsp;', 'formatter' => 'options', 'width' => 60, 'sortable' => false, 'align' => 'center'],
            ['name' => "goods_id", 'hidden' => true, 'label' => '商品ID'],
            ['name' => "goods_name", 'width' => 280, 'label' => '商品', 'rules' => ['required'=>true], 'sortable' => false, 'editable' => true],
            ['name' => "quantity", 'label' => '数量', 'width' => 140, 'rules' => ['required' => true, 'minValue' => 1,'integer' => true], 'formatter' => 'integer', 'sortable' => false, 'editable' => true, 'align' => 'right'],
            ['name' => "remark", 'label' => '备注', 'width' => 200, 'sortable' => false, 'editable' => true]
        ];

        return $this->display([
            'budget'   => $budget,
            'validate' => $this->validate,
            'models'   => $models,
        ]);
    }

    // 导出产品信息
    public function exportAction()
    {
        $columns = [[
            'name'  => 'name',
            'index' => 'product.name',
            'label' => '产品名称',
        ],[
            'name'  => 'spec',
            'index' => 'product.spec',
            'label' => '产品规格',
        ],[
            'name'  => 'category_name',
            'index' => 'product_category.name as category_name',
            'join'  => ['product_category','product_category.id','=','product.category_id'],
            'label' => '产品类别',
        ],[
            'name'  => 'price1',
            'index' => 'product.price1',
            'label' => '销售价',
        ],[
            'name'  => 'price4',
            'index' => 'product.price4',
            'label' => '销售(k/a)价',
        ],[
            'name'  => 'price2',
            'index' => 'product.price2',
            'label' => '经销价',
        ],[
            'name'  => 'price3',
            'index' => 'product.price3',
            'label' => '直营价',
        ]];

        $_columns = $_joins = [];
        foreach ($columns as $column) {
            if ($column['join']) {
                $on = $column['join'][1].$column['join'][2].$column['join'][3];
                $_joins[$on] = $column['join'];
            }

            if (is_array($column['index'])) {
                array_merge($_columns, $column['index']);
            } else {
                $_columns[] = $column['index'];
            }
        }

        $model = DB::table('product')
        ->where('product_category.type', 1)
        ->orderBy('product_category.lft', 'asc')
        ->orderBy('product.sort', 'asc');

        foreach ($_joins as $_join) {
            $model->leftJoin($_join[0], $_join[1], $_join[2], $_join[3]);
        }

        $status = Input::get('status', 1);
        $model->where('product.status', $status);
        
        $rows = $model->get($_columns);
        writeExcel($columns, $rows, date('y-m-d').'-产品档案');
    }

    /**
     * 弹出产品列表
     */
    public function dialog_jqgridAction()
    {
        $gets = Input::get();

        $abc = [
            ['text','product.name','产品名称'],
            ['text','product.spec','产品规格'],
            ['text','product.barcode','产品条码'],
            ['text','product.barcode','存货编码'],
            ['status','product.status','产品状态'],
            ['category','product.category_id','产品类别'],
            ['text','product.id','产品ID'],
        ];

        if ($gets['type'] == 2) {
            $abc[] = ['supplier','product.supplier_id','供应商'];
        }

        $search = search_form([
            'advanced'    => '',
            'owner_id'    => 0,
            'supplier_id' => 0,
            'type'        => 1,
            'page'        => 1,
            'sort'        => '',
            'order'       => '',
            'limit'       => '',
        ], $abc);
        
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = DB::table('product')
            ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
            ->where('product_category.type', $query['type'])
            ->where('product.status', 1)
            ->orderBy('product_category.lft', 'asc');

            if ($this->access['dialog'] == 1) {
                // 仓库负责人
                if ($query['owner_id']) {
                    $warehouse_id = DB::table('warehouse')->where('user_id', $query['owner_id'])->pluck('id');
                    $model->whereIn('product.warehouse_id', $warehouse_id);
                }
            }

            // 指定了供应商
            if ($query['supplier_id'] > 0) {
                $model->leftJoin('product_supplier', 'product_supplier.product_id', '=', 'product.id')
                ->where('product_supplier.supplier_id', $query['supplier_id']);
            }

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy($query['sort'], $query['order']);
            } else {
                $model->orderBy('product.sort', 'asc');
            }

            // 搜索条件
            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $rows = $model->selectRaw("product.*,product_category.name as category_name, IF(product.spec='', product.name, concat(product.name,' - ', product.spec)) as text")
            ->paginate($query['limit']);

            if ($query['stock'] == 'a') {
                // 获取库存现存量
                $totals = Stock::total();
                // 设置现存量
                $rows->transform(function ($row) use ($totals) {
                    $row['stock_total'] = (int)$totals[$row['id']];
                    return $row;
                });
            }

            // 指定了客户
            if ($query['customer_id']) {
                // 客户资料
                $customer = DB::table('user')->where('id', $query['customer_id'])->first();
                // 客户合同
                $contract = DB::table('customer_contract')
                ->whereRaw('end_time > ?', [time()])
                ->where('customer_id', $query['customer_id'])
                ->first();

                $priceItem = json_decode($contract['price_item'], true);

                $rows->transform(function ($row) use ($customer, $priceItem) {
                    $product_id = $row['id'];
                    // 计算单价类型以及自定义单价
                    $row['price'] = $priceItem[$product_id] > 0 ? number_format($priceItem[$product_id], 2) : $row['price'.$customer['post']];
                    return $row;
                });
            }

            if ($query['yonyou'] == 'a') {

                $time = time();

                // 去年 上月本月下月
                $dates['last_a'] = date('Ym', strtotime('-1 year -1 month', $time));
                $dates['last_b'] = date('Ym', strtotime('-1 year', $time));
                $dates['last_c'] = date('Ym', strtotime('-1 year +1 month', $time));

                // 今年上月本月
                $dates['a'] = date('Ym', strtotime('-1 month', $time));
                $dates['b'] = date('Ym', $time);

                // 查询用友库存
                $ch = curl_init(env('YONYOU_URL').'/yonyou.php?do=supplier_plan');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = curl_exec($ch);
                curl_close($ch);
                $yonyou = json_decode($res, true);

                // 周期订单预算
                $budgets = DB::table('supplier_budget')
                ->leftJoin('supplier_budget_data', 'supplier_budget.id', '=', 'supplier_budget_data.budget_id')
                ->selectRaw('supplier_budget_data.product_id,sum(supplier_budget_data.quantity) as budget_quantity')
                ->groupBy('supplier_budget_data.product_id')
                ->where('date', date('Y-m-d'))
                ->pluck('budget_quantity', 'product_id');

                $rows->transform(function ($row) use ($yonyou, $dates, $budgets) {

                    $code = $row['stock_number'];
                    // 去年上月本月下月库存出库
                    $row['month_1'] = $yonyou['a'][$code][$dates['last_a']];
                    $row['month_2'] = $yonyou['a'][$code][$dates['last_b']];
                    $row['month_3'] = $yonyou['a'][$code][$dates['last_c']];
                    // 本年上月本月
                    $row['month_4'] = $yonyou['b'][$code][$dates['a']];
                    $row['month_5'] = $yonyou['b'][$code][$dates['b']];
                    // 当前库存
                    $row['month_6'] = $yonyou['c'][$code];
                    // 当日周期计划
                    $row['budget'] = $budgets[$row['id']];
                    return $row;
                });
            }

            return response()->json($rows);
        }
        return $this->render(array(
            'search' => $search,
            'gets'   => $gets,
        ), 'jqgrid');
    }

    /**
     * 弹出层信息
     */
    public function dialogAction()
    {
        $gets = Input::get();

        $search = search_form([
            'advanced' => '',
            'owner_id' => 0,
            'type'     => 1,
            'offset'   => '',
            'sort'     => '',
            'order'    => '',
            'limit'    => '',
        ], [
            ['text','product.name','产品名称'],
            ['text','product.spec','产品规格'],
            ['text','product.id','产品编号'],
            ['text','product.barcode','产品条码'],
            ['category','product.category_id','产品类别'],
        ]);
        $query  = $search['query'];

        if (Request::method() == 'POST' || Request::isJson() || $gets['isjson']) {
            $model = DB::table('product')
            ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
            ->where('product_category.type', $query['type'])
            ->where('product.status', 1);

            // 筛选仓库负责人
            if ($query['owner_id']) {
                $warehouse_id = DB::table('warehouse')->where('user_id', $query['owner_id'])->pluck('id');
                $model->whereIn('product.warehouse_id', $warehouse_id);
            }

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy('product.'.$query['sort'], $query['order']);
            }

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $model->selectRaw("product.*, product_category.name as category_name,IF(product.spec='', product.name, concat(product.name,' - ', product.spec)) as text");

            if ($query['limit']) {
                $rows = $model->paginate($query['limit']);
            } else {
                $rows['total'] = $model->count();
                $rows['data']  = $model->get();
            }
            return response()->json($rows);
        }

        return $this->render(array(
            'search' => $search,
            'gets'   => $gets,
        ));
    }

    // 删除产品
    public function deleteAction()
    {
        $id = Input::get('id');
        if (empty($id)) {
            return $this->error('最少选择一行记录。');
        }

        $products = DB::table('product')->whereIn('id', $id)->get();
        foreach ($products as $product) {
            // 删除图片
            image_delete($product['image']);
        }
        // 删除数据
        DB::table('product')->whereIn('id', $id)->delete();
        
        return $this->success('index', '恭喜你，产品删除成功。');
    }
}
