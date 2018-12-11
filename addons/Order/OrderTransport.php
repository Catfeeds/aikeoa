<?php namespace Aike\Order;

use Aike\Index\BaseModel;

class OrderTransport extends BaseModel
{
    protected $table = 'order_transport';
    
    public function datas()
    {
        return $this->hasMany('Aike\Order\OrderData', 'order_id', 'order_id');
    }
}
