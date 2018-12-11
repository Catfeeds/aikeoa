<?php namespace App\Wechat\Work;

class AccessToken
{
    private $corpId;
    private $secret;
    private $agentId;
    private $appConfigs;

    /**
     * AccessToken构造器
     * @param [Number] $agentId 两种情况：1是传入字符串“txl”表示获取通讯录应用的Secret；2是传入应用的agentId
     */
    public function __construct($agentId)
    {
        $this->appConfigs = Util::loadConfig();
        $this->corpId = $this->appConfigs['corpId'];

        $this->secret = "";
        $this->agentId = $agentId;

        // 由于通讯录是特殊的应用，需要单独处理
        if ($agentId == "txl") {
            $this->secret = $this->appConfigs['txlSecret'];
        } else {
            $config = Util::getConfigByAgentId($agentId);
            if ($config) {
                $this->secret = $config['secret'];
            }
        }
    }

    public function getAccessToken()
    {
        // NOTE: 由于实际使用过程中不同的应用会产生不同的token，所以示例按照agentId做为文件名进行存储
        $data = cache()->get('wechat_work_'.$this->agentId);
        if ($data['expire_time'] < time()) {
            $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->corpId&corpsecret=$this->secret";
            $res = json_decode(Util::httpGet($url)["content"]);

            $access_token = $res->access_token;

            if ($access_token) {
                $data['expire_time'] = time() + 7000;
                $data['access_token'] = $access_token;
                cache()->put('wechat_work_'.$this->agentId, $data, 60);
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }
}
