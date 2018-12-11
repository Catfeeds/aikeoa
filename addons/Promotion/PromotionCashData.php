<?php namespace Aike\Promotion;

use Aike\Index\BaseModel;
use Aike\Promotion\PromotionCash;

class PromotionCashData extends BaseModel
{
    protected $table = 'promotion_cash_data';

    public function cash()
    {
        return $this->belongsTo(PromotionCash::class);
    }
}
