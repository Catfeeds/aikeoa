<?php namespace Aike\Customer\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\Customer\Customer;
use Aike\Customer\Receivable;

use Aike\Index\Controllers\DefaultController;
use select;

class ReceivableController extends DefaultController
{
    // 回款列表
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
            $columns[] = ['post','user.post','客户类型'];
        }

        if ($filter['role_type'] == 'all') {
            $columns[] = ['owner','user.salesman_id','负责人'];
            $columns[] = ['region','user.province_id','客户地区'];
            $columns[] = ['post','user.post','客户类型'];
        }

        $search = search_form([], $columns);
        $query  = $search['query'];

        $model = Receivable::LeftJoin('user', 'user.id', '=', 'customer_receivable.customer_id');

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

        $rows = $model->with('customer.user')
        
        ->select(['customer_receivable.*'])
        ->orderBy('customer_receivable.id', 'desc')
        ->paginate()
        ->appends($query);

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
        ]);
    }

    // 新建回款
    public function createAction()
    {
        $id  = Input::get('id');

        $row = Receivable::findOrNew($id);
        $row->customer_id = Input::get('customer_id', $row->customer_id);

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'customer_id' => 'required',
                'pay_date'    => 'required',
                'pay_money'   => 'required',
            ];

            $gets['pay_date'] = strtotime($gets['pay_date']);

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

    // 显示回款
    public function showAction()
    {
        $id = Input::get('id');
        $row = Receivable::find($id);

        return $this->render([
            'row' => $row,
        ]);
    }

    // 删除回款
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            Receivable::whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }
}
