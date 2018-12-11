<?php namespace Aike\Asset\Controllers;

use Input;
use Auth;
use Request;
use Validator;

use Aike\User\User;
use Aike\Asset\Asset;
use Aike\Asset\AssetData;
use Aike\Asset\AssetDataLog;
use Aike\Index\Controllers\DefaultController;

class DataController extends DefaultController
{
    public $permission = ['print'];

    public function indexAction($deleted = 0)
    {
        $query = [
            'status'           => 1,
            'asset_id'         => 0,
            'department_id'    => 0,
            'user_id'          => 0,
            'search_key'       => '',
            'search_condition' => '',
            'search_value'     => '',
            'referer'          => 1,
        ];
        foreach ($query as $key => $filter) {
            $query[$key] = Input::get($key, $filter);
        }

        $search = search_form([
            'referer' => 1,
            'status'  => 1
        ], [
            ['text','user.nickname','使用人'],
            ['text','asset_data.name','品牌'],
            ['text','asset_data.model','型号'],
            ['text','asset_data.number','识别码'],
            ['asset','asset_data.asset_id','类别'],
            ['department','user.department_id','部门'],
            ['text','asset_data.id','ID'],
        ]);

        $query = $search['query'];

        $model = AssetData::orderBy('asset_data.id', 'desc')
        ->LeftJoin('user', 'user.id', '=', 'asset_data.use_user_id')
        ->where('asset_data.deleted', $deleted);

        // 搜索条件
        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        if ($query['status'] > 0) {
            $model->where('asset_data.status', $query['status']);
        } else {
            $model->where('asset_data.status', '>', 0);
        }

        $level = User::authoriseAccess('index');
        if ($level) {
            $asset = Asset::where('user_id', Auth::id())->pluck('id');
            if ($asset->count()) {
                $model->whereIn('asset_data.asset_id', $asset);
            } else {
                $model->whereIn('asset_data.use_user_id', $level);
            }
        }

        // 类别管理员
        $rows = $model->select(['asset_data.*'])->paginate()->appends($query);
        
        $assets = Asset::get()->keyBy('id')->toArray();

        $status = AssetData::status();

        $tabs = [
            'name'  => 'status',
            'items' => $status
        ];
        
        return $this->display([
            'rows'   => $rows,
            'assets' => $assets,
            'query'  => $query,
            'search' => $search,
            'status' => array_by($status),
            'tabs'   => $tabs,
        ], 'index');
    }

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
                AssetData::where('id', $gets['id'])->update($gets);
            } else {
                AssetData::insert($gets);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }

        $row = AssetData::find($gets['id']);
        $assets = Asset::get();
        $status = AssetData::status();

        return $this->display(array(
            'assets' => $assets,
            'status' => $status,
            'row'    => $row,
        ));
    }

    public function viewAction()
    {
        $gets = Input::get();
        $row = AssetData::find($gets['id']);
        $logs = AssetDataLog::where('data_id', $gets['id'])->get();
        $assets = Asset::get()->keyBy('id');
        $status = AssetData::status();

        $qrcode = url('index/api/qrcode', ['size'=>6,'data'=>'asset:'.$row['id']]);

        $client = Input::get('client', 'web');

        $tpl = $client == 'app' ? 'mobile/view' : 'view';

        return $this->display(array(
            'logs'   => $logs,
            'row'    => $row,
            'assets' => $assets,
            'status' => $status,
            'qrcode' => $qrcode,
        ), $tpl);
    }

    /**
     * 统计
     */
    public function countAction()
    {
        $gets = Input::get();

        $rows = AssetData::where('status', '!=', 4)->where('deleted', '=', 0)->get();
        $assets = Asset::get();

        $data = [];
        foreach ($rows as $_rows) {
            $department_id = get_user($_rows->use_user_id, 'department_id');
            
            $asset_id = $_rows->asset_id;
            
            if ($_rows->status == 1) {
                $data[$asset_id][$department_id]['count']++;
                $data[$asset_id]['count_a']++;
            } elseif ($_rows->status == 2) {
                $data[$asset_id]['count_b']++;
            }
        }

        return $this->display(array(
            'data'   => $data,
            'assets' => $assets,
        ));
    }

    // 回收站列表
    public function trashAction()
    {
        return $this->indexAction(1);
    }

    // 打印资产
    public function printAction()
    {
        $gets = Input::get();
        $row = AssetData::find($gets['id']);
        $logs = AssetDataLog::where('data_id', $gets['id'])->get();
        $assets = Asset::get()->keyBy('id');
        $status = AssetData::status();

        $this->layout = 'layouts.print';
        return $this->display(array(
            'logs'   => $logs,
            'row'    => $row,
            'assets' => $assets,
            'status' => $status,
        ));
    }

    // 软删除和恢复
    public function deleteAction()
    {
        $id     = Input::get('id');
        $status = Input::get('status');
        $data   = AssetData::where('id', $id)->update([
            'deleted' => $status,
        ]);
        return $this->success('index', '恭喜你，操作成功。');
    }

    // 销毁资产
    public function destroyAction()
    {
        $id  = Input::get('id');
        $data = AssetData::find($id);

        if (empty($data)) {
            return $this->error('很抱歉，没有找到相关记录。');
        }

        $data->delete();
        return $this->success('index', '恭喜你，操作成功。');
    }
}
