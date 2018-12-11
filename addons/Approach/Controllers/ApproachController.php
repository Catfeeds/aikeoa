<?php namespace Aike\Approach\Controllers;

use Aike\Index\Attachment;

use Aike\Approach\Approach;
use Aike\Customer\CustomerType;
use Aike\Approach\ApproachData;
use Aike\Approach\ApproachAddress;

use select;
use DB;
use Input;
use Request;

use Aike\Index\Controllers\DefaultController;

class ApproachController extends DefaultController
{
    public $permission = ['dialog', 'detail', 'history', 'history_goods', 'history_shop'];

    /**
     * 促销列表
     */
    public function indexAction($tpl = 'index')
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','approach.sn','进店编号'],
            ['text','approach.data_1','零售全称'],
            ['approach.type','type_id','进店类型'],
            ['step','approach.step_number','流程步骤'],
            ['text','user.nickname','客户名称'],
            ['text','approach.customer_id','客户ID'],
        ];

        $columns = array_merge($columns, $circle['columns']);
 
        $search = search_form([
            'status'   => 0,
            'referer'  => 1,
        ], $columns);

        $query = $search['query'];

        $model = Approach::stepAt()
        ->leftJoin('user', 'user.id', '=', 'approach.customer_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'user.id');

        if ($tpl == 'index' || $tpl == 'count') {
            $model->where('deleted_by', 0);
        } else {
            $model->where('deleted_by', '>', 0);
        }

        if ($query['order'] && $query['srot']) {
            $model->orderBy($query['srot'], $query['order']);
        } else {
            $model->orderBy('approach.created_at', 'desc');
        }

        $model->where('approach.status', $query['status']);

        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $_steps = Approach::getSteps();
        $steps = [];
        foreach ($_steps as $step) {
            $steps[] = ['id' => $step->number, 'name' => $step->number.'.'.$step->name];
        }

        $rows = $model->select(['approach.*','user.nickname'])
        ->paginate()->appends($query);

        $tabs = [
            'name'  => 'status',
            'items' => [
                ['id' => '0','name' => '待审'],
                ['id' => '1','name' => '已审']
            ],
        ];

        foreach ($rows as $key => $row) {
            $step = get_step_status($row);
            $row['step']['edit'] = $step['edit'];
            $rows->put($key, $row);
        }

        // 返回json
        if (Request::wantsJson()) {
            $json = $rows->toArray();
            $json['search'] = ['steps' => $steps];
            return response()->json($json);
        }

        return $this->display([
            'search'    => $search,
            'query'     => $query,
            'rows'      => $rows,
            'tabs'      => $tabs,
            'steps'     => $steps,
            'tpl'       => $tpl,
        ], $tpl);
    }

    // 促销汇总
    public function countAction()
    {
        return $this->indexAction('count');
    }

    // 回收站列表
    public function trashAction()
    {
        return $this->indexAction('trash');
    }

    /**
     * 显示促销
     */
    public function showAction()
    {
        $id = Input::get('id');

        // 获取促销
        $approach = Approach::find($id);
        $approach->step_number = $approach->step_number > 0 ? $approach->step_number : 1;

        $step_number = $approach->step_number;

        // 获取流程
        $step = Approach::getStep($step_number);
        $approach->step = $step;

        // 获取流程办理权限
        $step = get_step_status($approach);

        // 获取产品列表
        $approach['approach_data'] = ApproachData::where('approach_id', $id)
        ->orderBy('approach_data.created_at', 'asc')
        ->get();

        // 获取地址列表
        $approach['approach_address'] = ApproachAddress::where('approach_id', $id)
        ->orderBy('approach_address.created_at', 'asc')
        ->get();

        $customer = DB::table('user')->find($approach->customer_id);
        $salesman = DB::table('user')->find($customer['salesman_id']);
        $approach['owner'] = $salesman['nickname'];

        $form = new \App\Form($approach, 'approach');

        $attach = Attachment::view($approach['attachment']);

        return $this->display([
            'form'     => $form,
            'approach' => $approach,
            'attach'   => $attach,
            'step'     => $step,
        ]);
    }

    /**
     * 打印促销
     */
    public function printAction()
    {
        $id = Input::get('id');

        // 获取促销
        $approach = Approach::find($id);
        $approach->step_number = $approach->step_number > 0 ? $approach->step_number : 1;

        $step_number = $approach->step_number;

        // 获取流程
        $step = Approach::getStep($step_number);
        $approach->step = $step;

        // 获取流程办理权限
        $step = get_step_status($approach);

        // 获取产品列表
        $approach['approach_data'] = ApproachData::where('approach_id', $id)
        ->orderBy('approach_data.created_at', 'asc')
        ->get();

        // 获取地址列表
        $approach['approach_address'] = ApproachAddress::where('approach_id', $id)
        ->orderBy('approach_address.created_at', 'asc')
        ->get();

        $customer = DB::table('user')->find($approach->customer_id);
        $salesman = DB::table('user')->find($customer['salesman_id']);
        $approach['owner'] = $salesman['nickname'];

        $form = new \App\Form($approach, 'approach', 0, 1);

        $this->layout = 'layouts.print';

        return $this->display([
            'form'       => $form,
            'approach'   => $approach,
            'attachment' => $attachment,
            'step'       => $step,
        ]);
    }

    /**
     * 新增促销
     */
    public function createAction()
    {
        $id = Input::get('id');

        // 获取促销
        $approach = Approach::findOrNew($id);

        if ($approach['step_number']) {
        } else {
            $approach['step_number'] = 1;
            $approach['customer_id'] = auth()->id();
            $customer = DB::table('user')->find($approach['customer_id']);
            $salesman = DB::table('user')->find($customer['salesman_id']);
            $approach['owner'] = $salesman['nickname'];
        }
        
        // 获取流程
        $step = Approach::getStep($approach['step_number']);
        $approach->step = $step;

        // 获取流程办理权限
        $step = get_step_status($approach);

        // 获取产品列表
        $approach['approach_data'] = ApproachData::where('approach_id', $id)
        ->orderBy('approach_data.created_at', 'asc')
        ->get();

        // 获取地址列表
        $approach['approach_address'] = ApproachAddress::where('approach_id', $id)
        ->orderBy('approach_address.created_at', 'asc')
        ->get();

        // 获取草稿附件
        $attachment['draft'] = Attachment::draft();

        $attach = Attachment::edit($approach['attachment']);

        $form = new \App\Form($approach, 'approach', 1);

        return $this->display([
            'form'          => $form,
            'approach'      => $approach,
            'attach'        => $attach,
            'step'          => $step,
        ], 'create');
    }

    /**
     * 单品明细
     */
    public function detailAction()
    {
        $id = Input::get('id');

        // 获取产品列表
        $rows = ApproachData::where('approach_id', $id)
        ->orderBy('approach_data.created_at', 'asc')
        ->get();

        return $this->render([
            'rows' => $rows,
        ]);
    }

    /**
     * 客户进店历史
     */
    public function historyAction()
    {
        $search = search_form([
            'customer_id' => 0,
        ]);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = Approach::where('customer_id', $query['customer_id'])
            ->orderBy('id', 'desc');

            // 排序方式
            if ($query['sort'] && $query['order']) {
                //$model->orderBy($query['sort'], $query['order']);
            }

            $rows = $model->get();
            return response()->json($rows);
        }

        return $this->render([
            'rows'  => $rows,
            'query' => $query,
        ]);
    }

    /**
     * 进店统计
     */
    public function reportAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['second2','approach.created_at','创建日期'],
        ];

        $columns = array_merge($columns, $circle['columns']);
        $search = search_form([], $columns);

        $query = $search['query'];

        if (Request::wantsJson()) {
            $model = DB::table('approach')
            ->leftJoin('customer', 'customer.user_id', '=', 'approach.customer_id')
            ->leftJoin('user as customer_user', 'customer_user.id', '=', 'customer.user_id')
            ->leftJoin('customer_circle', 'customer.circle_id', '=', 'customer_circle.id')
            ->where('approach.deleted_by', 0)
            ->groupBy('approach.customer_id')
            ->selectRaw('customer_circle.name as circle_name,customer.circle_id, SUM(approach.data_24) a, SUM(approach.data_38) b');

            if ($circle['whereIn']) {
                foreach ($circle['whereIn'] as $key => $where) {
                    $model->whereIn($key, $where);
                }
            }
    
            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }
            
            $rows = $model->get();
            return json_encode($rows);
        }

        $types = CustomerType::orderBy('id', 'asc')
        ->get(['id','name'])
        ->keyBy('id')->toArray();

        return $this->display([
            'search' => $search,
            'query'  => $query,
            'types'  => $types,
        ]);
    }

    /**
     * 单品查询
     */
    public function history_goodsAction()
    {
        $search = search_form([
            'approach_id' => 0,
        ]);
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = ApproachData::where('approach_id', $query['approach_id'])
            ->orderBy('approach_data.created_at', 'desc');

            // 排序方式
            if ($query['sort'] && $query['order']) {
                //$model->orderBy($query['sort'], $query['order']);
            }

            $rows = $model->get();
            return response()->json($rows);
        }
    }

    /**
     * 店名查询
     */
    public function history_shopAction()
    {
        $search = search_form([
            'approach_id' => 0,
        ]);
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = ApproachAddress::where('approach_id', $query['approach_id'])
            ->orderBy('approach_address.created_at', 'desc');

            // 排序方式
            if ($query['sort'] && $query['order']) {
                //$model->orderBy($query['sort'], $query['order']);
            }

            $rows = $model->get();
            return response()->json($rows);
        }
    }

    /**
     * 编辑促销
     */
    public function editAction()
    {
        return $this->createAction();
    }

    // 回收删除
    public function deleteAction()
    {
        $id = Input::get('id');
        $id = is_array($id) ? $id : [$id];

        $rows = Approach::whereIn('id', $id)->get();
        foreach ($rows as $row) {
            $data['deleted_at'] = time();
            $data['deleted_by'] = auth()->id();
            Approach::where('id', $row['id'])->update($data);
        }
        return $this->success('index', ' 删除成功。');
    }

    // 回收恢复
    public function restoreAction()
    {
        $id = Input::get('id');
        $id = is_array($id) ? $id : [$id];

        $rows = Approach::whereIn('id', $id)->get();
        foreach ($rows as $row) {
            $data['deleted_at'] = 0;
            $data['deleted_by'] = 0;
            Approach::where('id', $row['id'])->update($data);
        }
        return $this->success('trash', '恢复成功。');
    }

    // 销毁删除
    public function destroyAction()
    {
        $id = Input::get('id');
        $id = is_array($id) ? $id : [$id];

        $rows = Approach::whereIn('id', $id)->get();
        foreach ($rows as $row) {
            Attachment::delete($row['attachment']);
        }

        Approach::whereIn('id', $id)->delete();
        ApproachData::whereIn('approach_id', $id)->delete();
        ApproachAddress::whereIn('approach_id', $id)->delete();

        return $this->success('trash', '销毁成功。');
    }

    // 获取进店列表
    public function dialogAction()
    {
        $search = search_form([
            'customer_id' => '',
            'sort'        => '',
            'order'       => '',
            'offset'      => 0,
            'limit'       => 10,
        ], [
            ['text','approach.sn','促销编号'],
            ['text','user.nickname','客户名称'],
            ['text','approach.id','编号'],
        ]);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = Approach::leftJoin('user', 'user.id', '=', 'approach.customer_id');

            if ($query['customer_id']) {
                $model->where('user.id', $query['customer_id']);
            }

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy($query['sort'], $query['order']);
            }

            // 搜索条件
            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $json['total'] = $model->count();
            $rows = $model->skip($query['offset'])->take($query['limit'])
            ->get(['*', 'number as text', 'user.nickname']);

            $json['rows'] = $rows;
            return response()->json($json);
        }
        $get = Input::get();

        return $this->render([
            'search' => $search,
            'get'    => $get,
        ]);
    }

    // 导入进店表
    public function importAction()
    {
        // 上传文件
        if (Request::method() == 'POST') {
            $file = Input::file('userfile');
            
            if ($file->isValid()) {
                set_time_limit(0);
        
                $handle = fopen($file->getPathName(), 'r');
                $start = microtime(true);

                $customers = User::where('role_id', 2)
                ->get(['id', 'username'])
                ->keyBy('username');

                $rows = [];

                while ($data = fgets($handle, 10000)) {
                    $data = mb_convert_encoding($data, 'utf8', 'gb2312');

                    $data = explode("\t", $data);

                    // 检查文件头
                    if ($data[0] == '月份') {
                        continue;
                    }

                    if ($data[0]) {
                        // 客户编号
                        $user = $customers[trim($data[11])];
                        if (empty($user)) {
                            return $this->error($data[11].' 客户代码不存在。');
                        }

                        $row = [];

                        $row['customer_id'] = $user['id'];

                        // 客户名称
                        //$row['al'] = $data[12];

                        // 促销开始日期
                        $row['start_at'] = $data[13];

                        // 促销结束日期
                        $row['end_at'] = $data[14];

                        // 促销对象
                        $row['data_4'] = $data[15];

                        // 促销目标
                        $row['data_3'] = $data[16];

                        // 促销类型
                        $type = ['消费' => 1,'渠道' => 2, '经销' => 3];
                        $row['type_id'] = $type[trim($data[17])];

                        // 促销单品
                        $row['product_remark'] = $data[18];

                        // 促销方法
                        $row['data_5'] = $data[19];

                        // 支持方式
                        $row['data_10'] = $data[20];

                        // 兑现方式
                        $row['data_18'] = $data[21] == '现配' ? 1 : 2;

                        // 核销依据
                        $row['data_19'] = $data[21];

                        $row['data_amount_pl'] = $data[22];

                        $row['data_amount_tc'] = $data[23];

                        $row['data_amount_pc'] = $data[24];

                        $row['data_amount_ppc'] = $data[25];

                        $row['data_amount_hz'] = $data[26];

                        $row['data_amount_ql'] = $data[27];

                        // $row['data_amount_cl'] = $data[28];

                        $row['data_amount'] = $data[29];

                        // 实际兑现费用
                        /*
                        $row['bm'] = $data[30];

                        $row['bl'] = $data[31];
                        $row['bn'] = $data[32];
                        $row['bo'] = $data[33];
                        */

                        // 流程编号
                        $row['step_number'] = $data[34];

                        // 素材状态
                        $row['material_id'] = $data[35];

                        //$row['br'] = $data[36];

                        if ($row['step_number'] == 9) {
                            $row['status'] = 1;
                        }

                        // 促销编号
                        $row['number'] = $data[37];

                        $row['created_at'] = strtotime($data[13]);
                        $row['created_by'] = 1;
                        
                        $rows[] = $row;
                    }
                }

                $update = $insert = 0;
                foreach ($rows as $sql) {
                    $r = DB::table('promotion')->where('number', $sql['number'])->first();
                    if ($r['id']) {
                        $update ++;
                        DB::table('promotion')->where('id', $r['id'])->update($sql);
                    } else {
                        $insert ++;
                        DB::table('promotion')->insert($sql);
                    }
                }
                $end = microtime(true) - $start;
                return $this->success('import', '恭喜你，更新成功，耗时: '.$end.'，新建：'.$insert.'，更新：'.$update);
            } else {
                return $this->error('对不起，文件上传失败，错误代码为: '.$file->getError());
            }
        }
        return $this->display();
    }
}
