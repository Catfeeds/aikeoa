<?php namespace Aike\Index\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\UserWidget;
use Aike\Index\Controllers\DefaultController;

class WidgetController extends DefaultController
{
    public $permission = ['edit', 'sort'];

    // 编辑
    public function editAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'name' => 'required',
                'path' => 'required',
            ];
            $v = Validator::make($gets, $rules);
            if ($v->fails()) {
                return $this->back()->withErrors($v)->withInput();
            }
            if ($gets['id']) {
                DB::table('widget')->where('id', $gets['id'])->update($gets);
            } else {
                DB::table('widget')->insert($gets);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }
        $row = DB::table('widget')->where('id', $id)->first();

        return $this->display([
            'row' => $row
        ]);
    }

    /**
     * 排序
     */
    public function sortAction()
    {
        if (Request::method() == 'POST') {

            $gets = Input::get();
            foreach ($gets['widgets'] as $index => $item) {
                $widget = UserWidget::find($item['id']);
                $item['sort'] = $index;
                $widget->fill($item);
                $widget->save();
            }
            foreach ($gets['todos'] as $index => $item) {
                $widget = UserWidget::find($item['id']);
                $item['sort'] = $index;
                $widget->fill($item);
                $widget->save();
            }
            return $this->json('恭喜您，部件排序成功。', true);
        }
    }

    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            DB::table('widget')->whereIn('id', $id)->delete();
            return $this->back()->with('message', '恭喜你，操作成功。');
        }
    }
}
