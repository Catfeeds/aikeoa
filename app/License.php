<?php namespace App;

use Aike\User\User;
use Aike\Customer\Customer;

class License
{
    public function check($type)
    {
        $data = [
            'user'     => 9999,
            'customer' => 9999,
        ];

        if ($type == 'user') {
            $count = User::group('user')->count('id');
            if ($count > $data['user']) {
                abort_error('无法新建用户授权许可不足。');
            }
        } else if ($type == 'customer') {
            $count = Customer::count('id');
            if ($count > $data['customer']) {
                abort_error('无法新建客户授权许可不足。');
            }
        }
    }

    /**
     * 设置演示表，操作时候进行判断
     */
    public function demoCheck($table)
    {
        if (env('DEMO_VERSION') == false) {
            return true;
        }

        $demoDatas = [
            'role',
            'department',
            'widget',

            'user',
            'user_asset',
            'user_group',
            'user_position',
            'user_widget',

            'work',
            'work_step',
            'work_category',

            'menu',

            'product',
            'product_category',

            'option',

            'model',
            'model_field',
            'model_permission',
            'model_step',
            'model_template',

            'region',
            'mail',
            
            'warehouse',
            
            'setting',
        ];

        if (in_array($table, $demoDatas)) {
            return false;
        }
        
        return true;
    }
}
