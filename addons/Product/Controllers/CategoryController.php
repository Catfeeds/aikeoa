<?php namespace Aike\Product\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Product\ProductCategory;
use Aike\Product\Warehouse;

use Aike\Index\Controllers\DefaultController;

class CategoryController extends DefaultController
{
    public $permission = ['dialog'];
    
    // 分类列表
    public function indexAction()
    {
        $search = search_form([
            'referer' => 1,
        ]);

        if (Request::method() == 'POST') {
            $gets = Input::get('id');
            foreach ($gets as $id => $sort) {
                ProductCategory::where('id', $id)->update(['sort' => $sort]);
            }
            ProductCategory::treeRebuild();
        }
        $rows = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        return $this->display([
            'rows' => $rows,
        ]);
    }

    // 添加分类
    public function addAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();
            
            $model = ProductCategory::findOrNew($gets['id']);
            
            $rules = array(
                'name' => 'required',
                'code' => 'required|unique:product_category,code,'.$gets['id']
            );
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            $model->fill($gets)->save();
   
            $model->treeRebuild();
            
            return $this->success('index', '恭喜你，分类更新成功。');
        }
        
        $id = (int)Input::get('id');
        $categorys  = ProductCategory::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $warehouses = Warehouse::type('sale')->orderBy('lft', 'asc')->get()->toNested();
        $category = ProductCategory::find($id);
        return $this->display(array(
            'categorys'  => $categorys,
            'warehouses' => $warehouses,
            'category'   => $category,
        ));
    }

    // 删除产品类别
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('很抱歉，编号不正确无法显示。');
        }
        $res = DB::table('product')->where('category_id', $id)->first();
        if (!empty($res)) {
            return $this->error('很抱歉，分类下面存在产品，无法删除。');
        }

        DB::table('product_category')->where('id', $id)->delete();
        return $this->success('category', '恭喜你，分类删除成功。');
    }

    /**
     * 弹出层信息
     */
    public function dialogAction()
    {
        $gets = Input::get();
        $type = Input::get('type', 1);
        
        if (Request::method() == 'POST' || Input::get('data_type') == 'json') {
            $model = ProductCategory::orderBy('lft', 'asc');
            $maps = $model->where('type', $type)->get()->toNested();
            $rows = [];
            foreach ($maps as $row) {
                $rows[] = $row;
            }
            $json = ['total' => count($rows), 'rows' => $rows];
            return response()->json($json);
        }

        return $this->render(array(
            'gets' => $gets,
        ));
    }
}
