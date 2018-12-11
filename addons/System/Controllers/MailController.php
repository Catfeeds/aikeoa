<?php namespace Aike\System\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Index\Controllers\DefaultController;

class MailController extends DefaultController
{
    /**
     * 邮件设置
     */
    public function indexAction()
    {
        $search = search_form([
            'referer' => 1
        ]);
        $rows = DB::table('mail')->paginate($search['limit']);
        return $this->display([
            'rows' => $rows
        ]);
    }

    // 新建邮箱帐号
    public function addAction()
    {
        return $this->display([], 'edit');
    }

    // 编辑邮箱帐号
    public function editAction()
    {
        $id  = (int)Input::get('id');
        $row = DB::table('mail')->where('id', $id)->first();
        return $this->display([
            'row' => $row,
        ]);
    }

    /**
     * 保存
     */
    public function storeAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            $rules = [
                'name'     => 'required',
                'smtp'     => 'required',
                'user'     => 'required',
                'password' => 'required',
                'port'     => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            if ($gets['id']) {
                DB::table('mail')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('mail')->insert($gets);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            DB::table('mail')->whereIn('id', $id)->delete();
            return $this->back('操作完成。');
        }
    }

    /**
     * 邮件测试
     */
    public function testAction()
    {
        if (Request::method() == 'POST') {
            $mail_to = Input::get('mail_to');

            $send = notify()->mail([$mail_to], '测试邮件', '测试邮件内容');

            if ($send) {
                $subject = 'message';
                $content = '测试邮件已发送。';
            } else {
                $subject = 'error';
                $content = '发送失败，请检查邮件地址或SMTP配置。';
            }
            return $this->back($content, $subject)->withInput();
        }
        
        return $this->display([
            'mail_to' => $mail_to
        ]);
    }
}
