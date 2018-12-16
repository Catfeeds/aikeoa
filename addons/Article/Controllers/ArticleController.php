<?php namespace Aike\Article\Controllers;

use DB;
use Auth;
use Input;
use Request;

use Aike\User\User;
use Aike\User\Department;
use Aike\Article\Article;
use Aike\Index\Attachment;
use Aike\Index\Controllers\DefaultController;

class ArticleController extends DefaultController
{
    public $permission = [];

    public function indexAction()
    {
        $search = search_form([
            'read'    => 'all',
            'referer' => 1,
        ], [
            ['text','title','主题'],
            ['text','id','编号'],
            ['article.category','category_id','类别'],
        ]);

        $query  = $search['query'];
        $model = Article::withAt('user', ['id','username','nickname']);

        if ($query['order'] && $query['srot']) {
            $model->orderBy($query['srot'], $query['order']);
        } else {
            $model->orderBy('article.id', 'desc');
        }

        if ($this->access['index'] < 4) {
            // 这里需要包括创建者权限
            $model->permission('receive_id', null, false, true, false, 'created_by');
        }

        // 查询是否已经阅读
        $reader = function ($q) {
            $q->selectRaw('1')
            ->from('article_reader')
            ->whereRaw('article_reader.article_id = article.id')
            ->where('article_reader.created_by', auth()->id());
        };

        if ($query['read'] == 'done') {
            $model->whereExists($reader);
        }
        if ($query['read'] == 'unread') {
            $model->whereNotExists($reader);
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->paginate($search['limit'])->appends($query);

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $tabs = [
            'name'  => 'read',
            'items' => [
                ['id'=>'all', 'name'=>'全部'],
                ['id'=>'unread', 'name'=> '未读'],
                ['id'=>'done', 'name'=> '已读'],
            ]
        ];

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
            'tabs'   => $tabs,
        ]);
    }

    public function createAction()
    {
        // 更新数据
        if (Request::method() == 'POST') {
            $post = Input::get();

            if (empty($post['receive_id'])) {
                return $this->json('发布对象必须选择。');
            }

            if (empty($post['title'])) {
                return $this->json('公告标题必须填写。');
            }

            /*
            if (empty($post['category_id'])) {
                return $this->json('公告类别必须选择。');
            }
            */

            if (empty($post['content'])) {
                return $this->json('公告正文必须填写。');
            }

            $post['content']    = $_POST['content'];
            $post['expired_at'] = strtotime($post['expired_at']);
            $post['attachment'] = join(',', (array)$post['attachment']);

            $notify_type = array_pull($post, 'notify');
            $uploadify   = array_pull($post, 'uploadify');

            $model = Article::findOrNew($post['id']);
            $model->fill($post);
            $model->save();

            // 附件发布
            Attachment::publish();
 
            $notify['subject'] = '川南提醒您!';
            $notify['body']    = '请登录或手机直接登录www.shenghuafood.com 在工作信息里查阅关于'.$post['title'].'的公告。';
            $notify['url']     =  url('view', ['id' => $model->id]);

            $users = User::getDRU($post['receive_id']);
            notify($users, $notify, $notify_type);

            // 操作日志
            action_log('article', $model->id, 'article/article/view', $post['id']);
            // 跳转
            return $this->json('恭喜你，保存成功。', 'index');
        }

        $id = Input::get('id');
        $row = Article::find($id);

        $attach = Attachment::edit($row->attachment);

        return $this->display([
            'model'      => $model,
            'attach'     => $attach,
            'row'        => $row,
        ]);
    }

    public function viewAction()
    {
        $id = (int)Input::get('id');

        $res = Article::withAt('user', ['id','username','nickname'])
        ->where('id', $id)->first();

        // 发布人
        $from = DB::table('user')->where('id', $res['created_by'])->first();

        // 附件
        $attach = Attachment::view($res['attachment']);
        
        $res->attachment = $attach['main'];

        // 已读记录
        $reads = DB::table('article_reader')->where('article_id', $id)->get();
        $reads = array_by($reads, 'created_by');

        // 更新阅读记录
        if (empty($reads[Auth::id()])) {
            DB::table('article_reader')->insert([
                'article_id' => $id,
            ]);
            
            // 操作日志
            action_log('article', $id, 'article/article/view', '阅读');
        }

        // 返回json
        if (Request::wantsJson()) {
            return $res->toJson();
        }

        return $this->display([
            'attach' => $attach,
            'res'    => $res,
            'from'   => $from,
        ]);
    }

    /**
     * 阅读记录
     */
    public function readerAction()
    {
        $id = Input::get('id', 0);

        // 取得当前项目阅读情况
        $reads = DB::table('article_reader')->where('article_id', $id)->get();
        $reads = array_by($reads, 'created_by');
        $row = DB::table('article')->where('id', $id)->first();

        $scopes = User::getDRU($row['receive_id']);

        if ($scopes->count()) {
            $rows = [];

            $departments = Department::orderBy('lft', 'asc')->pluck('title', 'id');

            foreach ($scopes as $scope) {
                $read = isset($reads[$scope['id']]) ? 1 : 0;
                $rows['total'][$read]++;
                $rows['data'][] = [
                    'read'            => $read,
                    'department_id'   => $scope['department_id'],
                    'department_name' => $departments[$scope['department_id']],
                    'nickname'        => $scope['nickname'],
                    'created_at'      => $reads[$scope['id']]['created_at'],
                ];
            }

            $rows['data'] = array_sort($rows['data'], function ($value) {
                return $value['created_at'];
            });
        }

        return $this->render([
            'rows' => $rows,
        ]);
    }

    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $id = array_filter((array)$id);

            $rows = DB::table('article')->whereIn('id', $id)->get();

            if ($rows->isEmpty()) {
                return $this->error('没有数据。');
            }

            foreach ($rows as $row) {
                // 新删除附件
                Attachment::delete($row['attachment']);

                // 删除阅读积累
                DB::table('article_reader')->where('article_id', $row['id'])->delete();
                
                // 删除新闻
                DB::table('article')->where('id', $row['id'])->delete();
            }
            return $this->back('删除成功。');
        }
    }
}
