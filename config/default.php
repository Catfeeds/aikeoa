<?php

return array(
    'regular' => array(
       'SYS_NOT_NULL' => array('title'=>'不能为空 ','regex'=>''),
       'SYS_CN'       => array('title'=>'只能为中文','regex'=>'/^[\x{4e00}-\x{9fa5}]+$/u'),
       'SYS_EN'       => array('title'=>'只能为英文','regex'=>'/^[A-Za-z]+$/'),
       'SYS_NUM'      => array('title'=>'只能为数字','regex'=>'/^([+-]?)\d*\.?\d+$/'),
       'SYS_IDCARD'   => array('title'=>'只能为身份证','regex'=>'/^(\d{18,18}|\d{15,15}|\d{17,17}x)$/'),
       'SYS_MOBILET'  => array('title'=>'只能为手机号码','regex'=>'/^(1)[0-9]{10}$/'),
       'SYS_MONEY'    => array('title'=>'只能为金额','regex'=>'/^[0-9]{1,9}(\.[0-9]{1,2})?$/'),
       'SYS_PHONE'    => array('title'=>'只能为电话号码','regex'=>'/^[+]{0,1}(\d){1,4}[ ]{0,1}([-]{0,1}((\d)|[ ]){1,12})+$/'),
       'SYS_ZIPCODE'  => array('title'=>'只能为邮政编码','regex'=>'/^[1-9]\d{5}$/'),
       'SYS_EMAIL'    => array('title'=>'只能为Email','regex'=>'/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/'),
    ),
    // 连接用友配置
    'ufida' => array(
        // 插件状态
        'status' => 1,
        // 连接用友的密钥
        'key' => 'shenghuafood.com_+123456789asd!@#$',
        // 导出订单URL
        'export_order' => '118.122.82.249:4566',
        // 导出库存URL
        'export_stock' => '118.122.82.249:4568',
        // 同步订单到用友(外账税务)
        'sync_yonyou'  => '118.122.82.249:4569',
    ),
);
