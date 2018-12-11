<?php namespace Aike\Product\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Product\Warehouse;

use Aike\Index\Controllers\DefaultController;

class WarehouseController extends DefaultController
{
    // 仓库列表
    public function indexAction()
    {
        // 更新排序
        if (Request::method() == 'POST') {
            $gets = Input::get('sort');
            foreach ($gets as $id => $get) {
                $data['sort'] = $get;
                Warehouse::where('id', $id)->update($data);
            }
        }

        $search = search_form([
            'referer' => 1,
        ], []);

        $rows = Warehouse::with('user')->orderBy('lft', 'asc')->get()->toNested();
        return $this->display(array(
            'rows' => $rows,
        ));
    }

    // 添加仓库
    public function addAction()
    {
        $id = (int)Input::get('id');
        
        if (Request::method() == 'POST') {
            $gets = Input::get();
            
            $gets['advert'] = (int)$gets['advert'];
            
            unset($gets['past_parent_id']);
            
            $model = Warehouse::findOrNew($gets['id']);
            $rules = [
                'title' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            $model->fill($gets)->save();
            $model->treeRebuild();
            return $this->success('index', '恭喜你，类别更新成功。');
        }

        $row = Warehouse::where('id', $id)->first();
        $types = Warehouse::orderBy('lft', 'asc')->get()->toNested();

        return $this->display(array(
            'type' => $types,
            'row'  => $row,
        ));
    }

    // 删除仓库
    public function deleteAction()
    {
        $id = Input::get('id');
        if ($id <= 0) {
            return $this->error('很抱歉，编号不正确。');
        }
        Warehouse::where('id', $id)->delete();
        return $this->success('index', '恭喜你，类别删除成功。');
    }
}
