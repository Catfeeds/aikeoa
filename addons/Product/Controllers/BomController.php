<?php namespace Aike\Product\Controllers;

use DB;
use Input;
use Request;

use Aike\Supplier\Product;
use Aike\Supplier\ProductCategory;
use Aike\Supplier\Warehouse;

use Aike\Index\Controllers\DefaultController;

class BomController extends DefaultController
{
    public $permission = ['store'];

    // 编辑BOM单
    public function editAction()
    {
        $models = [
            ['name' => "id", 'hidden' => true],
            ['name' => 'os', 'label' => '&nbsp;', 'formatter' => 'options', 'width' => 60, 'sortable' => false, 'align' => 'center'],
            ['name' => "goods_id", 'hidden' => true, 'label' => '商品ID'],
            ['name' => "goods_name", 'width' => 280, 'label' => '商品', 'rules' => ['required'=>true], 'sortable' => false, 'editable' => true],
            ['name' => "quantity", 'label' => '数量', 'width' => 140, 'rules' => ['required' => true, 'minValue' => 1,'integer' => true], 'formatter' => 'integer', 'sortable' => false, 'editable' => true, 'align' => 'right'],
            ['name' => "remark", 'label' => '备注', 'width' => 200, 'sortable' => false, 'editable' => true]
        ];

        $product_id = Input::get('product_id');

        $rows = DB::table('product_bom')
        ->leftJoin('product', 'product.id', '=', 'product_bom.goods_id')
        ->where('product_bom.product_id', $product_id)
        ->selectRaw("product_bom.*,IF(product.spec='', product.name, concat(product.name,' - ', product.spec)) as goods_name")
        ->groupBy('goods_id')
        ->get();

        $data = json_encode($rows, JSON_UNESCAPED_UNICODE);

        return $this->display([
            'product_id' => $product_id,
            'models'     => $models,
            'data'       => $data,
        ]);
    }

    // 保存BOM单
    public function storeAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            DB::table('product_bom')->where('product_id', $gets['product_id'])->delete();

            $rows = $gets['rows'];

            foreach ($rows as $row) {
                $data = [
                    'product_id'  => $gets['product_id'],
                    'goods_id'    => $row['goods_id'],
                    'quantity'    => $row['quantity'],
                    'remark'      => $row['remark'],
                ];
                // 写入库数据表
                DB::table('product_bom')->insert($data);
            }
            return $this->json('DOM单保存成功。', true);
        }
    }
}
