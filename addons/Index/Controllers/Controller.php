<?php namespace Aike\Index\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Aike\System\Setting;
use Input;
use Session;
use View;
use URL;
use Request;

class Controller extends BaseController
{
    /**
     * @var 程序版本
     */
    public $version = '<a href="http://www.aikeoa.com">爱客办公</a> 1.0.0';

    /**
     * @var 开发商名称
     */
    public $powered = '爱客办公';

    /**
     * @var 配置参数
     */
    public $setting = [];

    /**
     * @var 跳过acl检查的方法
     */
    public $permission = [];

    /**
     * @var 当前控制下的方法权限
     */
    public $access = [];
   
    /**
     * @var layout 布局视图模板
     */
    protected $layout = 'layouts.default';

    /**
     * @var 企业微信
     */
    public $wxwork = false;

    /**
     * @var 执行初始化工作
     */
    public function __construct()
    {
        error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);

        // 不丢失表单返回数据
        header('Cache-control:private, must-revalidate');

        // 获取配置数据
        $this->setting = Setting::pluck('value', 'key');

        $this->setting['powered'] = $this->powered;

        View::share([
            'title'      => $this->setting['title'],
            'setting'    => $this->setting,
            'public_url' => URL::to('/'),
            'upload_url' => URL::to('/uploads'),
            'static_url' => URL::to('/static'),
            'asset_url'  => URL::to('/assets'),
            'version'    => $this->version,
        ]);
    }

    /**
     * 获取或设置[$_POST]查询字符串,注意此变量经过xss过滤的
     *
     * @param  mixed  $key   获取$_POST值的键
     * @param  string $value 在没有获取到数据的时候返回默认值
     * @return mixed
     */
    public function post($key = null, $default = null)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return $key === null ? $_POST : $default;
    }

    /**
     * Ajax调用返回
     *
     * 返回json数据, 供前台ajax调用
     * @param array   $data     返回数组,支持数组
     * @param boolean $status   执行状态, 1为true, 0为false
     * @param string  $type     返回信息类型, 默认为primary
     * @return string
     */
    public function json($data, $status = false)
    {
        $json = [];
        if ($status === false) {
            $json['status'] = $status;
            $json['url']    = null;
        } else {
            $json['status'] = true;
            if($status === true) {
                $json['url'] = '';
            } else {
                $json['url'] = url_referer($status);
                session()->flash('message', $data);
            }
        }
        $json['data'] = $data;
        return response()->json($json);
    }

    /**
     * 返回页面
     */
    public function back($message = null, $type = 'message')
    {
        $args = func_num_args();
        if ($args == 0) {
            return redirect()->back();
        }
        return redirect()->back()->with($type, $message);
    }
    
    // 操作错误返回
    public function error($error = null, $type = 'error')
    {
        $args = func_num_args();
        if ($args == 0) {
            return redirect()->back();
        }
        return redirect()->back()->with($type, $error);
    }

    // 提示信息不跳转
    public function alert($message)
    {
        return $message;
    }
    
    // 操作成功跳转
    public function success($path, $params = [], $message = null, $referer = 1)
    {
        $args = func_num_args();
        if ($args > 2) {
            return $this->to($path, $params, $referer)->with('message', $message);
        } else {
            return $this->to($path, [], $referer)->with('message', $params);
        }
    }

    /**
     * 刷新页面附带 referer
     */
    public function to($path = null, $params = [], $referer = 1)
    {
        return redirect(url_referer($path, $params, $referer));
    }
    
    /**
     * 模板文件名
     */
    public function viewFile($file)
    {
        if ($file === null) {
            $file = Request::controller().'.'.Request::action();
        } else {
            if (substr_count($file, '.') === 0) {
                $file = Request::controller().'.'.$file;
            }
        }
        return $file;
    }

    /**
     * 直接渲染模板不包含layout视图
     */
    public function render($params = [], $file = null)
    {
        return view($this->viewFile($file), $params);
    }

    /**
     * 渲染模板嵌套到layout视图
     */
    public function display($params = [], $file = null, $layout = '')
    {
        $layout = $layout == '' ? $this->layout : $layout;
        $layout = view($layout);
        return $layout->nest('content', $this->viewFile($file), $params);
    }
}
