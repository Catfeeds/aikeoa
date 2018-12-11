<?php namespace Aike\Approach;

use Aike\Index\BaseModel;

class Approach extends BaseModel
{
    protected $table = 'approach';

    public function customer()
    {
        return $this->belongsTo('Aike\Customer\Customer');
    }

    public function datas()
    {
        return $this->hasMany('Aike\Promotion\PromotionData');
    }
}
