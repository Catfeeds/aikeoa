<?php namespace Aike\System\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\Index\Controllers\DefaultController;

class WidgetController extends DefaultController
{
    public $permission = ['index', 'store', 'delete', 'system'];

    /**
     * 邮件设置
     */
    public function indexAction()
    {
        if (Request::method() == 'POST') {
            $sorts = Input::get('sort');
            foreach ($sorts as $id => $sort) {
                DB::table('widget')->where('id', $id)->update(['sort' => $sort]);
            }
            return $this->success('index', '恭喜你，操作成功。');
        }

        $metaData = [
            'columns' => [[
                    'dataIndex' => 'id',
                    'text'      => '编号',
                    'sortable'  => true,
                    'width'     => 70,
                    'align'     => 'center',
                ],[
                    'dataIndex' => 'title',
                    'text'      => '标题',
                    'width'     => 160,
                    'search'    => [
                        'name'  => 'title',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'name',
                    'text'      => '名称',
                    'width'     => 180,
                    'search'    => [
                        'name'  => 'name',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'receive_name',
                    'text'      => '可用对象',
                    'flex'      => 1,
                    'minWidth'  => 180,
                    'search'    => [
                        'name'  => 'receive_name',
                        'xtype' => 'textfield',
                    ],
                ],[
                    'dataIndex' => 'status',
                    'text'      => '可用',
                    'width'  => 140,
                ],[
                    'dataIndex' => 'default',
                    'text'      => '默认',
                    'width'  => 100,
                ],[
                    'dataIndex' => 'sort',
                    'text'      => '排序',
                    'width'     => 80,
                    'align'     => 'center',
                ]
            ]
        ];

        $search = search_form([
            'referer' => 1
        ]);
        $metaData['params'] = $search['params'];
        
        $rows = DB::table('widget')->orderBy('sort', 'asc')->paginate($search['limit']);

        return $this->display([
            'rows'   => $rows,
            'search' => $search,
        ]);
    }

    // 新建部件
    public function createAction()
    {
        $id = (int)Input::get('id');

        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = [
                'name' => 'required',
                'data' => 'required',
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
     * 保存
     */
    public function storeAction()
    {
        $gets = Input::get();

        if ($gets['id']) {
            DB::table('widget')->where('id', $gets['id'])->update($gets);
        } else {
            DB::table('widget')->insert($gets);
        }
        return $this->json('操作完成。', true);
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            DB::table('widget')->whereIn('id', $id)->delete();
            return $this->back()->with('message', '恭喜你，操作成功。');
        }
    }

    public function systemAction()
    {
        if (Request::isJson()) {
            $rows = [];
            $communicate = DB::table('communicate')->whereRaw("reply_text = '' and to_user_id=?", [auth()->id()])->count('id');

            if ($instruct > 0) {
                $rows[] = ['title' => '指令管理等待回复 <span class="badge badge-sm up bg-danger pull-right-xs">'.(int)$instruct['count'].'</span>条 <a class="c" href="'.url('task/task/index').'">点击回复</a>'];
            }

            if ($communicate > 0) {
                $rows[] = ['title' => '<a href="'.url('message/message/index').'">等待回复沟通信件</a> <span class="badge badge-sm bg-danger">'.$communicate.'</span></a>'];
            }

            // $rows[] = ['title' => '系统短信 <span class="badge badge-sm bg-info">'.\Sms::count().'</span>'];

            $rows[] = ['title' => '<a href="http://daily.shenghuafood.com" target="_blank"><span class="label bg-light dk"><i class="fa fa-external-link-square"></i> 川南每日8:30</span></a>'];

            $rows[] = ['title' => '<a href="http://dm.shenghuafood.com" target="_blank"><span class="label bg-light dk"><i class="fa fa-external-link-square"></i> 川南每日资讯</span></a>'];

            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }
}
