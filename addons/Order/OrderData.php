<?php namespace Aike\Order;

use Aike\Index\BaseModel;

class OrderData extends BaseModel
{
    protected $table = 'order_data';

    public function product()
    {
        return $this->belongsTo('Aike\Product\Product');
    }
}
