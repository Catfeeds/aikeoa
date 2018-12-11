<?php namespace Aike\Asset;

use Aike\Index\BaseModel;

class AssetData extends BaseModel
{
    protected $table = 'asset_data';

    public function status($id = 0)
    {
        $items = [
            ['id' => 1, 'name' => '正常'],
            ['id' => 2, 'name' => '库存'],
            ['id' => 3, 'name' => '维修'],
            ['id' => 4, 'name' => '报废'],
            ['id' => 5, 'name' => '转移'],
        ];
        return $id > 0 ? $items[$id] : $items;
    }
}
