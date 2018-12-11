<?php namespace Aike\Promotion\Controllers;

use Exception;

use DB;
use Input;
use Request;
use Validator;
use select;
use Aike\Promotion\PromotionCash as Cash;
use Aike\Promotion\PromotionCashData as CashData;
use Aike\Promotion\Promotion;

use Aike\Index\Controllers\DefaultController;

class CashController extends DefaultController
{
    public $permission = ['promotion'];

    // 兑现列表
    public function indexAction()
    {
        // 筛选客户
        $filter = select::customer();

        $columns = [
            ['text','number','促销编号'],
            ['promotion.type','type_id','促销类型'],
            ['step','step_number','流程步骤'],
            ['cash','data_18','兑现方式'],
            ['material','material_id','素材'],
            ['text','user.nickname','客户名称'],
            ['text','promotion.customer_id','客户编号'],
        ];

        if ($filter['role_type'] == 'salesman') {
            $columns[] = ['region','user.province_id','客户地区'];
        }

        if ($filter['role_type'] == 'all') {
            $columns[] = ['owner','user.salesman_id','负责人'];
            $columns[] = ['region','user.province_id','客户地区'];
        }

        $search = search_form([
            'referer' => 1
        ], $columns);

        $query  = $search['query'];

        $model = Cash::LeftJoin('promotion', 'promotion.id', '=', 'promotion_cash.promotion_id')
        ->LeftJoin('user', 'user.id', '=', 'promotion.customer_id');

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

        $rows = $model->select(['promotion_cash.*', 'user.nickname'])
        ->orderBy('promotion_cash.id', 'desc')
        ->paginate()
        ->appends($query);

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
        ]);
    }

    // 新建兑现
    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            $rules = [
                'promotion_id'      => 'required',
                'date'              => 'required',
                'sn'                => 'required',
                'user'              => 'required',
                'rows'              => 'required|array',
                'rows.*.product_id' => 'required',
                'rows.*.quantity'   => 'required',
                'rows.*.price'      => 'required',
                'rows.*.type_id'    => 'required',
            ];

            $attributes = [
                'promotion_id' => '所属促销',
                'date'         => '兑现日期',
                'sn'           => '单据编号',
                'user'         => '制单人',
                'rows'         => '产品列表',
                'product_id'   => '产品数量',
                'type_id'      => '兑现类型',
            ];

            $v = Validator::make($gets, $rules, [], $attributes);
            if ($v->fails()) {
                return $this->json(join('<br>', $v->errors()->all()));
            }

            // 主表数据
            $master = [
                'promotion_id' => $gets['promotion_id'],
                'date'         => $gets['date'],
                'sn'           => $gets['sn'],
                'user'         => $gets['user'],
            ];

            // 事务开始
            DB::beginTransaction();
            try {
                $cashId = Cash::insertGetId($master);
                $rows = $gets['rows'];
                foreach ($rows as $row) {
                    $row['cash_id'] = $cashId;
                    CashData::insert($row);
                }
                // 事务提交
                DB::commit();

                // 生成客户订单
                Cash::makeCustomerOrder($cashId);

            } catch (Exception $e) {
                // 事务回滚
                DB::rollback();
                return $this->json($e->getMessage());
            }

            return $this->json('促销兑现订单已经生成。', true);
        }

        $models = [
            ['name' => "id", 'hidden' => true],
            ['name' => 'option', 'label' => '&nbsp;', 'formatter' => 'options', 'width' => 60, 'sortable' => false, 'align' => 'center'],
            ['name' => "product_id", 'hidden' => true, 'label' => '产品ID'],
            ['name' => "product_name", 'width' => 280, 'label' => '产品', 'rules' => ['required'=>true], 'sortable' => false, 'editable' => true],
            ['name' => "quantity", 'label' => '数量', 'width' => 100, 'rules' => ['required' => true, 'minValue' => 1,'integer' => true], 'formatter' => 'integer', 'sortable' => false, 'editable' => true, 'align' => 'right'],
            ['name' => "price", 'label' => '单价', 'width' => 100, 'rules' => ['required' => true], 'sortable' => false, 'editable' => true, 'align' => 'right'],
            ['name' => "type_id", 'formatter' => 'dropdown', 'width' => 140, 'label' => '兑现类型', 'rules' => ['required'=>true], 'sortable' => false, 'editable' => true],
            ['name' => "remark", 'label' => '备注', 'width' => 160, 'sortable' => false, 'editable' => true]
        ];

        $sn = 'CXDX-'.date('ymdHs').Cash::count();

        $promotion_id = Input::get('promotion_id');
        $promotion = Promotion::find($promotion_id);

        $order_type = DB::table('order_type')->where('parent_id', '>', 0)
        ->orderBy('sort', 'asc')
        ->get(['id', 'title as text']);

        return $this->render([
            'models'    => $models,
            'order_type' => $order_type,
            'promotion' => $promotion,
            'sn'        => $sn,
        ]);
    }

    // 编辑兑现
    public function editAction()
    {
        $id  = Input::get('id');

        $row = Cash::findOrNew($id);
        $row->promotion_id = Input::get('promotion_id', $row->promotion_id);

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'promotion_id' => 'required',
                'date'         => 'required',
                'money'        => 'required',
            ];

            $gets['date'] = strtotime($gets['date']);

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $v->errors()->all();
            }
            $row->fill($gets)->save();
            
            return $this->json('reload', true);
        }

        return $this->render([
            'row' => $row,
        ]);
    }

    // 促销兑现
    public function promotionAction()
    {
        $promotion_id = Input::get('promotion_id');
        $rows = Cash::where('promotion_id', $promotion_id)->get();

        return $this->render([
            'rows' => $rows,
        ]);
    }

    // 显示兑现
    public function showAction()
    {
        $id = Input::get('id');
        $row = Cash::find($id);

        return $this->render([
            'row' => $row,
        ]);
    }

    // 删除兑现
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            Cash::whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }
}
