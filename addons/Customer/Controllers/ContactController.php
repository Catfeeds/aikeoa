<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\Customer\Customer;
use Aike\Customer\Contact;

use Aike\Index\Controllers\DefaultController;
use select;

class ContactController extends DefaultController
{
    public $permission = ['dialog'];

    // 联系人列表
    public function indexAction()
    {
        // 筛选专用函数
        // $filter = select::customer();
        $circle = select::circleCustomer();

        $columns = [
            ['text','contact.nickname','姓名'],
            ['date','contact.birthday','生日'],
            ['user.gender','contact.gender','性别'],
            ['contact.type','customer_contact.type','类型'],
            ['contact.post','contact.post','职位'],
            ['text','user.nickname','客户名称'],
        ];

        $columns = array_merge($columns, $circle['columns']);

        /*
        if ($filter['role_type'] == 'salesman') {
            $columns[] = ['text','user.nickname','客户名称'];
            $columns[] = ['owner','user.salesman_id','负责人'];
        }

        if ($filter['role_type'] == 'all') {
            $columns[] = ['text','user.nickname','客户名称'];
            $columns[] = ['owner','user.salesman_id','负责人'];
        }
        */

        $search = search_form([
            'referer' => 1,
        ], $columns);

        $query = $search['query'];

        $model = Contact::LeftJoin('user as contact', 'contact.id', '=', 'customer_contact.user_id')
        ->LeftJoin('customer', 'customer.id', '=', 'customer_contact.customer_id')
        ->LeftJoin('user', 'user.id', '=', 'customer_contact.customer_id');

        if ($query['order'] && $query['srot']) {
            $model->orderBy($query['srot'], $query['order']);
        } else {
            $model->orderBy('customer_contact.customer_id', 'asc');
        }

        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }
        
        /*
        if ($filter['where']) {
            foreach ($filter['where'] as $key => $where) {
                $model->where($key, $where);
            }
        }
        */

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->select(['customer_contact.*'])
        ->with('user')->paginate()->appends($query);

        return $this->display([
            'rows'    => $rows,
            'search'  => $search,
            'owners'  => $filter['owner'],
        ]);
    }

    // 新建客户联系人
    public function createAction()
    {
        $id = Input::get('id');
        $row = Contact::findOrNew($id);

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $gets['user']['group_id'] = 3;

            $rules = [
                'contact.customer_id' => 'required',
                'user.nickname'       => 'required',
            ];

            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $v->errors()->all();
            }

            if ($row->user) {
                $user = $row->user;
            } else {
                $user = new User;
                $user->username = uniqid('cc_');
            }
            $user->fill($gets['user'])->save();

            $row->user_id = $user->id;
            $row->fill($gets['contact'])->save();

            return $this->json('reload', true);
        }

        return $this->render([
            'row' => $row,
        ]);
    }


    // 显示客户联系人
    public function showAction()
    {
        $id = Input::get('id');
        $row = Contact::find($id);

        return $this->render([
            'row' => $row,
        ]);
    }

    // 客户联系人列表
    public function dialogAction()
    {
        $search = search_form([
            'advanced' => '',
            'sort'     => '',
            'order'    => '',
            'offset'   => 0,
            'limit'    => 10,
        ], [
            ['text','contact.nickname','联系人姓名'],
            ['text','user.nickname','客户名称'],
            ['text','user.username','客户代码'],
            ['text','user.id','客户编号'],
            ['region','user.province_id','客户地址'],
        ]);
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = Contact::LeftJoin('user as contact', 'contact.id', '=', 'customer_contact.user_id')
            ->LeftJoin('user', 'user.id', '=', 'customer_contact.customer_id')
            ->where('contact.group_id', 3)
            ->select([
                'customer_contact.id',
                'contact.status',
                'contact.nickname',
                'user.nickname as customer_name'
            ]);

            // 只显示自己负责的客户联系人
            $filter = select::customer();
            if ($filter['where']) {
                foreach ($filter['where'] as $key => $where) {
                    $model->where($key, $where);
                }
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
            ->get();

            $json['rows'] = $rows;
            return response()->json($json);
        }
        $get = Input::get();

        return $this->render(array(
            'search' => $search,
            'get'    => $get,
        ));
    }

    // 删除客户联系人
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            Contact::whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }
}
