<?php namespace Aike\Car\Controllers;

use DB;
use Request;
use Input;
use Validator;
use Auth;

use Aike\Index\Controllers\DefaultController;

class CarController extends DefaultController
{
    public $permission = ['photo','dialog','store'];

    /**
     * 车辆档案
     */
    public function indexAction()
    {
        $access  = authorise();
        $rows = DB::table('car');
        if ($access < 4) {
            $rows->whereRaw('FIND_IN_SET(?, car_user_id)', [Auth::id()]);
        }
        $rows = $rows->paginate();

        // 返回 json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        return $this->display(array(
            'rows' => $rows,
        ));
    }

    // 客户端显示车辆列表
    public function dialogAction()
    {
        $access = authorise('index');

        if ($access) {
            $rows = DB::table('car');
            if ($access < 4) {
                $rows->whereRaw('FIND_IN_SET(?, car_user_id)', [Auth::id()]);
            }
            $rows = $rows->get(['*','car_number as text']);

            // 返回 json
            if (Request::wantsJson()) {
                return json_encode($rows);
            }
        }
        return json_encode([]);
    }

    /**
     * 新建档案
     */
    public function createAction()
    {
        $id = Input::get('id', 0);

        if (Request::method() == 'POST') {
            $posts = Input::get();
            $rules = array(
                'car_number'          => 'required',
                'car_type'            => 'required',
                'car_driving_license' => 'required',
                'car_buy_date'        => 'required',
                'car_user_id'         => 'required',
            );
            $validator = Validator::make($posts, $rules);

            if ($validator->fails()) {
                return $this->back()->withErrors($validator)->withInput();
            }

            if ($posts['id'] > 0) {
                DB::table('car')->where('id', $posts['id'])->update($posts);
            } else {
                DB::table('car')->insert($posts);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }

        $row = DB::table('car')->where('id', $id)->first();
        return $this->display(array(
            'row' => $row,
        ));
    }

    /**
     * 显示附件图片
     */
    public function photoAction()
    {
        $gets = Input::get();
        $row = DB::table('car_'.$gets['type'])->where('id', $gets['id'])->first();
        $attachment = array_filter(explode(',', $row['attachment']));

        $rows = DB::table('car_attachment')->whereIn('id', $attachment)->get();
        
        return $this->render(array(
            'rows' => $rows,
        ), 'photo');
    }

    // 删除档案
    public function deleteAction()
    {
        $id = Input::get('id', 0);
        if ($id > 0) {
            DB::table('car')->where('id', $id)->delete();
            return $this->success('index', '恭喜你，操作成功。');
        }
    }
}
