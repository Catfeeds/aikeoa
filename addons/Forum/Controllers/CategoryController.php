<?php namespace Aike\Forum\Controllers;

use DB;
use Input;

use Aike\Index\Controllers\DefaultController;

class CategoryController extends DefaultController
{
    // 论坛类别
    public function indexAction()
    {
        // 更新排序
        if ($post = $this->post('sort')) {
            foreach ($post as $k => $v) {
                $data['sort'] = $v;
                DB::table('forum')->where('id', $k)->update($data);
            }
        }

        $rows = DB::table('forum')
        ->where('state', '1')
        ->get(['id', 'name']);
        
        if (Input::get('data_type') == 'json') {
            return $this->json($rows, true);
        }
        return $this->display([
            'rows' => $rows,
        ]);
    }

    // 论坛列别编辑
    public function addAction()
    {
        $id = (int)Input::get('id');

        if ($post = $this->post()) {
            if (empty($post['name'])) {
                return $this->error('类别名称必须填写。');
            }

            unset($post['past_parent_id']);

            if ($post['id'] > 0) {
                DB::table('forum')->where('id', $post['id'])->update($post);
            } else {
                DB::table('forum')->insert($post);
            }
            return $this->success('index', '类别更新成功。');
        }

        $row = DB::table('forum')->where('id', $id)->first();

        return $this->display(array(
            'row'  => $row,
        ));
    }

    // 论坛列别删除
    public function deleteAction()
    {
        if ($id = Input::get('id')) {
            DB::table('forum')->where('id', $id)->delete();
            DB::table('forum_post')->where('forum_id', $id)->delete();
            return $this->success('index', '类别删除成功。');
        }
        return $this->error('类别删除失败。');
    }
}
