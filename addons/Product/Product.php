<?php namespace Aike\Product;

use DB;
use Aike\Index\BaseModel;

class Product extends BaseModel
{
    protected $table = 'product';

    public function boms()
    {
        return $this->belongsToMany('Aike\Product\Product', 'product_bom', 'product_id', 'goods_id');
    }

    public function scopeType($query, $type = 1)
    {
        $types['sale']     = 1;
        $types['supplier'] = 2;
        return $query->LeftJoin('product_category', 'product_category.id', '=', 'product.category_id')
        ->where('product_category.type', $types[$type]);
    }

    public function warehouse($query)
    {
        return $this->belongsTo('Aike\Product\Warehouse');
    }

    /**
     * 获取当前启用的产品列表
     */
    public static function gets($category_id = 0, $product_id = 0, $status = 1, $search = array())
    {
        $db = DB::table('product AS p')
        ->whereRaw('p.status=?', [$status]);

        if ($category_id) {
            $category_id = is_array($category_id) ? $category_id : explode(',', $category_id);
            $db->whereIn(DB::raw('p.category_id'), $category_id);
        }
        if ($product_id > 0) {
            $db->whereRaw('p.id=?', [$product_id]);
        }

        // 搜索产品
        if (!empty($search['key']) && !empty($search['value'])) {
            $value = $search['condition'] == 'like' ? '%'.$search['value'].'%' : $search['value'];
            $db->whereRaw($search['key'].' '.$search['condition'].'?', [$value]);
        }
        $rows = $db->LeftJoin('product_category AS pc', DB::raw('pc.id'), '=', DB::raw('p.category_id'))
        ->groupBy(\DB::raw('p.id'))
        ->orderByRaw('pc.lft ASC,p.sort ASC')
        ->selectRaw('p.*,pc.name AS category_name')
        ->get();

        $data = [];
        if (sizeof($rows)) {
            foreach ($rows as $row) {
                $data[$row['id']] = $row;
            }
        }
        unset($rows);
        return $data;
    }
}
