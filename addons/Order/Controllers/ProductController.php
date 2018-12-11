<?php namespace Aike\Order\Controllers;

use DB;
use Input;
use Request;
use Auth;
use Validator;

use Aike\Supplier\Product;
use Aike\Supplier\ProductCategory;
use Aike\Supplier\Warehouse;

use Aike\Index\Controllers\DefaultController;

class ProductController extends DefaultController
{
    public $permission = ['dialog'];

    // 产品列表
    public function indexAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get('id');
            foreach ($gets as $id => $sort) {
                Product::where('id', $id)->update(['sort' => $sort]);
            }
        }

        $query = array('referer' => 1,'category_id'=>0,'warehouse_id'=>0,'status'=>1,'search_key'=>'','search_condition'=>'','search_value'=>'');
        foreach ($query as $k => $v) {
            $query[$k] = Input::get($k, $v);
        }
        extract($query, EXTR_PREFIX_ALL, 'q');

        $model = DB::table('product as p')
        ->LeftJoin('product_category as pc', 'pc.id', '=', 'p.category_id')
        ->LeftJoin('warehouse as psw', 'psw.id', '=', 'p.warehouse_id')
        ->where('pc.type', 1)
        ->where('p.status', $q_status)
        ->orderBy('pc.lft', 'asc')
        ->orderBy('p.sort', 'asc');

        if ($q_category_id > 0) {
            $category = ProductCategory::where('id', $q_category_id)->first();
            $model->whereBetween('pc.lft', [$category->lft, $category->rgt]);
        }

        if ($q_warehouse_id > 0) {
            $warehouse = Warehouse::where('id', $q_warehouse_id)->first();
            $model->whereBetween('psw.lft', [$warehouse->lft, $warehouse->rgt]);
        }

        if ($q_search_key && $q_search_value) {
            $value = $q_search_condition == 'like' ? '%'.$q_search_value.'%' : $q_search_value;
            $model->whereRaw($q_search_key.' '.$q_search_condition.' ?', [$value]);
        }

        $rows = $model->select(['p.*', 'pc.title as category_name'])
        ->paginate()->appends($query);

        $category  = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $warehouse = Warehouse::type('sale')->orderBy('lft', 'asc')->get()->toNested();

        set_referer($query);

        return $this->display(array(
            'rows'     => $rows,
            'category' => $category,
            'warehouse'=> $warehouse,
            'query'    => $query,
        ));
    }

    // 添加产品
    public function addAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            
            $model = Product::findOrNew($gets['id']);
            
            $rules = [
                'name' => 'required',
                'category_id'  => 'required',
                'stock_code'   => 'required|unique:product,stock_code,'.$gets['id'],
                'level_amount' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            
            // 上传图片
            $image = image_create('products', 'image', $model['image']);
            if ($image) {
                $gets['image'] = $image;
            }
            $model->fill($gets)->save();
            
            return $this->success('index', '恭喜你，产品更新成功。');
        }

        $id = (int)Input::get('id');
        $res = DB::table('product')->where('id', $id)->first();

        $category = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $warehouse = Warehouse::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        
        return $this->display(array(
            'warehouse' => $warehouse,
            'category'  => $category,
            'res'       => $res,
        ));
    }

    /**
     * 弹出层信息
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
            ['text','product.barcode','产品条码'],
            ['category','product.category_id','产品类别'],
        ]);
        $query  = $search['query'];

        if (Request::method() == 'POST' || Request::isJson() || $gets['isjson']) {
            $model = DB::table('product')
            ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
            ->where('product_category.type', 1);

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy('product.'.$query['sort'], $query['order']);
            }

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $json['total'] = $model->count();

            $rows = $model->skip($query['offset'])->take($query['limit'])->get(['product.*', 'product_category.name as category_name']);

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

    // 删除产品
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('编号不正确。');
        }

        $product = DB::table('product')->where('id', $id)->first();

        // 删除图片
        image_delete($product['image']);

        // 删除数据
        DB::table('product')->where('id', $id)->delete();
        return $this->success('index', '恭喜你，产品删除成功。');
    }
}
