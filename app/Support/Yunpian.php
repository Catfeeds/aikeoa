<?php

class Yunpian
{
    public static $apikey = '85SBI86vm2aRih1AXVQMW1sjMgpfo5GM'; //4095b4b05c69359ff5d4659bf39ad3fc
    public static $sign   = '【川南酿造】';

    public static function init()
    {
        $ch = curl_init();

        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept:text/plain;charset=utf-8',
            'Content-Type:application/x-www-form-urlencoded',
            'charset=utf-8'
        ]);

        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return $ch;
    }

    // 发送信息
    public static function send($mobile, $text)
    {
        // 过滤
        $text = static::replaceWords($text);

        $ch = static::init();
        $data = [
            'apikey' => static::$apikey,
            'text'   => static::$sign.$text,
            'mobile' => $mobile
        ];
        // curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/batch_send.json');
        curl_setopt($ch, CURLOPT_URL, 'http://crm.aikeoa.com/core/api/sms_send');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $json = curl_exec($ch);

        $res  = json_decode($json, true);
        return $res;
    }

    // 过滤短信关键词
    public static function replaceWords($text)
    {
        // 替换括号
        $search  = ['【','】','[',']'];
        $replace = ['(',')','(',')'];
        return str_replace($search, $replace, $text);
    }

    // 获取短信模板
    public static function getTpl($id)
    {
        $ch = static::init();
        curl_setopt($ch, CURLOPT_URL, 'http://crm.aikeoa.com/core/api/sms_tpl');
        $query = [
            'apikey' => static::$apikey,
            'tpl_id' => $id
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        $json = curl_exec($ch);
        $res  = json_decode($json, true);
        $res['tpl_content'] = str_replace(static::$sign, '', $res['tpl_content']);
        return $res;
    }

    // 模板方式发送
    public static function tplSend($mobile, $text)
    {
        $ch = static::init();

        //$tpl = ('#code#').'='.urlencode('1234').'&'.urlencode('#company#').'='.urlencode('欢乐行');

        $data = [
            'apikey'    => static::$apikey,
            'tpl_id'    => '1',
            'tpl_value' => $tpl,
            'mobile'    => $mobile
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/tpl_single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $json = curl_exec($ch);
        $res  = json_decode($json, true);
        return $res;
    }

    // 语音发送
    public static function voiceSend($mobile, $text)
    {
        $ch = static::init();
        curl_setopt($ch, CURLOPT_URL, 'http://voice.yunpian.com/v2/voice/send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        return curl_exec($ch);
    }

    // 获取用户数据
    public static function getUser()
    {
        $ch = static::init();
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/user/get.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => static::$apikey)));
        $json = curl_exec($ch);
        $res  = json_decode($json, true);
        return $res;
    }
}
