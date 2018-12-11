<?php namespace Aike\Index\Controllers;

use Aike\User\UserAsset;
use Aike\Index\Menu;
use View;

class DefaultController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        UserAsset::setPermissions($this->permission);

        // 登录认证和RBAC检查
        $this->middleware('auth');

        // 获取登录认证数据
        $this->middleware(function ($request, $next) {
            $this->user = $request->user();
            $this->access = UserAsset::getNowRoleAssets();
            $menus = Menu::getItems();
            View::share([
                'menus'  => $menus,
                'access' => $this->access,
            ]);
            return $next($request);
        });
    }
}
