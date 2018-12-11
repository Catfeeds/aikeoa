<?php namespace Aike\Promotion;

use Aike\Index\BaseModel;

class PromotionMaterial extends BaseModel
{
    protected $table = 'promotion_material';

    public function promotion()
    {
        return $this->belongsTo('Aike\Promotion\Promotion');
    }

    public function contact()
    {
        return $this->belongsTo('Aike\Customer\Contact');
    }
}
