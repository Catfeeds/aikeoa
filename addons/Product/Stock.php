<?php namespace Aike\Product;

use DB;
use Cache;
use Aike\Index\BaseModel;

class Stock extends BaseModel
{
    protected $table = 'stock';

    public function datas()
    {
        return $this->hasMany('Aike\Product\StockData', 'stock_id');
    }

    public function type1()
    {
        return $this->belongsTo('Aike\Product\StockType', 'type_id');
    }

    /**
     * 获取库存量及出入库详细产品列表
     *
     * 1.获取所有库存: gets();
     * 2.获取本期库存: gets('2013-07-01',2013-07-31');
     * 2.获取上期库存: gets('2013-07-31');
     */
    public static function gets($start_date = null, $end_date = null, $type_id = 0)
    {
        $model = DB::table('stock as s')
        ->LeftJoin('stock_data as sd', 's.id', '=', 'sd.stock_id')
        ->LeftJoin('stock_type as st', 's.type_id', '=', 'st.id')
        ->groupBy(['sd.product_id', 'st.type'])
        ->selectRaw('st.type, sd.product_id, SUM(sd.amount) as amount');
    
        // 获取本期库存
        if ($start_date && $end_date) {
            $model->whereRaw('s.date between ? and ?', [$start_date, date('Y-m-d', strtotime($end_date)+86400)]);
        // 获取上期库存
        } elseif ($start_date) {
            $model->whereRaw('s.date < ?', [$start_date]);
        }

        // 库存类型
        if ($type_id) {
            $model->where('st.id', $type_id);
        }

        $rows = $model->get();

        $data = [];
        if ($rows->count()) {
            // 将入库和出库分开计算
            foreach ($rows as $row) {
                $product_id = $row['product_id'];
                $data[$row['type']][$product_id] = $row['amount'];
            }
        }
        return $data;
    }

    /**
     * 计算库存结存数量
     */
    public static function settlement()
    {
        $data = [];
        $stocks = static::gets();
        $products = Product::gets();
        foreach ($products as $product_id => $product) {
            if (isset($stocks[1][$product_id]) && isset($stocks[2][$product_id])) {
                $data[$product_id] = $stocks[1][$product_id] - $stocks[2][$product_id];
            }
        }
        return $data;
    }

    /**
     * 计算现存数量
     */
    public static function total()
    {
        if (Cache::has('stock-total')) {
            return Cache::get('stock-total');
        }

        $data = [];
        $rows = $model = DB::table('stock')
        ->LeftJoin('stock_data', 'stock.id', '=', 'stock_data.stock_id')
        ->LeftJoin('stock_type', 'stock.type_id', '=', 'stock_type.id')
        ->groupBy(['stock_data.product_id', 'stock_type.type'])
        ->selectRaw('stock_type.type, stock_data.product_id, SUM(stock_data.amount) as amount')
        ->get();

        $data = [];
        foreach ($rows as $row) {
            $type       = $row['type'];
            $product_id = $row['product_id'];
            if ($type == 1) {
                $data[$product_id] = $data[$product_id] + $row['amount'];
            } else {
                $data[$product_id] = $data[$product_id] - $row['amount'];
            }
        }

        // 缓存现存量为24小时
        Cache::put('stock-total', $data, 1440);

        return $data;
    }

    /**
     * 获取当前库存统计
     */
    public static function getCount($type = 1)
    {
        return DB::table('stock')->where('type_id', $type)->count('id');
    }
    
    /**
     * 新增入库获取出库
     */
    public static function setAdd($rows, $stock_type_id, $type = 1)
    {
        if (empty($rows)) {
            return false;
        }

        // 统计数量
        $stock_count = static::getCount($stock_type_id) + 1;

        // 写入库或出入主表
        $data['number']      = date('ymd-').$stock_count;
        $data['date']        = date('Y-m-d');
        $data['type_id']     = $stock_type_id;
        $data['type']        = $type;
        $data['add_time']    = time();
        $data['add_user_id'] = auth()->id();
        
        $insert_id = DB::table('stock')->insertGetId($data);
        
        unset($data['number']);

        foreach ($rows as $product_id => $row) {
            $data['stock_id']   = $insert_id;
            $data['product_id'] = $product_id;
            $data['amount']     = $row['amount'];
            $data['remark']     = (string)$row['remark'];

            // 写入库数据表
            DB::table('stock_data')->insert($data);
        }

        // 删除现存量缓存
        Cache::forget('stock-total');

        return true;
    }
}
