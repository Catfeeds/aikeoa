<?php namespace Aike\System\Controllers;

use DB;
use Input;
use Request;

use Aike\System\Setting;
use Aike\Index\Notification;
use Aike\Index\Controllers\DefaultController;

class SettingController extends DefaultController
{
    /**
     * 基本设置
     */
    public function indexAction()
    {
        return $this->display();
    }
    
    /**
     * 上传设置
     */
    public function uploadAction()
    {
        // 扫描字体目录
        $paths = new \DirectoryIterator(public_path('assets/fonts'));
        $fonts = [];
        foreach ($paths as $file) {
            if ($file->isFile()) {
                $fonts[] = $file->getFilename();
            }
        }
        return $this->display([
            'fonts' => $fonts,
        ]);
    }

    /**
     * 图片设置
     */
    public function imageAction()
    {
        // 扫描字体目录
        $paths = new \DirectoryIterator(public_path('assets/fonts'));
        $fonts = [];
        foreach ($paths as $file) {
            if ($file->isFile()) {
                $fonts[] = $file->getFilename();
            }
        }
        return $this->display([
            'fonts' => $fonts,
        ]);
    }

    /**
     * 安全设置
     */
    public function securityAction()
    {
        return $this->display();
    }

    /**
     * 日期时间
     */
    public function datetimeAction()
    {
        $lang = trans('setting');
        return $this->display(array(
            'lang' => $lang,
        ));
    }

    /**
     * 存储设置
     */
    public function storeAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get('data');

            $gets['mail_encryption'] = $gets['mail_encryption'];

            foreach ($gets as $key => $value) {
                Setting::where('key', $key)->update([
                    'value' => $value,
                ]);
            }
            return $this->back('恭喜你，操作成功。');
        }
    }
}
