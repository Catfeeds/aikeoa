<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Receivable extends BaseModel
{
    protected $table = 'customer_receivable';

    /**
     * 设置字段黑名单
     */
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo('Aike\Customer\Customer');
    }
}
