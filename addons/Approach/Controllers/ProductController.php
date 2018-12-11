<?php namespace Aike\Approach\Controllers;

use Input;
use Request;
use DB;

use Aike\Supplier\Product;
use Aike\Supplier\ProductCategory;
use Aike\Supplier\Warehouse;

use Aike\Index\Controllers\DefaultController;

class ProductController extends DefaultController
{
    public $permission = ['dialog'];
    /**
     * 产品对话框
     */
    public function dialogAction()
    {
        $gets = Input::get();

        $search = search_form([
            'offset'    => '',
            'sort'      => '',
            'order'     => '',
            'limit'     => '',
        ], [
            ['text','product.name','产品名称'],
            ['text','product.spec','产品规格'],
            ['text','product.id','产品编号'],
            ['category','product.category_id','产品类别'],
        ]);
        $query  = $search['query'];

        if (Request::method() == 'POST') {
            $model = DB::table('product');

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy($query['sort'], $query['order']);
            }

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $json['total'] = $model->count();

            $rows = $model->skip($query['offset'])->take($query['limit'])->get();

            foreach ($rows as &$row) {
                $row['text'] = $row['name'].$row['spec'];
            }
            $json['rows'] = $rows;

            return response()->json($json);
        }
        return $this->render(array(
            'search' => $search,
        ));
    }
}
