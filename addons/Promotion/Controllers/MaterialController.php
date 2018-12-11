<?php namespace Aike\Promotion\Controllers;

use Input;
use Request;
use Validator;
use URL;
use select;
use DB;

use Aike\Promotion\Promotion;
use Aike\Promotion\PromotionMaterial as Material;

use Aike\Index\Controllers\DefaultController;

class MaterialController extends DefaultController
{
    public $permission = ['detail', 'dialog', 'store'];
    
    // 促销核销列表
    public function indexAction()
    {
        // 客户圈权限
        $circle = select::circleCustomer();

        $columns = [
            ['text','promotion.number','促销编号'],
        ];
        $columns = array_merge($columns, $circle['columns']);

        $search = search_form([
            'status'  => 0,
            'referer' => 1,
        ], $columns);
        $query = $search['query'];

        $model = Material::with(['promotion.customer.user', 'contact.user'])
        ->leftJoin('promotion', 'promotion.id', '=', 'promotion_material.promotion_id')
        ->leftJoin('customer', 'customer.user_id', '=', 'promotion.customer_id')
        ->where('promotion_material.status', $query['status']);

        if ($circle['whereIn']) {
            foreach ($circle['whereIn'] as $key => $where) {
                $model->whereIn($key, $where);
            }
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        $rows = $model->orderBy('promotion_material.id', 'desc')
        ->paginate($search['limit'], ['promotion_material.*','promotion.number'])->appends($query);

        $rows->map(function ($item) {
            $item->customer_name = $item->promotion->customer->user->nickname;
            $item->contact_name = $item->contact->user->nickname;
            return $item;
        });

        // 返回json
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $status = [
            ['id'=>'0', 'name'=>'待审', 'color' => 'danger'],
            ['id'=>'1', 'name'=>'已审', 'color' => 'success'],
        ];

        $tabs = [
            'name'  => 'status',
            'items' => $status
        ];

        $status = array_by($status);

        return $this->display([
            'query'  => $query,
            'search' => $search,
            'rows'   => $rows,
            'tabs'   => $tabs,
            'status' => $status,
        ]);
    }

    // 促销核销明细
    public function detailAction()
    {
        $promotion_id = Input::get('promotion_id');

        $promotion = Promotion::where('id', $promotion_id)->first();

        $model = Material::leftJoin('promotion', 'promotion.id', '=', 'promotion_material.promotion_id')
        ->where('promotion_material.promotion_id', $promotion_id);

        $rows = $model->orderBy('promotion_material.id', 'desc')
        ->get(['promotion_material.*','promotion.number'])->toArray();

        $images = [];

        foreach ($rows as $row) {
            $_files = explode(',', $row['files']);
            $files  = DB::table('promotion_material_file')->whereIn('id', $_files)->get();
            foreach ($files as $file) {
                $image = getimagesize(upload_path().'/'.$file['path']);
                $file['width']  = $image[0];
                $file['height'] = $image[1];
                $file['promotion'] = $row;
                $images[] = $file;
            }
        }

        return $this->display([
            'images'    => $images,
            'promotion' => $promotion,
        ]);
    }

    // 获取核销列表
    public function dialogAction()
    {
        $user = auth()->user();

        $customer_id = $user->customer->id;

        $rows = Promotion::with('datas')->where('material_id', '!=', 2)
        ->where('status', 0)
        ->where('customer_id', $customer_id)
        ->where('number', '!=', '')
        ->get(['id', 'number as text','data_4', 'data_19', 'start_at', 'end_at']);

        foreach ($rows as &$row) {
            $row->products = $row->datas->implode('product_name', ',');
        }
        
        return $this->json($rows, true);
    }
    
    // 显示促销
    public function showAction()
    {
        $id = Input::get('id');
        $row = Material::with('promotion')
        ->where('id', $id)
        ->first();

        // 获取核销图片
        $_files = explode(',', $row['files']);
        $files  = DB::table('promotion_material_file')->whereIn('id', $_files)->get();

        foreach ($files as $file) {
            $file = upload_path().'/'.$file['path'].'/'.$file['file'];
            // 生成缩略图
            thumb($file, 80, 80);
        }

        $row['images']     = $files;
        $row['upload_url'] = URL::to('/uploads');

        if (Input::wantsJson()) {
            return response()->json($row);
        }

        return $this->render([
            'row' => $row,
        ]);
    }

    // 促销核销审核
    public function auditAction()
    {
        $id   = (array)Input::get('id');
        $rows = DB::table('promotion_material')
        ->whereIn('id', $id)->get()->toArray();

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $row['status'] = $row['status'] == 1 ? 0 : 1;
                DB::table('promotion_material')->where('id', $row['id'])->update($row);
            }
        }
        return $this->back('审核成功。');
    }

    public function storeAction()
    {
        // 上传文件
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $promotion = DB::table('promotion')
            ->where('id', $gets['promotion_id'])
            ->first();

            if (empty($promotion)) {
                return $this->json('促销编号不正确。');
            }
            $gets['files'] = $this->images('promotion_material_file', 'promotion/material');
            
            DB::table('promotion_material')->insert($gets);
            return $this->json('核销保存成功。', true);
        }
    }

    // 删除核销
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = (array)Input::get('id');
            $materials = Material::whereIn('id', $id)->get();

            foreach ($materials as $material) {
                attachment_delete('promotion_material_file', $material['files']);
                $material->delete();
            }
            return $this->success('trash', '促销核销删除成功。');
        }
    }
    
    // 保存图片数据
    public function images($table, $path = 'default')
    {
        $images = Input::file();
        
        $path = $path.'/'.date('Y/m');
        $upload_path = upload_path().'/'.$path;

        $rows = [];

        foreach ($images as $image) {
            if ($image->isValid()) {
                // 文件后缀名
                // $extension = $image->getClientOriginalExtension();
                $extension = 'png';

                // 文件新名字
                $filename = date('dhis_').str_random(4).'.'.$extension;
                $filename = mb_strtolower($filename);

                if ($image->move($upload_path, $filename)) {
                    $rows[] = DB::table($table)->insertGetId([
                        'path'       => $path. '/'. $filename,
                        'name'       => $filename,
                        'type'       => $extension,
                        'status'     => 1,
                        'size'       => $image->getClientSize(),
                    ]);
                }
            }
        }
        return join(',', array_filter($rows));
    }
}
