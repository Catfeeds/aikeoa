<?php namespace Aike\User;

use Aike\Index\BaseModel;

class Message extends BaseModel
{
    protected $table = 'user_message';

    static public $tabs = [];

    static public $bys = [
        'name'  => 'status',
        'items' => [
            ['value' => '', 'name' => '全部'],
            ['value' => 'unread', 'name' => '未读'],
            ['value' => 'read', 'name' => '已读'],
        ]
    ];
}
