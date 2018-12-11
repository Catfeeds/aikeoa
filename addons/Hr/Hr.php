<?php namespace Aike\Hr;

use Aike\Index\BaseModel;

class Hr extends BaseModel
{
    protected $table = 'hr';

    public static $_status = [
        1 => '正常',
        2 => '试用',
        3 => '实习',
        //3 => '请假',
        4 => '离职',
    ];

    public static $_gender = [
        0 => '保密',
        1 => '男',
        2 => '女',
    ];

    public static $_messages = [
        'name.required' => '名字必须填写',
        'user_id.required' => '用户必须选择',
        'user_id.unique' => '用户已经存在',
        'position_id.required' => '职级必须选择'
    ];

    public function user()
    {
        return $this->belongsTo('Aike\User\User');
    }
}
