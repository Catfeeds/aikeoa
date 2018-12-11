<?php namespace Aike\Car\Controllers;

use DB;
use Request;
use Input;
use Validator;
use Auth;

use Aike\Index\Controllers\DefaultController;

class RefuelController extends CarController
{
    /**
     * 加油记录
     */
    public function indexAction()
    {
        $query = array(
            'car_id'      => 0,
            'car_user_id' => '',
            'start_at'    => '',
            'end_at'      => ''
        );
        foreach ($query as $k => $v) {
            $query[$k] = Input::get($k, $v);
        }

        $access = authorise();

        $model = DB::table('car_refuel as cr')
        ->LeftJoin('car as c', 'c.id', '=', 'cr.car_id')
        ->orderBy('cr.id', 'desc');

        if ($query['car_id'] > 0) {
            $model->where('cr.car_id', $query['car_id']);
        }

        if ($access < 4) {
            $model->where('cr.created_by', Auth::id());
        } else {
            if ($query['car_user_id'] > 0) {
                $model->where('cr.created_by', $query['car_user_id']);
            }
        }
        $rows = $model->select(['cr.*', 'c.car_number'])
        ->paginate()
        ->appends($query);

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $cars = DB::table('car');
        if ($access < 4) {
            $cars->whereRaw('FIND_IN_SET(?, car_user_id)', [Auth::id()]);
        }
        $cars = $cars->get();

        $car_user = array();
        $users = DB::table('car')->get(['car_user_id']);
        foreach ($users as $_user) {
            $user = explode(',', $_user['car_user_id']);
            foreach ($user as $user_id) {
                $car_user[$user_id] = $user_id;
            }
        }
        return $this->display(array(
            'query'    => $query,
            'car_user' => $car_user,
            'rows'     => $rows,
            'cars'     => $cars,
        ));
    }

    /**
     * 显示加油
     */
    public function showAction()
    {
        $id = Input::get('id');
        $row = DB::table('car')
        ->LeftJoin('car_refuel', 'car.id', '=', 'car_refuel.car_id')
        ->where('car_refuel.id', $id)
        ->first(['car_refuel.*', 'car.car_number']);
        if ($row) {
            $attachment = array_filter(explode(',', $row['attachment']));
            $row['attachments'] = DB::table('car_attachment')->whereIn('id', $attachment)->get();
        }
        return response()->json($row);
    }

    /**
     * 新建加油记录
     */
    public function createAction()
    {
        $id = Input::get('id', 0);

        if (Request::method() == 'POST') {
            $posts = Input::get();

            $rules = array(
                'car_id' => 'required',
                'km'     => 'required',
                'lng'    => 'required',
                'lat'    => 'required',
                'money'  => 'required',
                'litre'  => 'required',
            );
            $validator = Validator::make($posts, $rules);

            if ($validator->fails()) {
                return $validator->messages()->toJson();
            }

            // 保存图片
            $images = $this->storeImages();
            if ($images['status'] == 1) {
                $posts['attachment'] = $images['data'];
            } else {
                return response()->json($images['data']);
            }

            if ($posts['id'] > 0) {
                DB::table('car_refuel')->where('id', $posts['id'])->update($posts);
            } else {
                DB::table('car_refuel')->insert($posts);
            }
            return response()->json(array('status'=>1));
        }

        if (Input::get('data_type') == 'json') {
            $cars = DB::table('car')->whereRaw('FIND_IN_SET(?,car_user_id)', [Auth::id()])->get();
            return response()->json($cars);
        }
        $row = DB::table('car_refuel')->where('id', $id)->first();
        return $this->display(array(
            'row' => $row,
        ));
    }

    // 保存库存数据
    public function storeAction()
    {
        if (Input::isJson()) {
            $gets = json_decode(Request::getContent(), true);
        } else {
            $gets = Input::get();
        }

        $rules = [
            'car_id'     => 'required',
            'km'         => 'required',
            'lng'        => 'required',
            'lat'        => 'required',
            'money'      => 'required',
            'litre'      => 'required',
            //'attachment' => 'min:1|array|required',
        ];
        
        $v = Validator::make($gets, $rules);
        if ($v->fails()) {
            return $this->json($v->errors());
        }

        if (is_array($gets['attachment'])) {
            $gets['attachment'] = attachment_base64('car_attachment', $gets['attachment'], 'car');
        } else {
            $gets['attachment'] = attachment_images('car_attachment', 'image', 'car');
        }

        // $gets['attachment'] = attachment_base64('car_attachment', $gets['attachment'], 'car');
        DB::table('car_refuel')->insert($gets);
        return $this->json('数据上传成功。', true);
    }

    public function deleteAction()
    {
        $id = Input::get('id', 0);
        if ($id > 0) {
            DB::table('car_refuel')->where('id', $id)->delete();
            return $this->success('index', '恭喜你，操作成功。');
        }
    }
}
