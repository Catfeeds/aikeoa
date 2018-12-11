<?php namespace Aike\Index\Controllers;

use DB;
use URL;
use Input;
use QRCode;
use Request;

use Aike\User\User;

class ApiController extends Controller
{
    public function helloAction()
    {
        \App\Jobs\SendSite::dispatch([1], '您的验证码是0123');
        exit;

        DB::enableQueryLog();
        $user = DB::table('user as u')->orderBy('id', 'desc')->orderBy('username', 'asc')->first();
        print_r(DB::getQueryLog());

        exit;
        /*
        $t1 = microtime(true);

        $stocks = DB::table('stock_yonyou_data')
        ->groupBy('code')
        ->selectRaw('sum(quantity_set - quantity_get) as quantity,code')
        ->pluck('quantity', 'code');

        $abc = 0;
        foreach ($stocks as $stock) {
            $abc += $stock;
        }

        echo $abc."<br>";

        $t2 = microtime(true);
        echo '耗时'.($t2 - $t1).'秒';

        ``

        exit;
        */

        //$abc = \Yunpian::send('15182223008', '您的验证码是5967');
        //print_r($abc);
        //exit;

        /*
        $agentid = 1000035;
        $url = 'http://www.shenghuafood.com/article/article/view?id=1336&agentid='.$agentid;
        //$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=ww42727b1e44abc7fa&redirect_uri='.$u.'&response_type=code&scope=snsapi_privateinfo&agentid='.$agentid.'&state='.$agentid.'#wechat_redirect';

        $msg = array(
            'touser'  => 'qy01bbfb5d6f30ae009bc0e5b8fb',
            'toparty' => '',
            'msgtype' => 'news',
            'agentid' => $agentid,
            'news' => array(
                "articles"=> array(
                    0 => array(
                        "title"       => "有新的公告提醒",
                        "description" => "[公告】关于西安大军区的调整公告",
                        "url"         => $url,
                        "picurl"      => ""
                ))
            )
            'text'    => array(
                "content"=>"各部门及同事：\n".
                "为更好的服务好再来大厦，满足大厦入驻员工的班车需求，现对部分班车路线及时刻做相应调整，自2016年9月20日零时生效。详情点击\n<a href=\"http://banche.hoolilai.com\">http://banche.hoolilai.com</a>"
            )
        );
                
        $api = new \App\Wechat\Work\App($agentid);
        
        var_dump($api->sendMsgToUser($msg));

        */
        $xml = simplexml_load_file('tpl.xml');
        /*
        $attributes = $xml->record->attributes();
        foreach ($attributes as $k => $v) {
            print_r($k.'---'.$v);
        }
        */
        $form = $xml->xpath("record[@type='form']/form")[0];

        foreach ($form as $key => $node) {
            if ($key == 'group') {
                $fields = [];
                foreach ($node as $k => $field) {
                    if ($k == 'field') {
                        $attr = $field->attributes();
                        $col  = $attr['col'] - 2;
                        $abc[] = '<label class="col-sm-2 control-label" for=""><span class="red">*</span> 供应商</label>';
                        $abc[] = '<div class="col-sm-'.$col.' control-text"><input type="text" value="180407-185" required="required" class="form-control input-sm" id="supplier_price_sn" name="supplier_price[sn]" readonly="readonly"></div>';
                    }
                    //print_r($k);
                }
                print_r($abc);
            }
        }

        //$abc = Yunpian::send('15182223008', '您的验证码是5967');
        //print_r($abc);

        //$ab = new Hawind\Core();
        
        // 开启 log
        //DB::connection()->enableQueryLog();

        //$abc = User::whereIn('user.id', [1,2,3,4])->select(['user.*','user.nickname as role_name'])->paginate();

        // 获取已执行的查询数组
        //$abc = DB::getQueryLog();

        //$ab->test($abc);

        //print_r($abc);

        //print_r($cron->isDue());
        //$cron = Cron\CronExpression::factory('0 0 0 ? 1/2 FRI#2 *');
        //if ($cron->isDue()) {
        // The promotion should be enabled!
        //}

        /*
        $datas = DB::table('stock')
        ->where('date', '0000-00-00')
        ->get();

        foreach ($datas as $key => $data) {
            $data['date'] = date('Y-m-d', $data['add_time']);
            DB::table('stock')->where('id', $data['id'])->update($data);
        }
        */

        /*
        $logs = DB::table('model_step_log')
        ->where('table', 'promotion')
        ->where('step_status', 'next')
        ->where('created_id', '278')
        ->get();

        foreach ($logs as $log) {
            $data['data_30'] = date('Y-m-d', $log['created_at']);
            DB::table('promotion')->where('id', $log['table_id'])->update($data);
        }
        */

        //$sms = new iscms\Alisms\SendsmsPusher();

        //$t = "项目流程提醒! 主题：关于违反销售管理制度之扣分——龚涛天 -【销售行为】处罚等待确认！";
        //$words = Yunpian::replaceWords($t);

        //$t = str_replace($words[0], $words[1], $t);

        //$b = mb_str_split('销售');

        //print_r(var_dump($words));

        //$abc = Yunpian::getBlackWord($words);

        // $abc = Notify::sms(["15182223008"], $t);
        //$abc = Yunpian::getTpl('1701454');

        // $abc = Yunpian::getUser();

        //print_r($abc['balance'] / 0.05);

        //print_r($words);

        exit;

        /*
        $departments = DB::table('department')->pluck('title', 'id');
        $roles = DB::table('role')->pluck('title', 'id');
        $users = DB::table('user')->pluck('nickname', 'id');

        $shares = DB::table('article')->get();

        foreach ($shares as $share) {

            $id = $name = [];

            $share_user = explode(',', $share['user_id']);
            foreach ($share_user as $user) {
                if($users[$user]) {
                    $id[] = 'u'.$user;
                    $name[] = $users[$user];
                }
            }

            $share_role = explode(',', $share['role_id']);
            foreach ($share_role as $role) {
                if($roles[$role]) {
                    $id[] = 'r'.$role;
                    $name[] = $roles[$role];
                }
            }

            $share_department = explode(',', $share['department_id']);
            foreach ($share_department as $department) {
                if($departments[$department]) {
                    $id[] = 'd'.$department;
                    $name[] = $departments[$department];
                }
            }

            DB::table('article')->where('id', $share['id'])->update([
                'receive_id'   => join(',', $id),
                'receive_name' => join(',', $name)
            ]);
        }
        */

        /*
        $users = User::get();

        foreach ($users as $user) {

            if($user->password_text == '' && mb_strlen($user->password) == 32) {
                $user->password = \Hash::make($user->username);
                $user->password_text = $user->username;
                $user->save();
            }
        }
        */

        /*
        $p2 = DB::connection('sqlite')
        ->table('city')
        ->where('parent_id', 2621)
        ->get();

        print_r($p2);
        exit;

        */

        // app()->configure('pcas');

        // $abc = config('pcas');

        // print_r(json_encode($abc, JSON_UNESCAPED_UNICODE));

        /*

        $users = DB::table('user')->get();

        foreach ($users as $user) {

            $data['warehouse_tel']     = $user['warehouse_tel'];
            $data['warehouse_contact'] = $user['warehouse_contact'];
            $data['warehouse_mobile']  = $user['warehouse_mobile'];
            $data['warehouse_address'] = $user['warehouse_address'];
            $data['invoice_type']      = $user['invoice'];

            DB::table('customer')->where('user_id', $user['id'])->update($data);
        }
        */
        // print_r(123);
        // exit;
        // return $this->render([]);
    }

