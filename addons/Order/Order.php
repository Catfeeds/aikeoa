<?php namespace Aike\Order;

use Aike\Index\BaseModel;

class Order extends BaseModel
{
    protected $table = 'order';
    
    public function promotions()
    {
        return $this->hasMany('Aike\Promotion\Promotion', 'customer_id', 'customer_id');
    }

    public function approachs()
    {
        return $this->hasMany('Aike\Approach\Approach', 'customer_id', 'customer_id');
    }

    public function datas()
    {
        return $this->hasMany('Aike\Order\OrderData');
    }
}
