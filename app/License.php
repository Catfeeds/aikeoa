<?php namespace App;

class License
{
    public function check($type, $count)
    {
        $license = [
            'role'     => 9999,
            'user'     => 9999,
            'supplier' => 9999,
            'customer' => 9999,
        ];
        return $count >= $license[$type] ? true : false;
    }

    /**
     * 设置演示表，操作时候进行判断
     */
    public function demoCheck($table)
    {
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

        if (env('DEMO_VERSION') == false) {
            return true;
        }

        if (in_array($table, $demoDatas)) {
            return false;
        }
        return true;
    }
}
