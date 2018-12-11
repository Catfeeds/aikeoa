<?php namespace Aike\Order\Controllers;

use DB;
use Input;
use View;
use select;
use Request;

use App\Jobs\SendSms;

use Aike\Index\Notification;
use Aike\Product\ProductCategory;
use Aike\Customer\CustomerType;
use Aike\Index\Controllers\DefaultController;

class ReportController extends DefaultController
{
    public $promotion = [
        'promotions_category' => [
            1 => '消费促销',
            2 => '渠道促销',
            3 => '经销促销'
        ],
        'promotion_category' => [
            1 => '消费',
            2 => '渠道',
            3 => '经销'
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        // 客户类型
        $customer_type = DB::table('customer_type')->get();
        $customer_type = array_by($customer_type);
        View::share('customer_type', $customer_type);
    }

    // 销售曲线图分析
    public function indexAction()
    {
        // 本年时间
        $now_year  = date("Y");

        // 筛选专用函数
        $selects = $query1 = select::head1();
        $where = $selects['where'];

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        // 获得GET数据
        $category_id = Input::get('category_id', 0);
        $selects['select']['category_id'] = $category_id;

        // 获取品类
        $_categorys = ProductCategory::orderBy('lft', 'asc')
        ->where('status', 1)
        ->where('type', 1)
        ->get()->toNested();

        if ($category_id) {
            $category = $_categorys[$category_id];
            $category = DB::table('product_category')
            ->where('lft', '>=', $category['lft'])
            ->where('rgt', '<=', $category['rgt'])
            ->pluck('id');
        }

        /** 年度月份曲线图 **/
        $model = DB::table('order_data as i')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('i.deleted_by', 0)
        ->where('o.'.$time_type, '>', 0)
        ->where('t.type', 1)
        ->where('o.status', 1)
        ->groupBy('year')
        ->selectRaw('SUM(i.'.$amount_type.' * i.price) as money,FROM_UNIXTIME(o.'.$time_type.',"%Y-%c") as year');
        // 客户圈权限
        if ($selects['whereIn']) {
            foreach ($selects['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        
        if ($category_id) {
            $model->whereIn('p.category_id', $category);
        }

        $rows = $model->get();

        $years = array();
        foreach ($rows as $row) {
            list($year, $month) = explode('-', $row['year']);
            $years[$year][$month] = $row;
        }
        unset($rows);
        
        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->where('i.deleted_by', 0)
        ->where('o.'.$time_type, '>', 0)
        ->where('t.type', 1)
        ->where('o.status', 1)
        ->whereRaw('FROM_UNIXTIME(o.'.$time_type.',"%Y")=YEAR(CURDATE())')
        ->groupBy('p.category_id')
        ->selectRaw('p.category_id,SUM(i.'.$amount_type.' * i.price) as money');

        // 客户圈权限
        if ($selects['whereIn']) {
            foreach ($selects['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }

        if ($category_id) {
            $model->whereIn('p.category_id', $category);
        }
        $rows = $model->get();

        $categorys = array();
        foreach ($rows as $row) {
            // 取得产品类别的定级类别编号
            $category_id = $_categorys[$row['category_id']]['parent'][0];
            if ($category_id) {
                $categorys[$category_id] += $row['money'];
            }
        }
        unset($rows);

        // bd 预估费用， bm 兑现费用
        $model = DB::table('promotion as p')
        ->leftJoin('user as c', 'p.customer_id', '=', 'c.id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('p.deleted_by', 0)
        ->whereRaw("DATE_FORMAT(p.data_30, '%Y')=?", [date('Y')])
        ->groupBy('p.type_id')
        ->selectRaw('p.type_id, SUM(IF(p.data_amount1 > 0, p.data_amount1, p.data_amount)) AS bd, SUM(p.data_amount) bd1, SUM(p.data_amount1) bm');
        // 客户圈权限
        if ($selects['whereIn']) {
            foreach ($selects['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $ps = $model->get();

        $model = DB::table('promotion as p')
        ->leftJoin('user as c', 'p.customer_id', '=', 'c.id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('p.deleted_by', 0)
        ->whereRaw("DATE_FORMAT(p.data_30, '%Y')=?", [date('Y')])
        ->selectRaw('
            SUM(IF(p.data_amount1_pl > 0, p.data_amount1_pl, p.data_amount_pl)) PL,
            SUM(IF(p.data_amount1_tc > 0, p.data_amount1_tc, p.data_amount_tc)) TC,
            SUM(IF(p.data_amount1_pc > 0, p.data_amount1_pc, p.data_amount_pc)) PC,
            SUM(IF(p.data_amount1_ppc > 0, p.data_amount1_ppc, p.data_amount_ppc)) PPC,
            SUM(IF(p.data_amount1_hz > 0, p.data_amount1_hz, p.data_amount_hz)) HZ,
            SUM(IF(p.data_amount1_ql > 0, p.data_amount1_ql, p.data_amount_ql)) QL
        ');
        // 客户圈权限
        if ($selects['whereIn']) {
            foreach ($selects['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $promotions = $model->first();
        
        foreach ($_categorys as $category_id => $category) {
            if ($category['parent_id'] == 0) {
                $category_code = strtoupper($category['code']);
                
                if ($promotions[$category_code] > 0 && $categorys[$category_id] > 0) {
                    $_cat_temp = ($promotions[$category_code]/$categorys[$category_id]) * 100;
                    $_cat_temp = number_format($_cat_temp, 2).'%';
                } else {
                    $_cat_temp = '0.00%';
                }
                $cat_salesdata_ret[$category_id] = $_cat_temp;
            }
        }
        unset($promotions);

        $promotion = array();
        if ($ps->count()) {
            foreach ($ps as $key => $value) {
                // 本年读已经兑现的促销金额
                $promotion_honor += $value['bm'];
                //本年促销分类金额
                $promotion['cat'][$value['type_id']] = $value['bd'];
                //本年所有促销金额
                $promotion['all'] += $value['bd'];
            }
        }
        unset($ps);

        $data_all = array_sum($categorys);

        //本年促销费比(金额)计算
        if ($data_all > 0) {
            $promotions_all = ($promotion['all']/$data_all) * 100;
        }
        $assess = number_format($promotions_all, 2).'%';

        //多产品年度颜色定义
        $color = array('FF9900','339900','3399FF','FF66CC');

        $json = array();

        for ($i=1; $i <= 12; $i++) {
            $json['categories'][] = $i.'月';
        }

        if ($years) {
            $key = 0;
            $json['total'] = [];
            foreach ($years as $year => $months) {
                if ($year > 0) {
                    $j['name'] = $year;
                    $j['data'] = array();
                    for ($i=1; $i <= 12; $i++) {
                        $j['data'][] = (int)$months[$i]['money'];
                    }
                    $json['total'][$year] = number_format(array_sum($j['data']), 2);
                }
                $json['series'][] = $j;
            }
        }

        $query = url().'?'.http_build_query($selects['select']);
        return $this->display(array(
            'cat_salesdata_ret' => $cat_salesdata_ret,
            'product_categorys' => $_categorys,
            'categorys'         => $categorys,
            'promotion'         => $promotion,
            'promotion_honor'   => $promotion_honor,
            'select'            => $selects,
            'query'             => $query,
            'assess'            => $assess,
            'customer_types'    => $customer_types,
            'json'              => json_encode($json),
        ));
    }

    // 全国数据分类方法
    public function categoryAction()
    {
        // 当前年月日
        $start_date = Input::get('start_date', date('Y-01-01'));
        $end_date   = Input::get('end_date', date("Y-m-d"));
        // 减一年时间戳
        $last_start_date = date('Y-m-d', strtotime($start_date.'-1 year'));
        $last_end_date = date('Y-m-d', strtotime($end_date.'-1 year'));

        $start_year = date('Y', strtotime($start_date.'-1 year'));
        $end_year = date('Y', strtotime($end_date));

        // 筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];
        $selects['select']['date'] = $end_date;

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        // 获取产品类别
        $product_categorys = DB::table('product_category')
        ->where('node.status', 1)
        ->where('node.type', 1)
        ->toTreeChildren();

        $one = $two = [];
        foreach ($product_categorys as $category) {
            if ($category['level'] == 0) {
                foreach ($category['children'] as $children) {
                    $one[$children] = $category['id'];
                }
            }
            if ($category['level'] == 1) {
                foreach ($category['children'] as $children) {
                    $two[$children] = $category['id'];
                }
            }
        }
        $one[0] = 0;

        $product_categorys[0] = ['name' => '无品类', 'code' => 'NULL'];

        //$product_categorys = array_by($product_categorys);

        $percentData = $pieData = array();

        /** 品类累计到今天 **/
        // foreach ($product_categorys as $category) {
            
        $model = DB::table('order_data as i')
            ->leftJoin('order_type as t', 't.id', '=', 'i.type')
            ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
            ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('product_category as pc', 'pc.id', '=', 'p.category_id')
            ->where('i.deleted_by', 0)
            ->whereRaw("o.$time_type BETWEEN UNIX_TIMESTAMP('$last_start_date') AND UNIX_TIMESTAMP('$end_date')")
            //->whereRaw('pc.lft BETWEEN '.$category['lft'].' AND '.$category['rgt'])
            ->where('t.type', 1)
            ->where('o.status', 1)
            ->groupBy('year')
            ->groupBy('month')
            ->groupBy('p.category_id')
            ->selectRaw('p.category_id,SUM(i.'.$amount_type.' * i.price) money,FROM_UNIXTIME(o.'.$time_type.',"%Y") year,FROM_UNIXTIME(o.'.$time_type.',"%c") month');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();
            
        foreach ($rows as $row) {
            $category_id = (int)$one[$row['category_id']];
            if ($category_id > 0) {
                $pieData[$row['year']][$category_id] += $row['money'];
                $columnData[$row['year']][$category_id][$row['month']] += $row['money'];
            }
        }

        $rows = $model = DB::table('order_data as i')
            ->leftJoin('order_type as t', 't.id', '=', 'i.type')
            ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
            ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('product_category as pc', 'pc.id', '=', 'p.category_id')
            ->where('i.deleted_by', 0)
            ->whereRaw("FROM_UNIXTIME(o.$time_type,'%m-%d') BETWEEN '".date('m-d', strtotime($start_date))."' AND '".date('m-d', strtotime($end_date))."'")
            //->whereRaw('pc.lft BETWEEN '.$category['lft'].' AND '.$category['rgt'])
            ->where('t.type', 1)
            ->where('o.status', 1)
            ->groupBy('year')
            ->groupBy('p.category_id')
            ->selectRaw('p.category_id,SUM(i.'.$amount_type.' * i.price) money,FROM_UNIXTIME(o.'.$time_type.',"%Y") year');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();

        foreach ($rows as $row) {
            $category_id = (int)$one[$row['category_id']];
            $_category_id = (int)$two[$row['category_id']];
            $percentData[$row['year']][$category_id] += $row['money'];
            $percentData[$row['year']][$_category_id] += $row['money'];
            // 需要单独排除否则计算不准确
            $_percentData[$row['year']][$_category_id] += $row['money'];
        }

        //}
        unset($rows);

        // 去年区域销售额和今年金额占比
        if (is_array($percentData[$end_year])) {
            $percentage = array();
            foreach ($percentData[$end_year] as $key => $value) {
                $per = $value - $percentData[$start_year][$key];
                if ($percentData[$start_year][$key] > 0) {
                    $p = number_format(($per/$percentData[$start_year][$key])*100, 2);
                } else {
                    $p = '0.00';
                }
                $percentage[$key] = $p;
            }
        }

        // 本年同期去年占比
        $now_year_sum = is_array($percentData[$end_year]) ? array_sum((array)$percentData[$end_year]) : 0;
        $now_year_sum = $now_year_sum - array_sum((array)$_percentData[$end_year]);
        
        $last_year_sum = is_array($percentData[$start_year]) ? array_sum((array)$percentData[$start_year]) : 0;
        $last_year_sum = $last_year_sum - array_sum((array)$_percentData[$start_year]);

        if ($now_year_sum > 0) {
            $temp = $now_year_sum - $last_year_sum;
            $total = number_format(($temp/$now_year_sum)*100, 2);
        }
        $percentage['total'] = $total;
        $percentData['sum'][$end_year]  = $now_year_sum;
        $percentData['sum'][$start_year] = $last_year_sum;

        //饼图数据
        $json = array();
        if ($pieData) {
            foreach ($pieData as $year => $category) {
                $pie = array();
                foreach ($category as $category_id => $v) {
                    $title = $product_categorys[$category_id]['name'];
                    if (empty($pie)) {
                        $pie[] = array('name'=>$title,'y'=>$v,'sliced'=>true,'selected'=>true);
                    } else {
                        $pie[] = array($title,$v);
                    }
                }
                $json['pie'][$year] = $pie;
            }
        }

        if (count($json['pie'])) {
            asort($json['pie']);
        }

        if (count($columnData)) {
            asort($columnData);
        }

        if ($columnData) {
            foreach ($columnData as $year => $category) {
                for ($i=1; $i <= 12; $i++) {
                    $json['column']['categories'][] = $i.'月';
                }
                foreach ($category as $category_id => $months) {
                    $series = array();
                    $title = $product_categorys[$category_id]['name'];
                    $series['name'] = $title;
                    for ($i=1; $i <= 12; $i++) {
                        $series['data'][] = (int)$months[$i];
                    }
                    $json['column']['series'][$year][] = $series;
                }
            }
        }
        $query = url().'?'.http_build_query($selects['select']);

        $startTime = date('Y', $this->setting['setup_at']);
        $years = range($startTime, date('Y'));
        $months = range(1, 12);
        $selects['select']['years'] = $years;
        $selects['select']['months'] = $months;

        return $this->display(array(
            'echo'              => $echo,
            'percentage'        => $percentage,
            'percentData'       => $percentData,
            'product_categorys' => $product_categorys,
            'select'            => $selects,
            'now_year'          => $end_year,
            'last_year'         => $start_year,
            'query'             => $query,
            'assess'            => $assess,
            'stackedcolumn'     => $stackedcolumn,
            'json'              => json_encode($json),
        ));
    }

    // 单品查询
    public function singleAction()
    {
        //筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $now_year = date("Y", time());

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        // 获得GET数据
        $category_id = Input::get('category_id', 1);
        $selects['select']['category_id'] = $category_id;
        
        $categorys = ProductCategory::orderBy('lft', 'asc')
        ->where('status', 1)
        ->where('type', 1)
        ->get()->toNested();

        if ($category_id) {
            $category = $categorys[$category_id];
            $category = DB::table('product_category')
            ->where('lft', '>=', $category['lft'])
            ->where('rgt', '<=', $category['rgt'])
            ->pluck('id');
        }
        
        if ($category->count()) {
            $model = DB::table('order_data as i')
            ->leftJoin('order_type as t', 't.id', '=', 'i.type')
            ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
            ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
            ->where('i.deleted_by', 0)
            ->where('o.'.$time_type, '>', 0)
            ->whereIn('p.category_id', $category)
            ->where('t.type', 1)
            ->groupBy('year')
            ->groupBy('month')
            ->groupBy('i.product_id')
            ->orderBy('month', 'asc')
            ->selectRaw('p.name product_name,p.spec product_spec,i.product_id,p.category_id,SUM(i.'.$amount_type.' * i.price) money,SUM(i.'.$amount_type.') amount,FROM_UNIXTIME(o.'.$time_type.',"%Y") year,FROM_UNIXTIME(o.'.$time_type.',"%c") month');
            // 客户圈权限
            if ($query['whereIn']) {
                foreach ($query['whereIn'] as $key => $whereIn) {
                    if ($whereIn) {
                        $model->whereIn($key, $whereIn);
                    }
                }
            }
            $rows = $model->get();
            
            $single = array();
            if ($rows->count()) {
                foreach ($rows as $row) {
                    //金额大于0
                    $single['money'][$row['year']][$row['product_id']][$row['month']] += $row['money'];
                    $single['money2'][$row['year']] += $row['money'];
                    $single['amount'][$row['year']][$row['product_id']][$row['month']] += $row['amount'];
                    $single['name'][$row['product_id']] = $row['product_name'];
                    $single['spec'][$row['product_id']] = $row['product_spec'];

                    $single['year'][$row['year']]['money'][$row['product_id']] += $row['money'];
                    $single['year'][$row['year']]['amount'][$row['product_id']] += $row['amount'];
                }
            }
        }
        
        if (is_array($single['year'])) {
            asort($single['year']);
        }

        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'single'    => $single,
            'categorys' => $categorys,
            'select'    => $selects,
            'query'     => $query,
            'assess'    => $assess,
        ));
    }

    //单品涨跌分析
    public function increaseAction()
    {
        //筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        // 获得GET数据
        $category_id = Input::get('category_id', 0);
        $selects['select']['category_id'] = $category_id;
        
        // 获取品类
        $categorys = ProductCategory::orderBy('lft', 'asc')
        ->where('status', 1)
        ->where('type', 1)
        ->get()->toNested();

        if ($category_id) {
            $category = $categorys[$category_id];
            $category = DB::table('product_category')
            ->where('lft', '>=', $category['lft'])
            ->where('rgt', '<=', $category['rgt'])
            ->pluck('id');
        }

        //往前4个月
        $month1 = date("Y-m", strtotime("-3 month"));
        //往前3个月
        $month2 = date("Y-m", strtotime("-2 month"));
        //提前2个月
        $month3 = date("Y-m", strtotime("-2 month"));
        $month4 = date("Y-m", strtotime("-1 month"));
        
        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->where('i.deleted_by', 0)
        ->where('o.'.$time_type, '>', 0)
        ->whereRaw("FROM_UNIXTIME(o.$time_type,'%Y-%m') BETWEEN '$month1' AND '$month2'")
        ->where('t.type', 1)
        ->groupBy('p.id')
        ->selectRaw('i.product_id,SUM(i.'.$amount_type.' * i.price) money');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        
        if ($category_id) {
            $model->whereIn('p.category_id', $category);
        }

        $rows['a'] = $model->get();
        $rows['a'] = array_by($rows['a'], 'product_id');
        
        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->where('i.deleted_by', 0)
        ->where('o.'.$time_type, '>', 0)
        ->whereRaw("FROM_UNIXTIME(o.$time_type,'%Y-%m') BETWEEN '$month3' AND '$month4'")
        ->where('t.type', 1)
        ->groupBy('p.id')
        ->selectRaw('i.product_id,SUM(i.'.$amount_type.' * i.price) money');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }

        if ($category_id) {
            $model->whereIn('p.category_id', $category);
        }

        $rows['b'] = $model->get();
        $rows['b'] = array_by($rows['b'], 'product_id');

        $money2 = $money1 = array();
        if ($rows) {
            foreach ($rows['b'] as $product_id => $value) {
                $_temp = $value['money'] - $rows['a'][$product_id]['money'];
                if ($rows['a'][$product_id]['money'] <> 0) {
                    $temp = number_format($_temp/$rows['a'][$product_id]['money'], 2);
                } else {
                    $temp = '0.00';
                }
                $money2['pie'][$product_id] = $money1['pie'][$product_id] = $temp;
                $money2['money'][$product_id] = $money1['money'][$product_id] = [$_temp, $value['money'], $rows['a'][$product_id]['money']];
            }
        }
        unset($rows);
        
        $products = DB::table('product AS p')
        ->leftJoin('product_category AS b', 'b.id', '=', 'p.category_id')
        ->where('p.status', 1)
        ->where('b.type', 1)
        ->groupBy('p.id')
        ->orderBy('b.lft', 'ASC')
        ->orderBy('p.sort', 'ASC')
        ->selectRaw('p.*,b.name AS category_name')
        ->get();
        $products = array_by($products);
        
        $temp = [];
        foreach ($products as $product_id => $product) {
            if ($money1['pie'][$product_id]) {
                $temp[] = [$product_id, $money1['pie'][$product_id], $product['name'], $product['spec']];
            }
        }
        
        $pie['a'] = $pie['b'] = $temp;
        
        usort($pie['a'], function ($a, $b) {
            if ($a[1] == $b[1]) {
                return 0;
            }
            return ($a[1] > $b[1]) ? 1 : -1;
        });
        
        usort($pie['b'], function ($a, $b) {
            if ($a[1] == $b[1]) {
                return 0;
            }
            return ($a[1] < $b[1]) ? 1 : -1;
        });
        
        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'categorys' => $categorys,
            'products'  => $products,
            'rows'      => $pie,
            'month1'    => $month1,
            'month2'    => $month2,
            'month3'    => $month3,
            'month4'    => $month4,
            'select'    => $selects,
            'query'     => $query,
            'assess'    => $assess,
        ));
    }

    // 客户涨跌分析
    public function clientsortAction()
    {
        if (Request::method() == 'POST') {
            $post = Input::get();
            SendSms::dispatch([$post['mobile']], $post['text']);
            return $this->json('短信发送成功。', true);
        }

        // 筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        // 往前4个月
        $month1 = date("Y-m", strtotime("-3 month"));
        // 往前3个月
        $month2 = date("Y-m", strtotime("-2 month"));
        // 提前2个月
        $month3 = date("Y-m", strtotime("-2 month"));
        $month4 = date("Y-m", strtotime("-1 month"));

        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->where('i.deleted_by', 0)
        ->where('o.'.$time_type, '>', 0)
        ->whereRaw("FROM_UNIXTIME(o.$time_type,'%Y-%m') BETWEEN '$month1' AND '$month2'")
        ->where('t.type', 1)
        ->groupBy('c.id')
        ->selectRaw('c.nickname,c.mobile,o.customer_id,SUM(i.'.$amount_type.' * i.price) money');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows['a'] = $model->get();
        $rows['a'] = array_by($rows['a'], 'customer_id');

        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('i.deleted_by', 0)
        ->where('o.'.$time_type, '>', 0)
        ->whereRaw("FROM_UNIXTIME(o.$time_type,'%Y-%m') BETWEEN '$month3' AND '$month4'")
        ->where('t.type', 1)
        ->groupBy('c.id')
        ->selectRaw('c.nickname,c.mobile,o.customer_id,SUM(i.'.$amount_type.' * i.price) money');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows['b'] = $model->get();
        $rows['b'] = array_by($rows['b'], 'customer_id');

        $money2 = $money1 = array();
        if ($rows) {
            foreach ($rows['b'] as $customer_id => $value) {
                $temp = $value['money'] - $rows['a'][$customer_id]['money'];
                if ($rows['a'][$customer_id]['money'] <> 0) {
                    $temp = number_format($temp/$rows['a'][$customer_id]['money'], 2);
                } else {
                    $temp = '0.00';
                }
                $money1[$customer_id] = $money2[$customer_id] = $temp;
            }
        }
        unset($rows['b']);
        // 升序
        arsort($money2);
        // 降序
        asort($money1);

        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'rows'      => $rows,
            'month1'    => $month1,
            'month2'    => $month2,
            'month3'    => $month3,
            'month4'    => $month4,
            'money1'    => $money1,
            'money2'    => $money2,
            'select'    => $selects,
            'query'     => $query,
            'assess'    => $assess,
        ));
    }

    // 城市数据分析
    public function cityAction()
    {
        // 当前年月日
        $now_sdate = Input::get('sdate', date("Y").'-01-01');
        $now_edate = Input::get('date', date("Y-m-d"));

        // 减一年年月日
        $last_sdate = date('Y-m-d', strtotime('-1 year', strtotime($now_sdate)));
        $last_edate = date('Y-m-d', strtotime('-1 year', strtotime($now_edate)));

        // 当前年
        $now_year  = date('Y', strtotime($now_sdate));
        $last_year = date('Y', strtotime($last_sdate));

        //筛选专用函数
        $selects = $query = select::head1();

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;
        $selects['select']['sdate'] = $now_sdate;
        $selects['select']['date'] = $now_edate;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        // 读取产品类别
        $categorys = ProductCategory::orderBy('lft', 'asc')
        ->where('status', 1)
        ->where('type', 1)
        ->get()->toNested();

        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('region as r', 'r.id', '=', 'c.city_id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->where('i.deleted_by', 0)
        ->whereRaw('(FROM_UNIXTIME(o.'.$time_type.',"%Y-%m-%d") BETWEEN "'.$last_sdate.'" AND "'.$last_edate.'" or FROM_UNIXTIME(o.'.$time_type.',"%Y-%m-%d") BETWEEN "'.$now_sdate.'" AND "'.$now_edate.'")')
        ->where('t.type', 1)
        ->groupBy('p.category_id')
        ->groupBy('customer.circle_id')
        ->groupBy('year')
        ->orderBy('r.id', 'ASC')
        ->selectRaw('c.city_id,customer.circle_id,o.customer_id,c.nickname company_name,r.name city_name,c.salesman_id,p.category_id,i.product_id,SUM(i.'.$amount_type.' * i.price) money,FROM_UNIXTIME(o.'.$time_type.',"%Y") year');
        
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();

        $single = $info = array();

        if ($rows->count()) {
            foreach ($rows as $v) {
                if ($v['circle_id'] > 0) {
                    // 循环产品
                    $group = $v['circle_id'];
                    $year  = $v['year'];

                    $category_id = $categorys[$v['category_id']]['parent'][0];
                    if ($category_id) {
                        $single[$year]['money'][$group][$category_id] += $v['money'];
                        $single[$year]['cat'][$category_id] += $v['money'];
                        $single[$year]['totalcost'][$group] += $v['money'];
                    }
                }
            }
        }
        unset($rows);

        // 促销计算
        $model = DB::table('promotion as p')
        ->leftJoin('user as c', 'c.id', '=', 'p.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('region as r', 'r.id', '=', 'c.city_id')
        ->leftJoin('user as u', 'u.id', '=', 'c.salesman_id')
        ->where('p.deleted_by', 0)
        ->whereRaw("DATE_FORMAT(p.data_30, '%Y')=?", [$now_year])
        // ->whereRaw('DATE_FORMAT(p.end_at, "%m") <= '.date('m', strtotime($now_date)))
        ->groupBy('customer.circle_id')
        ->groupBy('p.type_id')
        ->selectRaw('DATE_FORMAT(p.end_at, "%m") as aaa, customer.circle_id, p.customer_id, c.salesman_id, c.city_id, SUM(p.data_amount) bd_sum, p.type_id');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $_promotions = $model->get();

        if ($_promotions->count()) {
            foreach ($_promotions as $v) {
                if ($v['circle_id'] > 0) {
                    // 促销分类金额
                    $group = $v['circle_id'];
                    $promotions[$group][$v['type_id']] += $v['bd_sum'];
                    $promotions['all'][$group] += $v['bd_sum'];
                }
            }
        }
        unset($_promotions);

        $now_year_single = $single[$now_year];
        $old_year_single = $single[$last_year];

        // 去年区域销售额和今年金额占比
        if (is_array($now_year_single['totalcost'])) {
            $percentage = array();
            foreach ($now_year_single['totalcost'] as $key => $value) {
                if ($value) {
                    $per = $value - $old_year_single['totalcost'][$key];

                    if ($old_year_single['totalcost'][$key] > 0) {
                        $per = $per/$old_year_single['totalcost'][$key];
                    }
                    $per = number_format($per*100, 2);
                    $percentage[$key] = $per;
                } else {
                    $percentage[$key] = '0.00';
                }
            }
        }

        // 去年同期和今年算占比
        $oldscale = array();

        if ($categorys) {
            foreach ($categorys as $cat) {
                $categoryCode = $cat['id'];

                // 循环去年的区域品类金额
                if (is_array($old_year_single['money'])) {
                    foreach ($old_year_single['money'] as $key => $value) {

                        // 客户代码$key
                        $a = $now_year_single['money'][$key][$categoryCode] - $value[$categoryCode];

                        if ($value[$categoryCode]) {
                            $a = ($a/$value[$categoryCode]);
                        } else {
                            $a = 0;
                        }
                        $oldscale[$key][$categoryCode] = $a;
                    }
                }
            }
        }

        $query = url().'?'.http_build_query($selects['select']);

        $circle_id = $selects['whereIn']['customer.circle_id'];
        $circles = DB::table('customer_circle')->whereIn('id', (array)$circle_id)->pluck('name', 'id');

        return $this->display(array(
            'percentage'      => $percentage,
            'oldscale'        => $oldscale,
            'info'            => $info,
            'old_year_single' => $old_year_single,
            'now_year_single' => $now_year_single,
            'promotions'      => $promotions,
            'categorys'       => $categorys,
            'tag'             => $tag,
            'last_year'       => $last_year,
            'now_year'        => $now_year,
            'select'          => $selects,
            'query'           => $query,
            'assess'          => $assess,
            'circles'         => $circles,
        ));
    }

    // 单区域数据分析
    public function citydataAction()
    {
        // 获得销售员登录名
        $year = Input::get('year');
        $circle_id  = Input::get('circle_id');

        $rows = $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('region as r', 'r.id', '=', 'c.city_id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->where('i.deleted_by', 0)
        ->where('o.pay_time', '>', 0)
        ->where('customer.circle_id', $circle_id)
        ->whereRaw('FROM_UNIXTIME(o.pay_time,"%Y")=?', [$year])
        ->where('t.type', 1)
        ->groupBy('p.category_id')
        ->groupBy('o.customer_id')
        //->groupBy('r.id')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->selectRaw('c.city_id,customer.circle_id,o.customer_id,c.nickname company_name,r.name city_name,c.salesman_id,p.category_id,i.product_id,SUM(i.fact_amount * i.price) money,FROM_UNIXTIME(o.pay_time,"%Y") year,FROM_UNIXTIME(o.pay_time,"%c") month');
        $rows = $model->get();

        if ($rows->count()) {
            $single = array();
            foreach ($rows as $v) {
                if ($v['circle_id'] > 0) {
                    // 循环产品
                    $single['money'][$v['month']][$v['category_en']] += $v['money'];
                    $single['cat'][$v['month']] += $v['money'];
                    $single['category'][$v['category_en']] = $v['category'];
                }
            }
        }

        // 促销计算
        $_promotions = DB::table('promotion as p')
        ->leftJoin('user as c', 'c.id', '=', 'p.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('region as r', 'r.id', '=', 'c.city_id')
        ->where('p.deleted_by', 0)
        ->where('customer.circle_id', $circle_id)
        ->whereRaw("DATE_FORMAT(p.data_30, '%Y')=?", [$year])
        ->groupBy('p.customer_id')
        // ->groupBy('r.id')
        ->groupBy('month')
        ->selectRaw('r.id,c.salesman_id,customer.circle_id,p.customer_id, DATE_FORMAT(p.end_at, "%c") as month, SUM(p.data_amount) as bd_sum, p.type_id')
        ->get();
        
        if ($_promotions->count()) {
            foreach ($_promotions as $v) {
                if ($v['circle_id']) {
                    // 促销分类金额
                    $promotions['month'][$v['month']][$v['type_id']] += $v['bd_sum'];
                    $promotions['month1'][$v['month']] += $v['bd_sum'];
                }
            }
        }
        unset($_promotions);

        $circle = DB::table('customer_circle')->where('id', $circle_id)->first();

        return $this->display(array(
            'single'    => $single,
            'year'      => $year,
            'categorys' => $categorys,
            'promotions'=> $promotions,
            'select'    => $selects,
            'query'     => $query,
            'assess'    => $assess,
            'circle'    => $circle,
        ));
    }

    // 单品客户数据分析
    public function clientAction()
    {
        $now_year = Input::get('year', date('Y'));
        // 获得前一年的年份
        $last_year = $now_year - 1;

        // 筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        // 筛选专用函数
        $selects['select']['year']  = $now_year;
        
        $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->leftJoin('product_category as pc', 'pc.id', '=', 'p.category_id')
        ->where('i.deleted_by', 0)
        ->where('o.add_time', '>', 0)
        ->where('p.id', '>', 0)
        ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y")=?', [$now_year])
        ->where('t.type', 1)
        ->groupBy('year')
        ->groupBy('month')
        ->groupBy('o.customer_id')
        ->groupBy('i.product_id')
        ->orderBy('pc.lft', 'ASC')
        ->orderBy('p.sort', 'ASC')
        //->whereRaw($where)
        ->selectRaw('o.customer_id,c.nickname company_name,p.name product_name,p.spec product_spec,i.product_id,p.category_id,SUM(i.amount * i.price) money,SUM(i.amount) amount,FROM_UNIXTIME(o.add_time,"%Y") year,FROM_UNIXTIME(o.add_time,"%c") month');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();
        
        if ($rows->count()) {
            $single = $info = array();
            foreach ($rows as $v) {
                if ($v['product_id'] > 0) {
                    $month = $v['month'];
                    $product_id = $v['product_id'];

                    $single['product'][$product_id] = $v;
                    $single['category'][$product_id] = $v['category_id'];
                    $single['customer'][$v['customer_id']] = $v['customer_id'];
                    $single['all'][$product_id][$v['customer_id']] = $v['customer_id'];
                    $single['sum'][$product_id][$month][$v['customer_id']] = $v['customer_id'];
                }
            }
        }
        unset($rows);
        $query = url().'?'.http_build_query($selects['select']);
        // 年数组
        $startTime = date('Y', $this->setting['setup_at']);
        $years = range(date('Y'), $startTime);
        $months = range(1, 12);
        return $this->display(array(
            'single' => $single,
            'year'   => $now_year,
            'years'  => $years,
            'months' => $months,
            'select' => $selects,
            'query'  => $query,
            'assess' => $assess,
        ));
    }

    // 客户数据分析
    public function clientdataAction()
    {
        $year = Input::get('year');
        $product_id = Input::get('product_id');
        $query = select::head1();

        $n = date("n", time());

        if ($product_id > 0) {
            $rows = DB::table('order_data as i')
            ->leftJoin('order_type as t', 't.id', '=', 'i.type')
            ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
            ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
            ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
            ->leftJoin('region as r', 'r.id', '=', 'c.city_id')
            ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('product_category as pc', 'pc.id', '=', 'p.category_id')
            ->where('i.deleted_by', 0)
            ->where('o.add_time', '>', 0)
            ->where('p.id', $product_id)
            ->whereRaw('FROM_UNIXTIME(o.add_time,"%Y")=?', [$year])
            //->whereRaw($sql, $params)
            ->where('t.type', 1)
            //->groupBy('month')
            ->groupBy('o.customer_id')
            ->orderBy('pc.lft', 'ASC')
            ->orderBy('p.sort', 'ASC')
            ->selectRaw('r.name city_name,o.customer_id,c.nickname company_name,p.name product_name,p.spec product_spec,i.product_id,p.category_id,SUM(i.amount * i.price) money,SUM(i.amount) amount,FROM_UNIXTIME(o.add_time,"%Y") year,FROM_UNIXTIME(o.add_time,"%c") month, pc.name category_name');
            
            if ($query['whereIn']) {
                foreach ($query['whereIn'] as $key => $whereIn) {
                    if ($whereIn) {
                        $rows->whereIn($key, $whereIn);
                    }
                }
            }

            $rows = $rows->get();

            $single = array();
            foreach ($rows as $key => $value) {
                //如何当前月存在数据
                $customer_id = $value['customer_id'];
                //客户编号公司名称
                $customers[$customer_id] = array(
                    'customer_id' => $value['company_name'],
                    'area' => $value['city_name'],
                );

                if ($value['money'] > 0) {
                    $single['all'][$customer_id] += $value['money'];
                    $single['cat'] = $value['category_name'];
                    $single['name'] = $value['product_name'];
                    $single['spec'] = $value['product_spec'];
                }
                if ($value['month'] == $n) {
                    //筛选本月没有数量的客户
                    $notpurchase[$customer_id] = $value;
                }
            }
        }

        arsort($single['all']);

        return $this->display(array(
            'single'     => $single,
            'years'      => $years,
            'year'       => $year,
            'year_id'    => $year_id,
            'code_id'    => $code_id,
            'month'      => $n,
            'customers'  => $customers,
            'notpurchase'=> $notpurchase,
            'assess'     => $assess,
        ));
    }

    // 客户销售排序
    public function rankingAction()
    {
        // 当前年月日
        $now_sdate = Input::get('sdate', date("Y").'-01-01');
        $now_date = Input::get('date', date("Y-m-d"));
        // 当前选中日期的时间戳
        $now_year_time = strtotime($now_sdate);
        // 减一年时间戳
        $last_year_time = strtotime('-1 year', $now_year_time);
        // 当前年
        $now_year = date('Y', $now_year_time);
        // 减一年
        $last_year = $now_year - 1;

        $stime = date('md', strtotime($now_sdate));
        $etime = date('md', strtotime($now_date));

        // 当前年开始时间
        $now_year_start_time = strtotime($now_year.'-01-01');
        // 减一年开始时间
        $last_year_start_time = strtotime($last_year.'-01-01');

        //筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $time_type = Input::get('time_type', 'add_time');

        $selects['select']['time_type']     = $time_type;
        $selects['select']['sdate']         = $now_sdate;
        $selects['select']['date']          = $now_date;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';

        $tag = Input::get('tag', 'city_id');
        $selects['select']['tag'] = $tag;

        if ($tag == 'city_id') {
            $sql = 'c.city_id';
        }
        if ($tag == 'customer_id') {
            $sql = 'o.customer_id';
        }

        $rows = $model = DB::table('order_data as i')
        ->leftJoin('order_type as t', 't.id', '=', 'i.type')
        ->leftJoin('product as p', 'p.id', '=', 'i.product_id')
        ->leftJoin('order as o', 'o.id', '=', 'i.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->leftJoin('region as r', 'r.id', '=', 'c.city_id')
        ->leftJoin('region as p2', 'p2.id', '=', 'r.parent_id')
        ->where('i.deleted_by', 0)
        ->where('o.status', 1)
        ->where('t.type', 1)
        ->whereRaw('FROM_UNIXTIME(o.'.$time_type.',"%m%d") BETWEEN '.$stime.' AND '.$etime)
        //->whereRaw($where)
        ->groupBy('p.category_id')
        ->groupBy('year')
        ->groupBy($sql)
        ->orderBy('money', 'desc')
        ->selectRaw('p.category_id,c.nickname company_name,p2.name province_name,r.name city_name,o.customer_id,c.salesman_id,customer.circle_id,c.city_id,SUM(i.'.$amount_type.'*i.price) money,FROM_UNIXTIME(o.'.$time_type.',"%Y") year');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();

        // 获取品类
        $product_categorys = ProductCategory::orderBy('lft', 'asc')
        //->where('status', 1)
        ->where('type', 1)
        ->get()->toNested();

        $categorys = $single = $info = array();
        foreach ($rows as $row) {
            $single['info'][$row[$tag]] = $row;
            $single[$row['year']][$row[$tag]] += $row['money'];

            $category_id = $product_categorys[$row['category_id']]['parent'][0];
            if ($category_id) {
                $categorys['title'][$category_id] = $product_categorys[$category_id];
                $categorys['money'][$row['year']][$row[$tag]][$category_id] += $row['money'];
            }
        }
        unset($rows);

        $query = url().'?'.http_build_query($selects['select']);

        // 年数组
        $month = date('m', strtotime($now_date));

        $selects['customer_type'] = CustomerType::orderBy('id', 'asc')
        ->get(['id','name'])
        ->keyBy('id')->toArray();

        $circles = DB::table('customer_circle')->get()->keyBy('id');

        return $this->display(array(
            'info'              => $info,
            'circles'           => $circles,
            'single'            => $single,
            'categorys'         => $categorys,
            'product_categorys' => $product_categorys,
            'tag'               => $tag,
            'month'             => $month,
            'now_year'          => $now_year,
            'last_year'         => $last_year,
            'select'            => $selects,
            'query'             => $query,
            'assess'            => $assess,
        ));
    }

    // 促销分类查询
    public function promotionAction()
    {
        // 获得销售员登录名
        $id       = (int)Input::get('id');
        $tag      = Input::get('tag');
        $category = Input::get('category');

        if ($id <= 0 and empty($tag) and empty($category)) {
            return $this->alert('很抱歉，参数不正确。');
        }

        $category_name = $this->promotion['promotions_category'][$category];
        $category_name = str_replace('促销', '', $category_name);

        //查询类型
        if ($tag == 'salesman_id') {
            $where = 'c.salesman_id='.$id;
        } elseif ($tag == 'city_id') {
            $where = 'c.city_id='.$id;
        } elseif ($tag == 'user_id') {
            $where = 'p.user_id='.$id;
        }

        // 促销计算
        $_promotions = DB::table('promotion as p')
        ->leftJoin('user as c', 'c.id', '=', 'p.customer_id')
        ->where('p.deleted_by', 0)
        ->whereRaw($where)
        ->where('p.type_id', $category)
        ->groupBy('p.id')
        ->orderBy('p.id', 'DESC')
        ->selectRaw('p.step_number,p.start_at,p.end_at,p.data_4,p.data_3,p.type_id,p.product_remark,p.data_5,p.data_18,p.data_19,p.data_amount,p.data_amount1')
        ->get();

        return $this->display(array(
            'promotions'=> $_promotions,
        ));
    }

    // 客户销售类型排行
    public function clienttypeAction()
    {
        // 筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        $amount_type = ($time_type == 'delivery_time') ? 'fact_amount' : 'amount';
        
        $rows = $model = DB::table('order as a')
        ->leftJoin('order_data as b', 'a.id', '=', 'b.order_id')
        ->leftJoin('user as c', 'c.id', '=', 'a.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('b.deleted_by', 0)
        ->where("a.$time_type", '>', 0)
        ->where('a.invoice_type', 3)
        ->groupBy('a.customer_id')
        ->orderBy('money_sum', 'DESC')
        ->selectRaw('SUM(b.price * b.'.$amount_type.') money_sum,c.nickname,c.username,c.post,c.city_id,c.province_id,c.salesman_id,a.customer_id,a.invoice_type');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();

        $list = array();
        foreach ($rows as $k => $v) {
            $list[$v['customer_id']] = $v;
        }
        unset($rows);

        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'select'  => $selects,
            'rows'    => $list,
            'lastYear'=> $lastYear,
            'nowYear' => $nowYear,
            'query'   => $query,
        ));
    }

    /**
     * 新客户分析
     * 计算本年度有订单去年无订单为新客户
     */
    public function newclientAction()
    {
        // 筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $lastYear = date("Y", strtotime("-1 year"));
        $nowYear = date("Y");
        
        $model = DB::table('order AS a')
        ->leftJoin('order_data AS b', 'a.id', '=', 'b.order_id')
        ->leftJoin('user AS c', 'c.id', '=', 'a.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('b.deleted_by', 0)
        ->where('a.add_time', '>', 0)
        ->groupBy('a.customer_id')
        ->groupBy('year')
        ->orderBy('money_sum', 'DESC')
        ->selectRaw('SUM(b.price * b.fact_amount) AS money_sum,FROM_UNIXTIME(a.add_time,"%Y") AS year,c.nickname company_name,c.username number,c.post,c.city_id,c.province_id,c.salesman_id,a.customer_id');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();

        $list = array();
        foreach ($rows as $k => $v) {
            $list[$v['year']][$v['customer_id']] = $v;
        }
        unset($rows);

        $query = url().'?'.http_build_query($selects['select']);

        $customer_type = DB::table('customer_type')->get();
        $customer_type = array_by($customer_type);

        return $this->display(array(
            'select'      => $selects,
            'rows'        => $list,
            'lastYear'    => $lastYear,
            'nowYear'     => $nowYear,
            'query'       => $query,
            'customer_type' => $customer_type,
        ));
    }

    /**
     * 连续3个月未进货的客户
     */
    public function stockmonthAction()
    {
        // 筛选专用函数
        $selects = $query = select::head1();
        $where = $selects['where'];

        $time_type = Input::get('time_type', 'add_time');
        $selects['select']['time_type'] = $time_type;

        // 去年
        $year1 = date('Y', strtotime('-1 year'));
        // 今年
        $year2 = date('Y');

        $amount_type = 'fact_amount';

        // 退后三个月
        $start_at = strtotime("-3 month");
        // 今天时间戳
        $end_at = time();

        $model = DB::table('order as o')
        ->leftJoin('user as c', 'c.id', '=', 'o.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->whereRaw("o.$time_type BETWEEN $start_at AND $end_at")
        ->where('c.status', 1)
        ->groupBy('o.customer_id');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get(['c.id','c.nickname']);
        $rows = array_by($rows);

        $model = DB::table('user as c')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->where('c.status', 1)
        ->where('group_id', 2);
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $customers = $model->get(['c.id','c.username','c.nickname','c.post']);
        $customers = array_by($customers);
        foreach ($rows as $key => $row) {
            unset($customers[$key]);
        }

        $customer_ids = [];
        foreach ($customers as $customer) {
            $customer_ids[] = $customer['id'];
        }

        $rows = $model = DB::table('order_data')
        ->leftJoin('order_type', 'order_type.id', '=', 'order_data.type')
        ->leftJoin('order', 'order.id', '=', 'order_data.order_id')
        ->leftJoin('customer', 'order.customer_id', '=', 'customer.id')
        ->leftJoin('user', 'user.id', '=', 'customer.user_id')
        ->whereIn('customer.id', $customer_ids)
        ->where('order_data.deleted_by', 0)
        ->whereRaw("FROM_UNIXTIME(order.$time_type,'%Y') BETWEEN '".$year1."' AND '".$year2."'")
        ->where('order_type.type', 1)
        ->where('order.status', 1)
        ->groupBy('year')
        ->groupBy('order.customer_id')
        ->selectRaw('order.customer_id,SUM(order_data.'.$amount_type.' * order_data.price) money,FROM_UNIXTIME(order.'.$time_type.',"%Y") year');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $rows = $model->get();
        
        $data = [];
        foreach ($rows as $row) {
            $data[$row['year']][$row['customer_id']] += $row['money'];
        }

        $query = url().'?'.http_build_query($selects['select']);
        return $this->display(array(
            'year1'  => $year1,
            'year2'  => $year2,
            'data'   => $data,
            'rows'   => $customers,
            'select' => $selects,
            'query'  => $query,
        ));
    }

    /**
     * 回款记录
     */
    public function receivableAction()
    {
        $selects = $query = select::head1();
        $where = $selects['where'];
        $selects['select']['year'] = Input::get('year', date('Y'));

        $model = DB::table('customer_receivable as receivable')
        ->LeftJoin('user as c', 'c.id', '=', 'receivable.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'c.id')
        ->LeftJoin('customer_contract as cc', 'cc.customer_id', '=', 'receivable.customer_id')
        ->whereRaw('FROM_UNIXTIME(pay_date,"%Y") = ?', [$selects['select']['year']])
        //->whereRaw($where)
        ->groupBy('receivable.customer_id')
        ->groupBy('month')
        ->selectRaw('c.nickname as customer_name, c.username as customer_code,receivable.id, receivable.customer_id, FROM_UNIXTIME(receivable.pay_date,"%c") as month, SUM(receivable.pay_money) as money,cc.month_task');
        // 客户圈权限
        if ($query['whereIn']) {
            foreach ($query['whereIn'] as $key => $whereIn) {
                if ($whereIn) {
                    $model->whereIn($key, $whereIn);
                }
            }
        }
        $temps = $model->get();

        $rows = [];
        foreach ($temps as $temp) {
            $month_task = json_decode($temp['month_task'], true);
            $rows[$temp['customer_id']]['customer_name'] = $temp['customer_name'];
            $rows[$temp['customer_id']]['customer_code'] = $temp['customer_code'];
            $rows[$temp['customer_id']]['money'][$temp['month']] += $temp['money'];
            $rows[$temp['customer_id']]['task'] = (array)$month_task;
        }

        $query = url().'?'.http_build_query($selects['select']);

        return $this->display(array(
            'rows'   => $rows,
            'select' => $selects,
            'query'  => $query,
        ));
    }
}
