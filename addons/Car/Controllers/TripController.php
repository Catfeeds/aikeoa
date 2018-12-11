<?php namespace Aike\Car\Controllers;

use Auth;
use DB;
use Request;
use Input;
use Validator;

use Aike\Index\Controllers\DefaultController;

class TripController extends CarController
{
    /**
     * 行程记录
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

        // 查询出发前
        $model = DB::table('car_trip as ct')
        ->LeftJoin('car as c', 'c.id', '=', 'ct.car_id')
        ->where('ct.category_id', 1)
        ->selectRaw('ct.*,c.car_number,FROM_UNIXTIME(ct.created_at, "%Y-%m-%d") as date')
        ->orderBy('ct.id', 'desc');

        if ($query['car_id'] > 0) {
            $model->where('ct.car_id', $query['car_id']);
        }

        if ($query['car_user_id'] > 0) {
            $model->where('ct.created_by', $query['car_user_id']);
        }

        $access = authorise();
        if ($access < 4) {
            $model->where('ct.created_by', Auth::id());
        }

        $rows = $model->paginate()->appends($query);

        // 出发前编号
        $parents = $model->pluck('id');

        // 查询回驻地
        $backs = $model = DB::table('car_trip as ct')
        ->LeftJoin('car as c', 'c.id', '=', 'ct.car_id')
        ->where('ct.category_id', 2)
        ->whereIn('ct.parent_id', $parents)
        ->selectRaw('ct.*,c.car_number, FROM_UNIXTIME(ct.created_at, "%Y-%m-%d") as date')
        ->get();
        $backs = array_by($backs, 'parent_id');

        foreach ($rows as $key => $row) {
            $row['back'] = $backs[$row['id']];
            $rows->put($key, $row);
        }

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $cars = DB::table('car');
        if ($access < 4) {
            $cars->whereRaw('FIND_IN_SET(?,car_user_id)', [Auth::id()]);
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
            'cars'     => $cars,
            'rows'     => $rows,
            'items'    => $items,
        ));
    }

    /**
     * 查看行程
     */
    public function showAction()
    {
        /*
        $rows = DB::table('car')
        ->LeftJoin('car_trip as ct', 'car.id', '=', 'car_trip.car_id')
        ->where('car_trip.created_by', Auth::id())
        ->orderBy('car_trip.id', 'desc')
        ->get(['car_trip.*', 'car.car_number']);
        */
        $id = Input::get('id');

        $row = DB::table('car')
        ->LeftJoin('car_trip', 'car.id', '=', 'car_trip.car_id')
        ->where('car_trip.id', $id)
        ->first(['car_trip.*', 'car.car_number']);

        $back = DB::table('car')
        ->LeftJoin('car_trip', 'car.id', '=', 'car_trip.car_id')
        ->where('car_trip.parent_id', $id)
        ->first(['car_trip.*', 'car.car_number']);

        $rows = [];

        $rows['car_number'] = $row['car_number'];
        $rows['km'] = $back['km'] - $row['km'].'km';
        $rows['start'] = date('Y-m-d H:i', $row['created_at']).'('.$row['km'].'km)'.$row['remark'];
        $rows['end']   = date('Y-m-d H:i', $back['created_at']).'('.$back['km'].'km)';
        $rows['lat']   = $back['lat'];
        $rows['lng']   = $back['lng'];
        if ($row) {
            $attachment = array_filter(explode(',', $row['attachment'].','.$back['attachment']));
            $rows['attachments'] = DB::table('car_attachment')->whereIn('id', $attachment)->get();
        }
        return response()->json($rows);
    }

    /**
     * 新建行程
     */
    public function createAction()
    {
        $id = Input::get('id', 0);

        if (Request::method() == 'POST') {
            $posts = Input::get();

            $rules = array(
                'car_id'      => 'required',
                'category_id' => 'required',
                'km'          => 'required',
                'lng'         => 'required',
                'lat'         => 'required',
            );
            $validator = Validator::make($posts, $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages());
            }

            // 保存图片
            $images = $this->storeImages();
            if ($images['status'] == 1) {
                $posts['attachment'] = $images['data'];
            } else {
                return response()->json($images['data']);
            }

            // 回驻地获取最后一条出发前数据
            if ($posts['category_id'] == 2) {
                $parent = DB::table('car_trip')
                ->whereRaw('car_id=? and category_id=1', [$posts['car_id']])
                ->orderBy('id', 'desc')
                ->first('id');
                $posts['parent_id'] = $parent['id'];
            }

            if ($posts['id'] > 0) {
                DB::table('car_trip')->where('id', $posts['id'])->update($posts);
            } else {
                DB::table('car_trip')->insert($posts);
            }
            return response()->json(array('status'=>1));
        }

        if (Input::get('data_type') == 'json') {
            $cars = DB::table('car')->whereRaw('FIND_IN_SET(?,car_user_id)', [Auth::id()])->get();
            return response()->json($cars);
        }
        $row = DB::table('car_trip')->where('id', $id)->first();
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
            'car_id'      => 'required',
            'category_id' => 'required',
            'lng'         => 'required',
            'lat'         => 'required',
            'km'          => 'required',
            // 'attachment'  => 'min:1|array|required',
        ];

        $v = Validator::make($gets, $rules);
        if ($v->fails()) {
            //return $this->json($v->errors());
        }

        // 回驻地获取最后一条出发前数据
        if ($gets['category_id'] == 2) {
            $parent = DB::table('car_trip')
            ->where('category_id', 1)
            ->where('car_id', $gets['car_id'])
            ->orderBy('id', 'desc')
            ->first(['id']);

            // 没有出发前数据
            if (empty($parent)) {
                return $this->json('出发前数据不存在。');
            }

            $gets['parent_id'] = $parent['id'];
        }

        if (is_array($gets['attachment'])) {
            $gets['attachment'] = attachment_base64('car_attachment', $gets['attachment'], 'car');
        } else {
            $gets['attachment'] = attachment_images('car_attachment', 'image', 'car');
        }

        DB::table('car_trip')->insert($gets);
        return $this->json('数据上传成功。', true);
    }

    public function deleteAction()
    {
        $id = Input::get('id', 0);
        if ($id > 0) {
            $row = DB::table('car_trip')->where('id', $id)->first();
            if ($row) {
                attachment_delete('car_attachment', $row['attachment']);
                DB::table('car_trip')->where('id', $row['id'])->delete();
            }

            $row = DB::table('car_trip')->where('parent_id', $id)->first();
            if ($row) {
                attachment_delete('car_attachment', $row['attachment']);
                DB::table('car_trip')->where('id', $row['id'])->delete();
            }

            return $this->success('trip', '恭喜你，操作成功。');
        }
    }
}
