<?php namespace Aike\Inspect\Controllers;

use DB;
use Input;
use Request;
use URL;

use Aike\Index\Controllers\DefaultController;

class MartController extends DefaultController
{
    public $permission = ['category', 'list'];

    public function indexAction()
    {
        $categorys = DB::table('inspect_mart_category')->orderBy('sort', 'asc')->get();

        $type_id = Input::get('type_id', $categorys[0]['id']);
        $page_id = Input::get('page', '1');
        
        $query_key = array('type_id' => $type_id);
        foreach ($query_key as $k => $v) {
            $query['select'][$k] = Input::get($k, $v);
        }
        extract($query['select'], EXTR_PREFIX_ALL, 'q');
        
        $model = DB::table('inspect_mart as m')
        ->leftJoin('inspect_attachment as a', 'a.id', '=', 'm.attachment')
        ->leftJoin('user as c', 'm.customer_id', '=', 'c.id')
        ->orderBy('m.id', 'DESC')
        ->select(['m.*','a.path','a.name as attach_file','c.nickname as company_name']);

        $model->where('m.type', $q_type_id);
        
        $rows = $model->paginate()->appends($query['select']);
        
        return $this->display(array(
            'categorys' => $categorys,
            'type_id'   => $type_id,
            'rows'      => $rows,
            'links'     => $links,
        ));
    }

    public function listAction()
    {
        $category_id = Input::get('category_id', 1);

        $items = DB::table('inspect_mart as im')
        ->LeftJoin('inspect_attachment as ia', 'ia.id', '=', 'im.attachment')
        ->orderBy('im.id', 'desc')
        ->where('im.type', $category_id)
        ->select(['im.*', 'ia.path', 'ia.name as file'])
        ->paginate();

        foreach ($items as $i => $item) {
            $item['upload_url'] = URL::to('/uploads');
            $file = upload_path().'/'.$item['path'].'/'.$item['file'];
            // 生成缩略图
            thumb($file, 80, 80);
            $items[$i] = $item;
        }
        return response()->json($items);
    }

    public function addAction()
    {
        $id = (int)Input::get('id');
        $row = DB::table('inspect_mart')->where('id', $id)->first();

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
            if ($post['id'] > 0) {
                DB::table('inspect_mart')->where('id', $id)->update($post);
            } else {
                DB::table('inspect_mart')->insert($post);
            }

            // 设置附件为已经使用
            attachment_store('inspect_attachment', $_POST['attachment']);

            return $this->success('index', '数据保存成功。');
        }

        $attachList = attachment_edit('inspect_attachment', $row['attachment'], 'mart');
        
        $categorys = DB::table('inspect_mart_category')->where('state', 1)->get();

        return $this->display(array(
            'attachList' => $attachList,
            'row'        => $row,
            'categorys'  => $categorys,
        ));
    }

    public function viewAction()
    {
        $id = (int)Input::get('id');

        // 模型实例
        $row = DB::table('inspect_mart')->where('id', $id)->first();
        if (empty($row)) {
            return $this->error('数据不存在。');
        }

        // 信件附件
        $attachList = attachment_view('inspect_attachment', $row['attachment']);

        // 视图设置
        return $this->display(array(
            'attachList' => $attachList,
            'row'        => $row,
        ));
    }

    public function deleteAction()
    {
        $id = (int)Input::get('id');
        $row = DB::table('inspect_mart')->where('id', $id)->first();

        if (empty($row)) {
            return $this->error('没有数据。');
        }

        // 删除附件
        attachment_delete('inspect_attachment', $row['attachment']);

        DB::table('inspect_mart')->where('id', $id)->delete();

        return $this->success('index', '数据删除成功。');
    }

    // 市场巡查类别
    public function categoryAction()
    {
        $model = DB::table('inspect_mart_category');

        // 更新排序
        if ($post = $this->post('sort')) {
            foreach ($post as $k => $v) {
                $data['sort'] = $v;
                DB::table('inspect_mart_category')->where('id', $k)->update($data);
            }
        }
        $rows = DB::table('inspect_mart_category')
        ->where('state', 1)
        ->orderBy('sort', 'asc')
        ->get();
        
        if (Input::get('data_type') == 'json') {
            return $this->json($rows, true);
        }

        // 返回 json
        if (Request::wantsJson()) {
            return response()->json($rows);
        }

        return $this->display(array(
            'rows' => $rows,
        ));
    }

    //添加市场类别
    public function category_addAction()
    {
        $id = (int)Input::get('id');
        if ($post = $this->post()) {
            if (empty($post['name'])) {
                return $this->error('很抱歉，类别名称必须填写。');
            }
            
            unset($post['past_parent_id']);

            if ($post['id'] > 0) {
                DB::table('inspect_mart_category')->where('id', $post['id'])->update($post);
            } else {
                DB::table('inspect_mart_category')->insert($post);
            }
            return $this->success('category', '恭喜你，类别更新成功。');
        }

        $row = DB::table('inspect_mart_category')->where('id', $id)->first();

        return $this->display(array(
            'row'  => $row,
        ));
    }

    // 删除市场类别
    public function category_deleteAction()
    {
        if ($id = Input::get('id')) {
            DB::table('inspect_mart_category')->where('id', $id)->delete();
            return $this->success('category', '恭喜你，类别删除成功。');
        }
        return $this->error('很抱歉，编号不正确。');
    }
}
