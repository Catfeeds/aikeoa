<?php namespace Aike\Message\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Paginator;

use Sms;
use Aike\User\User;
use Aike\Index\Controllers\DefaultController;

class MessageController extends DefaultController
{
    // 信息列表
    public function indexAction()
    {
        $search = search_form([
            'tab' => 'receive',
        ], [
            ['text','user.nickname','发件人'],
            ['text','title','主题'],
            ['second','add_time','发送时间'],
            ['second','hope_reply_time','阅读时间'],
        ]);
        $query  = $search['query'];

        $model = DB::table('communicate')
        ->LeftJoin('user', 'user.id', '=', 'communicate.user_id')
        ->orderBy('communicate.id', 'desc')
        ->select(['communicate.*']);

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        if ($query['tab'] == 'receive') {
            $model->where('communicate.to_user_id', Auth::id());
        } else {
            $model->where('communicate.user_id', Auth::id());
        }
        
        $rows = $model->paginate()->appends($query);
        return $this->display(array(
            'search' => $search,
            'rows'   => $rows,
            'query'  => $query,
        ));
    }

    public function addAction()
    {
        if ($post = $this->post()) {
            $post['content'] = $_POST['content'];
            $post['hope_reply_time'] = strtotime($post['hope_reply_time']);
            $post['attachment'] = join(',', (array)$post['attachment']);

            if (empty($post['title'])) {
                return $this->error('信件标题必须填写。');
            }

            if ($post['hope_reply_time'] < time()) {
                return $this->error('期望回复时间不能小于当前时间。');
            }

            if ($post['to_user_id'] <= 0) {
                return $this->error('沟通对象必须选择。');
            }

            if (empty($post['content'])) {
                return $this->error('信件正文必须填写。');
            }

            // 短信通知
            if ($post['sms'] == 'true') {
                $to_user = DB::table('user')->where('id', $post['to_user_id'])->first();
                $user    = DB::table('user')->where('id', Auth::id())->first();
                Notify::sms([$to_user['mobile']], '您有新的沟通函, 主题: '.$post['title'].'，发信人: '.$user['nickname'].'，希望您回复时间: '.date($this->setting['datetime_format'], $post['hope_reply_time']));
            }

            unset($post['sms']);

            $post['user_id'] = Auth::id();
            if ($post['id'] > 0) {
                DB::table('communicate')->where('id', $post['id'])->update($post);
            } else {
                $post['add_time'] = time();
                DB::table('communicate')->insert($post);
            }

            // 设置附件为已经使用
            attachment_store('attachment', $_POST['attachment']);

            return $this->success('index', '恭喜您，沟通函提交成功。');
        }

        $id = (int)Input::get('id', 0);
        $res = DB::table('communicate')->find($id);

        $attachList = attachment_edit('attachment', $res['attachment']);

        return $this->display(array(
            'attachList' => $attachList,
            'res'        => $res,
        ));
    }

    // 查看信件
    public function viewAction()
    {
        if ($post = $this->post()) {
            if (empty($post['content'])) {
                return $this->error('正文必须填写。');
            }
        
            // 更新回复信件
            $update = array();
            $update['reply_text'] = $_POST['content'];
            $update['reply_time'] = time();
            $update['reply_attachment'] = join(',', (array)$post['attachment']);

            DB::table('communicate')->where('id', $post['id'])->update($update);

            attachment_store('attachment', $_POST['attachment']);

            // 短信通知
            if ($post['sms'] == 'true') {
                $res         = DB::table('communicate')->where('id', $post['id'])->first();
                $target_user = DB::table('user')->where('id', $res['user_id'])->first();
                $user        = DB::table('user')->where('id', Auth::id())->first();

                Notify::sms([$target_user['mobile']], '您有新的沟通函回复, 主题: '.$res['title'].'，回复人: '.$user['nickname'].'，希望回复时间: '.date($this->setting['datetime_format'], $post['hope_reply_time']));
            }
            return $this->success('view', ['id' => $post['id']], '恭喜您，沟通函回复成功。');
        }

        // 回复标志
        $reply = Input::get('reply', 0);
        $id = Input::get('id', 0);

        if ($id <= 0) {
            return $this->error('信件的编码不正确。');
        }

        $res = DB::table('communicate')->find($id);

        $attachList = attachment_edit('attachment', $res['attachment']);
        $attachList['view'] = attachment_get('attachment', $res['attachment']);
        $attachList['reply'] = attachment_get('attachment', $res['reply_attachment']);

        return $this->display(array(
            'attachList' => $attachList,
            'reply'      => $reply,
            'res'        => $res,
        ));
    }
    
    // 删除信件
    public function deleteAction()
    {
        $id = (int)Input::get('id', 0);

        $res = DB::table('communicate')->where('id', $id)->first();

        if (empty($res)) {
            return $this->error('没有数据。');
        }

        attachment_delete('attachment', $res['attachment']);
        attachment_delete('attachment', $res['reply_attachment']);

        DB::table('communicate')->where('id', $id)->delete();

        return $this->success('index', '恭喜您，沟通函删除成功。');
    }
}
