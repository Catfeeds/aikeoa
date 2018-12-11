<?php namespace Aike\System\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Index\Controllers\DefaultController;

class MenuController extends DefaultController
{
    // 菜单列表
    public function indexAction()
    {
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            foreach ($sorts as $id => $sort) {
                DB::table('menu')->where('id', $id)->update(['sort' => $sort]);
            }
            tree_rebuild('menu');
            return $this->success('index', '恭喜你，操作成功。');
        }

        $search = search_form([
            'referer'  => 1,
        ], []);
        
        $rows = DB::table('menu')
        ->orderBy('lft', 'asc')
        ->get();

        /*
        $rows = DB::table('dict')->get();

        foreach ($rows as $key => $row) {

            $values = json_decode($row['value'], true);

            //$data['parent_id'] = $row['id'];

            $id = DB::table('option')->insertGetId([
                'parent_id' => 0,
                'name'  => $row['name'],
                'value' => $row['key'],
            ]);

            $data = [];

            $data['parent_id'] = $id;

            if($values) {

            foreach ($values as $k => $v) {

                $data['sort']  = $k;
                $data['value'] = ''.$v['id'].'';
                $data['name']  = ''.$v['name'].'';
                DB::table('option')->insert($data);
            }
            }
        }

        print_r($rows);
        exit;
        */

        $rows = array_nest($rows);
        
        return $this->display([
            'rows' => $rows,
        ]);
    }

    // 新建字典
    public function createAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $gets = Input::get();
            
            $rules = [
                'name' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }

            if ($gets['id']) {
                DB::table('menu')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('menu')->insert($gets);
            }

            tree_rebuild('menu');

            return $this->success('index', '恭喜你，操作成功。');
        }

        $row     = DB::table('menu')->where('id', $id)->first();
        $parents = DB::table('menu')->orderBy('lft', 'asc')->get();
        $parents = array_nest($parents);

        return $this->display([
            'row'     => $row,
            'parents' => $parents,
        ]);
    }

    // 删除菜单
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');

            $count = DB::table('menu')->whereIn('parent_id', $id)->count();
            if ($count) {
                return $this->error('存在子菜单无法删除。');
            }
            $row = DB::table('menu')->whereIn('id', $id)->delete();
            return $this->back('删除成功。');
        }
    }
}
