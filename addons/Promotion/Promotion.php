<?php namespace Aike\Promotion;

use Aike\Index\BaseModel;

class Promotion extends BaseModel
{
    protected $table = 'promotion';

    public static $tabs = [
        'name'  => 'tab',
        'items' => [
            ['value' => 'promotion.index', 'url' => 'promotion/promotion/index', 'name' => '促销列表'],
        ]
    ];

    public static $bys = [
        'name'  => 'by',
        'items' => [
            ['value' => '', 'name' => '全部'],
            ['value' => 'divider'],
            ['value' => 'day', 'name' => '今日创建'],
            ['value' => 'week', 'name' => '本周创建'],
            ['value' => 'month', 'name' => '本月创建'],
        ]
    ];

    public function customer()
    {
        return $this->belongsTo('Aike\Customer\Customer');
    }

    public function datas()
    {
        return $this->hasMany('Aike\Promotion\PromotionData');
    }

    public function cashs()
    {
        return $this->hasMany('Aike\Promotion\PromotionCash');
    }

    public static $materials = [
        ['id' => 0, 'name' => '待审核', 'color' => 'default'],
        ['id' => 1, 'name' => '不合格', 'color' => 'info'],
        ['id' => 2, 'name' => '已审核', 'color' => 'success']
    ];

    public static $cashs = [
        ['id' => 1, 'name' => '现配', 'color' => 'default'],
        ['id' => 2, 'name' => '凭兑', 'color' => 'info'],
    ];

    public static $status = [
        ['id' => 0, 'name' => '待审', 'color' => 'default'],
        ['id' => 1, 'name' => '已审', 'color' => 'info'],
    ];
}
