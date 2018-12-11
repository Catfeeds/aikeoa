<?php namespace Aike\Customer\Controllers;

use DB;
use Input;
use Request;

use Aike\Index\Controllers\DefaultController;

class TypeController extends DefaultController
{
    // 客户类型
    public function indexAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get();
            foreach ($gets as $k => $v) {
                $data['sort'] = $v;
                DB::table('customer_type')->where('id', $k)->update($data);
            }
        }

        $search = search_form([
            'status'  => 1,
            'referer' => 1,
        ], [
            ['text','customer_type.name','名称'],
        ]);

        $query = $search['query'];

        $rows = DB::table('customer_type')->orderBy('sort', 'asc')->get();
        return $this->display(array(
            'rows'   => $rows,
            'query'  => $query,
            'search' => $search,
        ));
    }

    // 添加分类
    public function addAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $post = Input::get();
            if (empty($post['name'])) {
                return $this->error('名称必须填写。');
            }

            if ($post['id'] > 0) {
                DB::table('customer_type')->where('id', $post['id'])->update($post);
            } else {
                DB::table('customer_type')->insert($post);
            }
            return $this->success('index', '保存成功。');
        }

        $row = DB::table('customer_type')->where('id', $id)->first();
        return $this->display(array(
            'row' => $row,
        ));
    }

    // 删除产品类别
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('编号不正确无法显示。');
        }
        $row = DB::table('customer_type')->where('id', $id)->delete();
        return $this->success('index', '删除成功。');
    }
}
