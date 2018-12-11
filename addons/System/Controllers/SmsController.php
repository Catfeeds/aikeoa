<?php namespace Aike\System\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Index\Controllers\DefaultController;

class SmsController extends DefaultController
{
    /**
     * 短信设置
     */
    public function indexAction()
    {
        $search = search_form([
            'referer' => 1
        ]);
        $rows = DB::table('sms')->paginate($search['limit']);
        return $this->display([
            'rows' => $rows
        ]);
    }

    /**
     * 短信保存
     */
    public function storeAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            $rules = [
                'name'   => 'required',
                'appkey' => 'required',
                'secret' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            if ($gets['id']) {
                DB::table('sms')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('sms')->insert($gets);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }
    }

    // 新建短信帐号
    public function addAction()
    {
        return $this->display([], 'edit');
    }

    // 编辑短信帐号
    public function editAction()
    {
        $id  = (int)Input::get('id');
        $row = DB::table('sms')->where('id', $id)->first();
        return $this->display([
            'row' => $row,
        ]);
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            DB::table('sms')->whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }

    /**
     * 短信测试
     */
    public function testAction()
    {
        if (Request::method() == 'POST') {
            $mobile = Input::get('mobile');

            $send = notify()->sms([$mobile], '测试短信', '测试短信内容');

            if ($send) {
                $subject = 'message';
                $content = '测试短信已发送。';
            } else {
                $subject = 'error';
                $content = '发送失败，请检查短信配置。';
            }
            return $this->back($content, $subject)->withInput();
        }
        
        return $this->display([
            'mobile' => $mobile
        ]);
    }
}
