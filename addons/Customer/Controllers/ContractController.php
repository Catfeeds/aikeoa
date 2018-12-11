<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;
use Validator;
use Config;

use Aike\User\User;
use Aike\Customer\CustomerType;
use Aike\Customer\Contract;
use Aike\Product\ProductCategory;
use Aike\Index\Region;
use select;

use Aike\Index\Controllers\DefaultController;

class ContractController extends DefaultController
{
    // 合同列表
    public function indexAction()
    {
        // 筛选客户
        $filter = select::customer();

        $columns = [
            ['text','user.nickname','客户名称'],
            ['text','user.username','客户代码'],
        ];

        if ($filter['role_type'] == 'salesman') {
            $columns[] = ['region','user.province_id','客户地区'];
            $columns[] = ['status','user.status','客户状态'];
            $columns[] = ['post','user.post','客户类型'];
        }

        if ($filter['role_type'] == 'all') {
            $columns[] = ['owner','user.salesman_id','负责人'];
            $columns[] = ['region','user.province_id','客户地区'];
            $columns[] = ['status','user.status','客户状态'];
            $columns[] = ['post','user.post','客户类型'];
        }

        $search = search_form([], $columns);
        $query  = $search['query'];

        $model = Contract::LeftJoin('user', 'user.id', '=', 'customer_contract.customer_id')
        ->orderBy('end_time', 'desc');

        if ($filter['where']) {
            foreach ($filter['where'] as $key => $where) {
                $model->where($key, $where);
            }
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->select(['customer_contract.*'])
        ->paginate()->appends($query);

        $types = CustomerType::orderBy('id', 'asc')->get(['id','title as name'])->keyBy('id');

        $region = Region::pluck('name', 'id');

        return $this->display(array(
            'rows'    => $rows,
            'types'   => $types,
            'search'  => $search,
            'owners'  => $filter['owner'],
            'region'  => $region,
        ));
    }

    public function addAction()
    {
        // 需要转换json的字段
        $jsonDataFields = array('month_task');
        $checkboxFields = array('task_type','reward_type');

        // 数据集合处理
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $items = [];
            if (is_array($gets['price_item'])) {
                foreach ($gets['price_item'] as $item) {
                    if ($item['price'] > 0) {
                        $items[$item['product_id']] = $item;
                    }
                }
            }
            $gets['price_item'] = json_encode($items);

            // 类别处理
            $gets['category_item'] = json_encode($gets['category_item']);

            // 处理月度任务
            $gets['month_task'] = json_encode($gets['month_task']);

            $gets['start_time'] = strtotime($gets['start_time']);
            $gets['end_time'] = strtotime($gets['end_time']);

            $_customer = $gets['customer'];
            unset($gets['customer']);

            $gets['customer_id'] = $_customer['id'];

            if ($gets['id'] > 0) {
                DB::table('customer_contract')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('customer_contract')->insert($gets);
            }

            if ($_customer['id']) {
                DB::table('customer')->where('id', $_customer['id'])->update($_customer);
            } else {
                DB::table('customer')->insert($_customer);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }

        $customer_id = Input::get('customer_id');
        $id          = Input::get('id');

        $model = DB::table('customer_contract');

        if ($customer_id) {
            $model->where('customer_id', $customer_id);
        } elseif ($id) {
            $model->where('id', $id);
        }
        $row = $model->first();

        // 读取内部说明
        $customer = DB::table('customer')->where('id', $row['customer_id'])->first();

        if ($customer_id) {
            $customer['id'] = $customer_id;
        }

        $row['month_task']    = (array)json_decode($row['month_task'], true);
        $row['category_item'] = (array)json_decode($row['category_item'], true);

        // 计算季度，月份季度任务
        $row['quarter_task'] = [];
        if (is_array($row['month_task'])) {
            foreach ($row['month_task'] as $k => $v) {
                $i = ceil($k / 3);
                $row['quarter_task'][$i] += (int)$v;
            }
        }

        $categorys = ProductCategory::where('type', 1)->orderBy('lft', 'asc')->get()->toNested();

        // 获取全部产品
        $products = DB::table('product')->get(array('id', DB::raw('concat(`name`, " - ", `spec`) as text')));

        if ($row['price_item']) {
            $price_item = json_decode($row['price_item'], true);
            $price_items = array();
            foreach ($products as $key => $item) {
                if ($price_item[$item['id']] > 0) {
                    $price_items[] = [
                        'product_id'   => $item['id'],
                        'product_text' => $item['text'],
                        'price'        => $price_item[$item['id']]['price'],
                        'rate'         => $price_item[$item['id']]['rate'],
                    ];
                }
            }
        }

        $customer['user'] = DB::table('user')->where('id', $customer['user_id'])->first();

        return $this->display(array(
            'row'           => $row,
            'categorys'     => $categorys,
            'price_items'   => json_encode($price_items, JSON_UNESCAPED_UNICODE),
            'customer'      => $customer,
            'content_title' => $row->title,
        ));
    }

    // 合同查看
    public function viewAction()
    {
        // 需要转换json的字段
        $jsonDataFields = array('category_item','channel_item','reward_product_item','reward_task','quarter_task','month_task');
        $checkboxFields = array('task_type','reward_type');

        $customer_id = Input::get('customer_id');
        $id          = Input::get('id');

        $model = DB::table('customer_contract');

        if ($customer_id) {
            $model->where('customer_id', $customer_id);
        } elseif ($id) {
            $model->where('id', $id);
        }

        $row = $model->first();

        foreach ((array)$row as $k => $v) {
            if (in_array($k, $jsonDataFields)) {
                $row[$k] = json_decode($v, true);
            }
        }

        // 计算季度，月份季度任务
        $row['quarter_task'] = [];
        if (is_array($row['month_task'])) {
            foreach ($row['month_task'] as $k => $v) {
                $i = ceil($k/3);
                $row['quarter_task'][$i] += $v;
            }
        }
        
        // 读取内部说明
        $customer = DB::table('customer')->where('id', $customer_id)->first();

        $categorys = ProductCategory::orderBy('lft', 'asc')->get(['id', 'parent_id', 'name'])->toNested('name');

        // 获取全部产品
        $products = DB::table('product')->get(array('id', DB::raw('concat(`name`," - ",`spec`) as text')));

        if ($row['price_item']) {
            $price_item = json_decode($row['price_item'], true);
            $price_items = array();
            foreach ($products as $key => $item) {
                if ($price_item[$item['id']] > 0) {
                    $price_items[] = array('product_id'=>$item['id'],'product_text'=>$item['text'],'price'=>$price_item[$item['id']]);
                }
            }
        }

        return $this->display(array(
            'row'          => $row,
            'categorys'    => $categorys,
            'price_items'  => $price_items,
            'customer'     => $customer,
            'contract'     => $contract,
        ));
    }

    // 删除合同
    public function delete()
    {
        $id = Input::get('id', 0);
        if ($id > 0) {
            DB::table('customer_contract')->where('id', $id)->delete();
            return $this->success('index', '合同删除成功。');
        }
    }

    // 任务管理 (未使用)
    public function task()
    {
        // 筛选专用函数
        $selects = select::head();
        $where = $selects['where'];

        $_year = date("Y", time());

        $contract = \Wang_Default_Model::fetchArray("
            SELECT c.id, c.customer_id, u.username, c.month_task, u.nickname
            FROM `#__customer_contract` AS c
            LEFT JOIN `user` AS u ON c.customer_id = u.id
            WHERE $where AND c.customer_year = $_year
            ORDER BY c.id DESC
        ");

        if (is_array($contract)) {
            $task = $users = $info = array();
            foreach ($contract as $v) {
                $customers = unserialize($v['customers']);
                $task[$v['customer_id']] = unserialize($v['month_task']);
                $info[$v['customer_id']] = $v['company_name'];
                $users[$v['customer_id']] = $v['username'];
            }
        }

        $salesdata = \Wang_Default_Model::fetchArray("SELECT `id`, `year`, `month`, `category`, SUM(`money`) AS `money`, `client_name`, `user_id`  FROM erp_sale_data WHERE `year` = '$_year' GROUP BY `month`, `user_id` ORDER BY id DESC ");
        if (is_array($salesdata)) {
            $rs = array();
            foreach ($salesdata as $v) {
                $rs[$v['user_id']][$v['month']] += $v['money'];
            }
        }

        $m = array();
        if (is_array($task)) {
            foreach ($task as $key => $month) {
                foreach ($month as $k => $v) {
                    $t = $v *10000;
                    $month = $rs[$key][$k];
                    if ($v > 0) {
                        $percent = ($month/$t) * 100;
                        $m[$key]['percent'][$k] = number_format($percent, 2).'%';
                    } else {
                        $m[$key]['percent'][$k] = '0.00%';
                    }
                    $m[$key]['money'][$k] = $month ? $month : 0;
                    $m[$key]['quarter'][$k] = $v ? $t : 0;
                }
            }
        }

        $query = url().'?'.http_build_query($selects['select']);

        $this->view->set(array(
            'res'     => $res,
            'm'       => $m,
            'info'    => $info,
            'query'   => $query,
            'selects' => $selects,
        ));
        $this->view->display();
    }

    // 单个任务查看 (未使用)
    public function taskdata()
    {
        $_year = date("Y", time());
        
        $user_id = Input::get('user_id', 0);
        if ($user_id <= 0) {
            return $this->error('用户编号不正确，无法完成访问。');
        }

        $contract = \Wang_Default_Model::fetchRecord("SELECT `id`, `quarter_task` FROM `#__customer_contract` WHERE `customer_id` = '$user_id' ");

        if (is_array($contract)) {
            if ($contract['id']) {
                $month = unserialize($contract['month_task']);
                $customer_name = $contract['customer_name'];
                $quarter = unserialize($contract['quarter_task']);
            }
        }

        // 季度任务翻转
        $_quarter = array();
        foreach ($quarter as $k => $v) {
            foreach ($v as $k2 => $v2) {
                $_quarter[$k2][$k] = $v2;
            }
        }

        $salesdata = \Wang_Default_Model::fetchArray("SELECT `quarterly`, `id`, `year`, `month`, `category`, `category_en`, SUM(`money`) as `money`, `client_name`, `user_id`  FROM sale_data WHERE `year` = '$_year' AND `user_id` = '$user_id' GROUP BY `category_en`, `quarterly`, `user_id` ORDER BY id DESC ");

        if (is_array($salesdata)) {
            $rs = array();
            foreach ($salesdata as $v) {
                $rs[strtolower($v['category_en'])][$v['quarterly']] += $v['money'];
            }
        }
        $m = array();
        foreach ($_quarter as $k => $v) {
            foreach ($v as $k2 => $v2) {
                $t = $v2 * 10000;
                $e = $rs[$k][$k2];

                if ($v2 > 0) {
                    $percent = ($e/$t) * 100;
                    $m[$k]['percent'][$k2] = number_format($percent, 2).'%';
                } else {
                    $m[$k]['percent'][$k2] = '0.00%';
                }
                $m[$k]['money'][$k2] = $e ? $e : 0;
                $m[$k]['quarter'][$k2] = $t ? $t : 0;
            }
        }

        // $categorys = 分类数据;
        $categorys = array_flip($categorys);

        $this->view->set(array(
            'res'       => $res,
            'm'         => $m,
            '_quarter'  => $_quarter,
            'categorys' => $categorys,
            'query'     => $query,
            'selects'   => $selects,
        ));
        $this->view->display();
    }

    // 客户分析 (未使用)
    public function analysis()
    {
        //筛选专用函数
        $selects = select::head();
        $where = $selects['where'];
        $selects['select']['type_id'] = Input::get('type_id', 1);

        $title = array(
            //本年有销售去年没有销售的客户
            1 => array('title' => '新客户', 'desc' => '新客户'),
            //红色警戒客户
            2 => array('title' => '去年和今年同期销售下降20%以上', 'desc' => '红色警戒客户'),
            //蓝色警戒客户
            3 => array('title' => '去年和今年同期销售下降20%以内', 'desc' => '蓝色警戒客户'),
        );
        $selects['type'] = $title;

        //本年
        $year = date("Y", time());
        //去年
        $last_year = $year - 1;

        $month_list = array(
            date("Yn", strtotime("-1 months")),
            date("Yn", strtotime("-2 months")),
            date("Yn", strtotime("-3 months"))
        );

        $now_month = date("n", time());

        // 下面是曾经的
        $rs = \Wang_Default_Model::fetchArray("
            SELECT `d`.`user_id`, `d`.`client_name`, `d`.`client_company_name`, `d`.`month`, `d`.`sales_id`, `d`.`sales_name`, `d`.`category`, `d`.`category_en`, SUM(`d`.`money`) AS `money`, `d`.`year`
            FROM `sale_data` AS `d`
            LEFT JOIN user AS u ON d.user_id = u.id
            WHERE `d`.`year` IN('$year', '$last_year') AND `d`.`month` < '$now_month' AND $where
            GROUP BY `d`.`user_id`, `d`.`category_en`, `d`.`year`, `d`.`month`
            ORDER BY `d`.`id` DESC
        ");
        
        $single = array();
        if ($rs) {
            foreach ($rs as $value) {
                $customer_id = $value['user_id'];
                // 今年的数据
                $single['new'][$customer_id][$value['year']] += $value['money'];
                $single['data'][$customer_id] += $value['money'];

                // 客户信息和区域信息
                $single['area'][$customer_id] = $value;
                $single['area_id'][$customer_id] = $value['sales_id'];
                $single[$value['category_en']] = $value['category'];
              
                // 分年度的销售信息
                $single[$value['year']]['money'][$customer_id ][$value['category_en']] += $value['money'];
                $single[$value['year']]['cat'][$value['category_en']] += $value['money'];
                $single[$value['year']]['totalcost'][$customer_id ] += $value['money'];
            }
        }
        unset($rs);

        // 获取新客户列表
        $new_dealer = array();
        if ($single['new']) {
            foreach ($single['new'] as $key => $value) {
                //只有本年
                if (!isset($single['new'][$key][$last_year]) && isset($single['new'][$key][$year])) {
                    $new_dealer[$key] = $single['new'][$key][$year];
                }
            }
        }
        // 去年区域销售额和今年金额占比
        $percentage = array();
        $percentage_int = array();

        if ($single[$year]['totalcost']) {
            foreach ($single[$year]['totalcost'] as $key => $value) {
                if ($value && $single[$last_year]['totalcost'][$key]) {
                    $per = $value - $single[$last_year]['totalcost'][$key];
                    $per = $per_int = $per/$single[$last_year]['totalcost'][$key];

                    $per = number_format($per*100, 2);
                    $per_int = number_format($per_int*100, 0);
                    $percentage[$key] = $per;
                    $percentage_int[$key] = $per_int;
                } else {
                    $percentage[$key] = '0.00';
                    $percentage_int[$key] = '0';
                }
            }
        }

        // 配置文件预设分类
        // $categorys = 分类数据;

        // 去年同期和今年算占比
        $oldscale = array();
        foreach ($categorys as $cat) {
            if ($single[$last_year]['money']) {
                foreach ($single[$last_year]['money'] as $key => $value) {
                    // 客户代码$key
                    if ($value[$cat]) {
                        $a = $single[$year]['money'][$key][$cat] - $value[$cat];
                        $a = $a/$value[$cat];
                        $a = number_format($a*100, 2);
                        $oldscale[$key][$cat] = $a;
                    } else {
                        $oldscale[$key][$cat] = '0.00';
                    }
                }
            }
        }

        if (is_array($single['data'])) {
            // 总销售额
            $total_sales = array_sum($single['data']);
            // 多少个客户
            $new_dealer_count = sizeof($new_dealer);
            // 销售额
            $data_count = array_sum($new_dealer);
            // 新客户占总销售比例
            $new_percent = round(($data_count/$total_sales)*100, 2);
        }

        // 促销计算
        $ps = \Wang_Default_Model::fetchArray("
            SELECT `p`.`user_id`, `p`.`ah`, SUM(`p`.`bd`) AS `bd`, `p`.`ar`, `p`.`am`
            FROM `promotions` AS `p`
            LEFT JOIN user AS u ON p.user_id = u.id
            WHERE $where GROUP BY `p`.`am`, `p`.`ar`
            ORDER BY `p`.`id` DESC
        ");

        if (is_array($ps)) {
            foreach ($ps as $key => $value) {
                // 促销分类金额
                $_ps['area'][$value['user_id']][$value['ar']] += $value['bd'];
                $_ps['area1'][$value['user_id']] += $value['bd'];
            }
        }
        unset($ps);

        $list = array();
        $type = $selects['select']['type_id'];
        if ($single['area']) {
            foreach ($single['area'] as $key => $val) {
                if ($type == 1 && isset($new_dealer[$key])) {
                    $list[$key] = true;
                } elseif ($type == 2 && $percentage_int[$key] < -20 && $percentage_int[$key] != 0) {
                    $list[$key] = true;
                } elseif ($type == 3 && $percentage_int[$key] >= -20 && $percentage_int[$key] < 0) {
                    $list[$key] = true;
                }
            }
        }
        $categorys = array_flip($categorys);
        $query = url().'?'.http_build_query($selects['select']);

        $this->view->set(array(
            'list' => $list,
            '_ps' => $_ps,
            'year' => $year,
            'type_id' => $selects['select']['type_id'],
            'single' => $single,
            'new_dealer_count' => $new_dealer_count,
            'new_percent' => $new_percent,
            'percentage' => $percentage,
            'oldscale' => $oldscale,
            'percentage' => $percentage,
            'categorys'  => $categorys,
            'query' => $query,
            'selects' => $selects,
        ));
        $this->view->display();
    }

    /**
     * 导出客户合同 (未使用)
     */
    public function export()
    {
        // 筛选专用函数
        $selects = select::head();
        $where = $selects['where'];

        $select_key = array('number'=>'','category_id'=>'','time_type'=>'created','year'=>date('Y'));
        foreach ($select_key as $k => $v) {
            $selects['select'][$k] = Input::get($k, $v);
        }
        extract($selects['select'], EXTR_PREFIX_ALL, 'select');

        if ($post = $this->post('export')) {
            $clients = $model->from('customer c', 'c.user_id,c.nickname company_name,cc.product,cc.price')
            ->join('customer_contract cc', 'c.user_id = cc.customer_id')
            ->group('c.user_id')
            ->where($where)
            ->where('cc.year=?', $select_year)
            ->select();

            $products = $model->from('product a', 'a.*')
            ->join('product_category b', 'b.id = a.category_id')
            ->order('b.sort ASC')->order('a.sort ASC')
            ->select();

            $rows = array();
            $head = array('客户名称');
            $i = 0;
            foreach ($customers as $customer) {
                $product_arr = explode(',', $customer['product_item']);
                $price_arr   = json_decode($customer['price_item'], true);
                $rows[$i][] = $customer['company_name'];

                $j = 1;
                foreach ($products as $product) {
                    $head[$j] = $product['name'].'-'.$product['spec'];

                    $product_id = $product['id'];

                    if (in_array($product_id, $product_arr)) {
                        $rows[$i][$j] = 0;

                        if (isset($price_arr[$product_id])) {
                            $rows[$i][$j] = $price_arr[$product_id];
                        }
                    } else {
                        $rows[$i][$j] = '';
                    }
                    $j++;
                }
                $i++;
            }
            getExcel($head, $rows, '客户合同');
        }

        $query = url::action().'?'.http_build_query($selects['select']);

        $order_config = config('order');
        $order_category = $order_config['type'];
        $selects['years'] = range(date('Y')-1, date('Y')+1);
        $this->view->set(array(
            'query'   => $query,
            'selects' => $selects,
            'money'   => $money,
            'order'   => $order,
            'customer'  => $customer,
            'order_category' => $order_category,
        ));
        $this->view->display();
    }
}
