<?php namespace Aike\Order\Controllers;

use DB;
use Input;
use Request;
use Auth;
use Validator;

use Aike\Order\OrderType;

use Aike\Index\Controllers\DefaultController;

class TypeController extends DefaultController
{
    // 分类列表
    public function indexAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get('sort');
            foreach ($gets as $id => $get) {
                $type = OrderType::find($id);
                $type->sort = $get;
                $type->save();
            }
            OrderType::treeRebuild();
            return $this->back('恭喜你，排序完成。');
        }

        $rows = OrderType::orderBy('lft', 'asc')->get()->toNested();
        return $this->display(array(
            'rows' => $rows,
        ));
    }

    public function addAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $rules = array(
                'title' => 'required'
            );
            $validator = Validator::make($gets, $rules);
            if ($validator->fails()) {
                return $this->back()->withErrors($validator)->withInput();
            }

            $type = OrderType::firstOrNew(array('id'=>$gets['id']));
            foreach ($gets as $key => $get) {
                $type->setAttribute($key, $get);
            }
            $type->save();
            OrderType::treeRebuild();
            
            return $this->success('index', '恭喜你，操作成功。');
        }

        $type = OrderType::orderBy('lft', 'asc')->get()->toNested();
        $row = OrderType::find($gets['id']);
        return $this->display(array(
            'type' => $type,
            'row'  => $row,
        ));
    }

    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id > 0) {
            OrderType::delete($id);
            return $this->success('index', '恭喜你，操作成功。');
        }
    }
}
