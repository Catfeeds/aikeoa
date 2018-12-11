<?php namespace Aike\Asset\Controllers;

use Input;
use Request;
use Validator;

use Aike\Asset\Asset;
use Aike\Index\Controllers\DefaultController;

class AssetController extends DefaultController
{
    // 资产类别
    public function indexAction()
    {
        $rows = Asset::paginate();
        return $this->display(array(
            'rows' => $rows,
        ));
    }

    // 新建类别
    public function createAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $rules = [
                'name' => 'required',
            ];

            $validator = Validator::make($gets, $rules);
            if ($validator->fails()) {
                return $this->back()->withErrors($validator)->withInput();
            }

            // 编辑或创建
            if ($gets['id'] > 0) {
                Asset::where('id', $gets['id'])->update($gets);
            } else {
                Asset::insert($gets);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }

        $row = Asset::find($gets['id']);
        return $this->display(array(
            'assets' => $assets,
            'row'    => $row,
        ));
    }

    // 删除类别
    public function deleteAction()
    {
        $id  = Input::get('id');
        $row = Asset::find($id);
        if (empty($row)) {
            return $this->error('很抱歉，没有找到相关记录。');
        }

        $row->delete();
        return $this->success('index', '恭喜你，操作成功。');
    }
}
