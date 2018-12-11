<?php namespace Aike\Model;

use Aike\Index\BaseModel;

class Model extends BaseModel
{
    protected $table = 'model';

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function children()
    {
        return $this->hasMany(Model::class, 'parent_id');
    }
    
    public function regulars()
    {
        return [
            'required'               => '必填',
            'numeric'                => '数字',
            'integer'                => '整数',
            'alpha'                  => '字母',
            'date'                   => '日期',
            'alpha_num'              => '数字+字母',
            'email'                  => '邮箱',
            'active_url'             => '链接',
            'regex:/^[0-9]{5,20}$/'  => 'QQ',
            'regex:/^(1)[0-9]{10}$/' => '手机',
            'regex:/^[0-9-]{6,13}$/' => '电话',
            'regex:/^[0-9]{6}$/'     => '邮编',
        ];
    }
}
