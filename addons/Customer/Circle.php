<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Circle extends BaseModel
{
    protected $table = 'customer_circle';

    /**
     * 设置字段黑名单
     */
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo('Aike\Customer\Circle');
    }

    public function scopeDialog($q, $value)
    {
        return $q->whereIn('id', $value)
        ->pluck('name', 'id');
    }
}
