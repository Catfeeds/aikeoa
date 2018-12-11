<?php namespace Aike\Scene\Controllers;

use DB;
use Input;
use Auth;
use Paginator;
use Request;

use Aike\Index\Controllers\DefaultController;

class ImproveController extends DefaultController
{
    public function indexAction()
    {
        $page = Input::get('page', '1');
        $category_id = Input::get('category_id', '0');
        $query['category_id'] = $category_id;
        
        $model = DB::table('improve as m')
        ->leftJoin('improve_attachment as a', 'a.id', '=', 'm.attachment')
        ->orderBy('m.id', 'DESC')
        ->selectRaw('m.*,a.path,a.name as attach_file');

        // 检查访问权限
        if ($this->access['index'] == 4) {
        } else {
            $model->whereRaw('(FIND_IN_SET('.Auth::id().', m.access_user_id) OR FIND_IN_SET('.Auth::user()->role_id.', m.access_role_id) OR FIND_IN_SET('.Auth::user()->department_id.', m.access_department_id))');
        }
        if ($category_id > 0) {
            $model->where('m.category_id', $category_id);
        }
        $count = $model->count('m.id');

        $rows = $model->forPage($page, 15)->get();
        $rows = Paginator::make($rows, $count, 15)->appends($query);

        $categorys = DB::table('improve_category')->where('state', 1)->orderBy('sort', 'asc')->get();

        return $this->display([
            'rows'        => $rows,
            'category_id' => $category_id,
            'categorys'   => $categorys,
        ]);
    }

    public function addAction()
    {
        $id = (int)Input::get('id');
        
        $row = DB::table('improve')->where('id', $id)->first();
        
        //更新数据
        if ($post = $this->post()) {
            if (empty($post['title'])) {
                return $this->error('来源必须不能为空。');
            }

            if (empty($post['content'])) {
                return $this->error('描述不能为空。');
            }

            if (count($post['attachment']) <> 1) {
                return $this->error('附件数量限定1个。');
            }

            $post['content'] = $_POST['content'];
            $post['attachment'] = join(',', (array)$post['attachment']);

            // 更新数据库
            if ($post['id']) {
                DB::table('improve')->where('id', $post['id'])->update($post);
            } else {
                DB::table('improve')->insert($post);
            }

            // 设置附件为已经使用
            attachment_store('improve_attachment', $_POST['attachment']);

            return $this->success('index', '数据保存成功。');
        }

        $attachList = attachment_edit('improve_attachment', $row['attachment'], 'improve');

        $categorys = DB::table('improve_category')->where('state', 1)->orderBy('sort', 'asc')->get();
        return $this->display(array(
            'categorys'  => $categorys,
            'attachList' => $attachList,
            'row'        => $row,
        ));
    }

    public function viewAction()
    {
        $id = (int)Input::get('id');

        // 模型实例
        $row = DB::table('improve')->where('id', $id)->first();
        if (empty($row)) {
            return $this->error('数据不存在。');
        }

        // 信件附件
        $attachList = attachment_view('improve_attachment', $row['attachment']);

        return $this->display(array(
            'attachList' => $attachList,
            'row'        => $row,
        ));
    }

    public function deleteAction()
    {
        $id = (int)Input::get('id');
        
        $row = DB::table('improve')->where('id', $id)->first();

        if (empty($row)) {
            return $this->error('没有数据。');
        }

        // 删除附件
        attachment_delete('improve_attachment', $row['attachment']);
        
        DB::table('improve')->where('id', $id)->delete();
        return $this->success('category', '数据删除成功。');
    }

    // 市场巡查类别
    public function categoryAction()
    {
        //更新排序
        if ($post = $this->post('sort')) {
            foreach ($post as $k => $v) {
                $data['sort'] = $v;
                DB::table('improve_category')->where('id', $k)->update($data);
            }
        }

        $rows = DB::table('improve_category')
        ->where('state', 1)
        ->orderBy('sort', 'asc')
        ->get();
        
        return $this->display([
            'rows' => $rows,
        ]);
    }

    //添加市场类别
    public function category_addAction()
    {
        $id = (int)Input::get('id');

        if ($post = $this->post()) {
            if (empty($post['name'])) {
                return $this->error('很抱歉，类别名称必须填写。');
            }

            if ($post['id'] > 0) {
                DB::table('improve_category')->where('id', $post['id'])->update($post);
            } else {
                DB::table('improve_category')->insert($post);
            }
            return $this->success('category', '恭喜你，类别更新成功。');
        }

        $row = DB::table('improve_category')->where('id', $id)->first();

        return $this->display(array(
            'row' => $row,
        ));
    }

    // 删除市场类别
    public function category_deleteAction()
    {
        if ($id = Input::get('id')) {
            DB::table('improve_category')->where('id', $id)->delete();
            return $this->success('category', '恭喜你，类别删除成功。');
        }
        return $this->error('很抱歉，编号不正确。');
    }
}
