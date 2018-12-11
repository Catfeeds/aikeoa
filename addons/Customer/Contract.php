<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Contract extends BaseModel
{
    protected $table = 'customer_contract';

    public function user()
    {
        return $this->belongsTo('Aike\User\User', 'customer_id');
    }
}
