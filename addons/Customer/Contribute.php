<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Contribute extends BaseModel
{
    protected $table = 'customer_contribute';

    /**
     * 设置字段黑名单
     */
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo('Aike\Customer\Customer');
    }

    public function contact()
    {
        return $this->belongsTo('Aike\Customer\Contact');
    }
}
