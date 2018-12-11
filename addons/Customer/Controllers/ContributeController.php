<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\Customer\Contribute;
use Aike\Customer\Contact;

use Aike\Index\Controllers\DefaultController;
use select;

class ContributeController extends DefaultController
{
    // 客户贡献记录
    public function indexAction()
    {
        $search = search_form([], [
            ['text','customer_contribute.subject','主题'],
            ['date','customer_contribute.date','日期'],
            ['text','customer_contribute.description','描述'],
        ]);
        $query  = $search['query'];

        $model = Contribute::with('customer.user', 'contact.user')
        ->LeftJoin('user', 'user.id', '=', 'customer_contribute.customer_id');

        // 显示自己负责的客户联系人
        $filter = select::customer();

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

        $rows = $model->select(['customer_contribute.*'])
        ->paginate();

        return $this->display([
            'rows'    => $rows,
            'search'  => $search,
        ]);
    }

    // 新建客户贡献记录
    public function createAction()
    {
        $id = Input::get('id');
        $row = Contribute::findOrNew($id);

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'contact_id' => 'required',
            ];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            $contact = Contact::where('id', $gets['contact_id'])->first();
            $row->customer_id = $contact->customer_id;
            $row->fill($gets)->save();

            return $this->success('index', '恭喜你，操作成功。');
        }

        return $this->display([
            'row' => $row,
        ]);
    }

    // 删除客户贡献记录
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            Contribute::whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }
}
