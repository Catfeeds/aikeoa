<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;

use Aike\Customer\Circle;
use Aike\Customer\Customer;
use Aike\User\User;

use Aike\Index\Controllers\DefaultController;

class CircleController extends DefaultController
{
    public $permission = ['dialog'];

    // 方面列表
    public function aspectAction()
    {
        $layer = 1;

        // 更新排序
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            foreach ($sorts as $id => $sort) {
                $model = Circle::find($id);
                $model->sort = $sort;
                $model->save();
            }
        }

        $search = search_form([
            'referer' => 1,
        ]);

        $rows = Circle::where('layer', $layer)->orderBy('sort', 'asc')->get();
        return $this->display([
            'layer' => $layer,
            'rows'  => $rows,
        ], 'index');
    }

    // 区域
    public function regionAction()
    {
        $layer = 2;

        // 更新排序
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            foreach ($sorts as $id => $sort) {
                $model = Circle::find($id);
                $model->sort = $sort;
                $model->save();
            }
        }

        $search = search_form([
            'referer' => 1,
        ]);

        $rows = Circle::with('parent')->where('layer', $layer)->orderBy('sort', 'asc')->get();
        return $this->display([
            'layer' => $layer,
            'rows'  => $rows,
        ], 'index');
    }

    // 圈列表
    public function indexAction()
    {
        $layer = 3;

        // 更新排序
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            foreach ($sorts as $id => $sort) {
                $model = Circle::find($id);
                $model->sort = $sort;
                $model->save();
            }
        }

        $search = search_form([
            'referer' => 1,
        ]);

        $rows = Circle::with('parent')->where('layer', $layer)->orderBy('sort', 'asc')->get();
        return $this->display([
            'layer' => $layer,
            'rows'  => $rows,
        ], 'index');
    }

    // 添加
    public function addAction()
    {
        $gets = Input::get();
        $row = Circle::findOrNew($gets['id']);

        if (Request::method() == 'POST') {
            if (empty($gets['name'])) {
                return $this->error('类别名称必须填写。');
            }

            $row->fill($gets);
            $row->save();

            $owner_id = $gets['owner_user_id'];
            if ($gets['layer'] == 3 && $owner_id > 0) {
                $customers = Customer::where('circle_id', $row->id)->get(['id', 'user_id']);
                // 更新客户表负责人
                Customer::whereIn('id', $customers->pluck('id'))->update(['owner_id' => $owner_id]);
                // 更新用户表业务员
                User::whereIn('id', $customers->pluck('user_id'))->update(['salesman_id' => $owner_id]);
            }
            return $this->success('index', '保存成功。');
        }

        $rows = Circle::where('layer', $gets['layer'] - 1)->orderBy('sort', 'asc')->get();
        $row['layer'] = $gets['layer'];

        return $this->display([
            'row'  => $row,
            'rows' => $rows,
        ]);
    }

    public function dialogAction()
    {
        $search = search_form([
            'advanced' => '',
            'prefix'   => 0,
            'layer'    => 3,
            'offset'   => 0,
            'sort'     => '',
            'order'    => '',
            'limit'    => 25
        ], [
            ['text','name','名称'],
        ]);

        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = DB::table('customer_circle')
            ->where('layer', $query['layer']);

            // 客户圈权限
            $circle = \select::circleCustomer();
            if ($circle['circleIn']) {
                $model->whereIn('id', $circle['circleIn']);
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
            //$json['total'] = $model->count();
            $rows = $model
            //->skip($query['offset'])->take($query['limit'])
            ->get();

            //$json['rows'] = $rows;
            return response()->json($rows);
        }
        return $this->render([
            'search' => $search,
            'query'  => $query,
        ]);
    }


    // 删除
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('编号不正确无法显示。');
        }

        $count = Circle::where('parent_id', $id)->count();
        if ($count) {
            return $this->error('存在下级无法删除。');
        }

        Circle::where('id', $id)->delete();

        return $this->success('index', '删除成功');
    }
}
