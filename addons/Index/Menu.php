<?php namespace Aike\Index;

use Auth;
use DB;

use Aike\User\UserAsset;

class Menu extends BaseModel
{
    protected $table = 'menu';

    /**
     * 取得菜单列表
     */
    public static function getItems()
    {
        static $data = [];

        if ($data) {
            return $data;
        }

        $assets = UserAsset::getRoleAuthorise(Auth::user()->role_id);
        $menus = DB::table('menu')->orderBy('lft', 'asc')->get();
        $menus = array_tree($menus);

        $positions = [];

        foreach ($menus as $menuId => &$menu) {
            if ($menu['children']) {
                // 二级菜单
                foreach ($menu['children'] as $groupId => &$group) {
                    if ($group['url']) {
                        $group['url'] = str_replace('.', '/', $group['url']);
                        if ($group['access'] == 0 || isset($assets[$group['url']])) {
                            $menu['selected']   = 1;
                            $group['selected']  = 1;
                        }
                    }

                    if ($group['children']) {
                        // 三级菜单
                        foreach ($group['children'] as $actionId => &$action) {
                            $action['url'] = str_replace('.', '/', $action['url']);

                            $positions[$action['url']] = $menuId.','.$groupId.','.$actionId;

                            if ($action['access'] == 0 || isset($assets[$action['url']])) {
                                if (empty($group['url'])) {
                                    $group['url'] = $action['url'];
                                }
                                $menu['selected']   = 1;
                                $group['selected']  = 1;
                                $action['selected'] = 1;
                            }
                        }
                    }
                }
            }
        }
        $menus[0]['selected'] = 0;
        $data['children'] = $menus;
        $data['left']     = $left;

        return $data;
    }
}