    /**
     * jq导出xls
     */
    public function jqexportAction()
    {
        $gets = Input::get();
        $data = urldecode($gets['data']);
        $rows = json_decode($data, true);
        return writeExcel1($rows['thead'], $rows['tbody'], 'jqexport');
    }
    
    /**
     * 初始化JS输出
     */
    public function commonAction()
    {
        $settings['public_url'] = URL::to('/');
        $settings['upload_file_type'] = $this->setting['upload_type'];
        $settings['upload_max_size']  = $this->setting['upload_max'];
        header('Content-type: text/javascript');
        echo 'var settings = '. $json = json_encode($settings);
        exit;
    }

    /**
     * 任务调用
     */
    public function taskAction()
    {
        $rows = DB::table('cron')->where('status', 1)->get();
        if ($rows) {
            foreach ($rows as $row) {
                $cron = Cron\CronExpression::factory($row['expression']);

                // 由于定时任务无法定义秒这里特殊处理一下
                if (strtotime($row['next_run']) <= time()) {
                    // 这里执行代码
                    // 记录下次执行和本次执行结果
                    $next = $cron->getNextRunDate()->format('Y-m-d H:i:00');
                    $data = [
                        'next_run' => $next,
                        'last_run' => '执行成功。'
                    ];
                    DB::table('cron')->where('id', $row['id'])->update($data);
                }
            }
        }
    }

