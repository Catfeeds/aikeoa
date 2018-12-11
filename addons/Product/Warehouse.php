<?php namespace Aike\Product;

use Aike\Index\BaseModel;

class Warehouse extends BaseModel
{
    protected $table = 'warehouse';

    public function user()
    {
        return $this->belongsTo('Aike\User\User');
    }

    /*
     * 筛选仓库类型 1 为销售，2为供应
     */
    public function scopeType($query, $type = 1)
    {
        $types['sale']     = 1;
        $types['supplier'] = 2;
        return $query->where('type', $types[$type]);
    }

    /*
     * 获取仓库管理员所有管理的仓库(包括继承)
     */
    public function scopeManager($query, $user_id = 0)
    {
        $warehouses = $query->orderBy('lft', 'asc')->get()->toNested();
        $warehouseIds = [];

        if ($user_id == 0) {
            $user_id = auth()->id();
        }

        foreach ($warehouses as $warehouse) {
            if ($warehouse['user_id'] == $user_id) {
                $warehouseIds[] = $warehouse['id'];

                // 寻找继承的子仓库
                foreach ($warehouse['child'] as $warehouseId) {
                    if ($warehouses[$warehouseId]['user_id'] == '') {
                        $warehouseIds[] = $warehouseId;
                    }
                }
            }
        }
        return $warehouseIds;
    }
}
