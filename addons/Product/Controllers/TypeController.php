<?php namespace Aike\Product\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Product\StockType;

use Aike\Index\Controllers\DefaultController;

class TypeController extends DefaultController
{
    public $permission = ['dialog'];

    // 库存类型
    public function indexAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get('sort');
            foreach ($gets as $id => $get) {
                $data['sort'] = $get;
                StockType::where('id', $id)->update($data);
            }
            return $this->back('恭喜你，排序完成。');
        }

        $search = search_form([
            'referer' => 1,
        ], []);

        $rows = StockType::orderBy('lft', 'asc')->get()->toNested();
        return $this->display(array(
            'rows' => $rows,
        ));
    }

    // 添加库存类型
    public function addAction()
    {
        $id = (int)Input::get('id');
        
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $model = StockType::findOrNew($gets['id']);
            $rules = [
                'title' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            $model->fill($gets)->save();
            $model->treeRebuild();
            return $this->success('index', '恭喜你，类型更新成功。');
        }

        $types = StockType::orderBy('lft', 'asc')->get()->toNested();
        $row = StockType::where('id', $id)->first();

        return $this->display(array(
            'type' => $types,
            'row'  => $row,
        ));
    }

    /**
     * 库存类型选项
     */
    public function dialogAction()
    {
        $type = Input::get('type');
        $model = StockType::orderBy('lft', 'asc');
        if ($type) {
            $model->where('type', $type);
        }
        $rows = $model->get()->toNested('title');

        $json = [];
        foreach ($rows as $row) {
            $row['text'] = $row['title'];
            $json[] = $row;
        }
        return response()->json($json);
    }
    
    // 删除库存类型
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('很抱歉，编号不正确。');
        }
        StockType::where('id', $id)->delete();
        return $this->success('index', '恭喜你，类型删除成功。');
    }
}