    /**
     * 汉字转拼音
     */
    public function pinyinAction()
    {
        $word = Input::get('name');
        $type = Input::get('type');

        if (empty($word)) {
            return '';
        }

        if ($type == 'first') {
            return str_replace('/', '', Pinyin::output(str_replace(' ', '', $word)));
        } else {
            return str_replace('/', '', Pinyin::getstr(str_replace(' ', '', $word)));
        }
    }

    /**
     * 显示位置信息
     */
    public function locationAction()
    {
        $gets = Input::get();
        return $this->render(array(
            'gets' => $gets
        ));
    }

    /**
     * 二维码生成
     */
    public function qrcodeAction()
    {
        $size  = Input::get('size', 3);
        $level = Input::get('level', 'L');
        $data  = Input::get('data');

        $qr = QRCode::getMinimumQRCode($data, QR_ERROR_CORRECT_LEVEL_H);
        $im = $qr->createImage($size, 5);

        header('Content-type:image/gif');
        imagegif($im);
        imagedestroy($im);
    }
    
    /**
     * 系统字典
     */
    public function dictAction()
    {
        $key  = Input::get('key');
        $rows = option($key);
        return response()->json($rows)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * 系统选项
     */
    public function optionAction()
    {
        $key  = Input::get('key');
        $rows = option($key);
        return response()->json($rows)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * 不支持浏览器提示
     */
    public function unsupportedBrowserAction()
    {
        return $this->render();
    }

    /*
     * 显示用户列表
     */
    public function dialogAction()
    {
        $gets = Input::get();
        return $this->render([
            'gets' => $gets
        ]);
    }

    /*
     * 调用省市县显示
     */
    public function regionAction()
    {
        $parent_id = Input::get('parent_id');
        $layer     = Input::get('layer', 1);

        if ($layer == 1) {
            $parent_id = 1;
        }

        $names = array(1=>'省',2=>'市',3=>'县');

        $rows[] = array('id'=>'','name'=>$names[$layer]);

        $rows += DB::table('region')
        ->where('parent_id', $parent_id)
        ->where('layer', $layer)
        ->get()->toArray();

        return response()->json($rows);
    }

    /**
     * 短信发送接口
     */
    public function smsAction()
    {
        $user_id = Input::get('user_id');
        $user = DB::table('user')->find($user_id);

        if (Request::method() == 'POST') {
            $content = Input::get('content');
            if ($content == '') {
                return response()->json('短信内容必须填写。');
            }
            $phone[] = $user['mobile'];

            if ($phone && $content) {
                Notify::sms($phone, $content);
                return $this->json('短信发送成功。', true);
            }
            return $this->json('短信发送参数不正确。');
        }
        return $this->render([
            'user' => $user
        ]);
    }

    /*
     * 盛华市场APP升级数据
     */
    public function app_customer_versionAction()
    {
        $gets = Input::get();

        $data = [[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.1',
            'description' => "1.修改促销核销，新增核销选中更详细。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.customer/1.0.1.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.2',
            'description' => "1.新增核销选择中添加开始和结束日期。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.customer/1.0.2.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.3',
            'description' => "1.完善促销拍照上传字段。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.customer/1.0.3.zip',
        ]];

        $update = ['status' => 0];

        if ($gets['version']) {
            foreach ($data as $i => $row) {
                if (version_compare($gets['version'], $row['version'], '<')) {
                    $update = $row;
                    break;
                }
            }
        }
        return $update;
    }

    /*
     * App 更新信息
     */
    public function app_versionAction()
    {
        $gets = Input::get();

        $data = [[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.6',
            'description' => "-修复客户库存提交出错bug。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.6.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.7',
            'description' => "-加入固定资产扫描查询功能。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.7.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.8',
            'description' => "-客户门店修改字段和增加功能。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.8.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.9',
            'description' => "-加入设备绑定功能。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.9.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.11',
            'description' => "-修改客户库存字段。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.11.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.12',
            'description' => "-客户库存加入上次发货数量和时间。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.12.zip',
        ],[
            'type'        => 'inside',
            'status'      => 1,
            'version'     => '1.0.13',
            'description' => "-取消客户门店的特陈照片字段。",
            'path'        => 'http://www.shenghuafood.com/uploads/com.shenghua.oa/1.0.13.zip',
        ]];

        $update = ['status' => 0];

        if ($gets['version']) {
            foreach ($data as $i => $row) {
                if (version_compare($gets['version'], $row['version'], '<')) {
                    $update = $row;
                    break;
                }
            }
        }
        return $update;
    }
}
