<?php namespace Aike\Asset\Controllers;

use Input;
use Request;
use Validator;

use Aike\Asset\Asset;
use Aike\Asset\AssetData;
use Aike\Asset\AssetDataLog;
use Aike\Index\Controllers\DefaultController;

class LogController extends DefaultController
{
    public function indexAction()
    {
    }

    public function createAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $_data['use_user_id'] = $gets['user_id'];
            $_data['status'] = 1;

            // 收回默认给类别管理者
            if ($gets['type'] == 3) {
                $data  = AssetData::find($gets['data_id']);
                $asset = Asset::find($data->asset_id);
                $_data['use_user_id'] = $gets['user_id'] = $asset->user_id;
                $_data['status'] = 2;
            }

            $rules = [
                'user_id' => 'required',
            ];

            $validator = Validator::make($gets, $rules);
            if ($validator->fails()) {
                return $this->back()->withErrors($validator)->withInput();
            }

            // 编辑或创建
            if ($gets['id'] > 0) {
                AssetDataLog::where('id', $gets['id'])->update($gets);
            } else {
                AssetDataLog::insert($gets);
            }

            // 更新资产表
            AssetData::where('id', $gets['data_id'])->update($_data);

            return $this->success('data/view', ['id'=>$gets['data_id']], '恭喜你，操作成功。');
        }

        $row = AssetDataLog::find($gets['id']);
        return $this->display([
            'data_id' => $gets['data_id'],
            'row'     => $row,
        ]);
    }

    public function viewAction()
    {
        $gets = Input::get();
        $row = AssetData::find($gets['id']);
        $logs = AssetDataLog::where('data_id', $gets['id']);
        $assets = Asset::get()->keyBy('id');

        return $this->display(array(
            'logs'   => $logs,
            'row'    => $row,
            'assets' => $assets,
        ));
    }

    public function deleteAction()
    {
        $id  = Input::get('id');
        $log = AssetDataLog::find($id);

        if (empty($log)) {
            return $this->error('很抱歉，没有找到相关记录。');
        }

        $log->delete();
        return $this->success('data/view', ['id'=>$log->data_id], '恭喜你，操作成功。');
    }
}
