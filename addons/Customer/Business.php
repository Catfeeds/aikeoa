<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Business extends BaseModel
{
    protected $table = 'customer_business';

    public static $_messages = [
        'name.required' => '客户名称必须填写',
        'user_id.required' => '负责人必须选择',
    ];
}
