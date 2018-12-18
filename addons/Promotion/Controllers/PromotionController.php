<?php namespace Aike\Promotion\Controllers;

use DB;
use Input;
use Request;
use Validator;

use Aike\User\User;
use Aike\Promotion\Promotion;
use Aike\Model\Grid;
use Aike\Model\Form;

use Aike\Index\Controllers\DefaultController;

class PromotionController extends DefaultController
{
    public $permission = ['dialog'];

    public function indexAction()
    {
        $header = Grid::header([
            'table'   => 'promotion',
            'referer' => 1,
            'search'  => ['by' => ''],
        ]);

        $cols = $header['cols'];

        $cols['actions']['options'] = [[
            'name'    => '显示',
            'action'  => 'show',
            'display' => $this->access['show'],
        ],[
            'name'    => '编辑',
            'action'  => 'edit',
            'display' => $this->access['edit'],
        ]];

        $search = $header['search_form'];
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = Promotion::setBy($header);
            foreach ($header['join'] as $join) {
                $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $model->orderBy($header['sort'], $header['order']);

            // 公司权限
            /*
            $companyIds = User::authoriseCompany();
            if($companyIds) {
                $model->whereIn('invoice_provide.company_id', $companyIds);
            }
            */

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            if ($query['export']) {
                $rows = $model->get($header['select']);
            } else {
                $rows = $model->paginate($search['limit'], $header['select'])->appends($query);
            }

            $items = Grid::dataFilter($rows, $header);

            if ($query['export']) {
                unset($cols['actions']);
                writeExcel($cols, $items, $header['name']. date('Y-m-d'));
            }

            return $items->toJson();
        }

        $header['buttons'] = [
            ['name' => '删除', 'icon' => 'fa-remove', 'action' => 'delete', 'display' => $this->access['delete']],
            ['name' => '导出', 'icon' => 'fa-share', 'action' => 'export', 'display' => $this->access['export']],
        ];

        $header['cols'] = $cols;
        $header['tabs'] = Promotion::$tabs;
        $header['bys']  = Promotion::$bys;
        $header['js']   = Grid::js($header);

        return $this->display([
            'header' => $header,
        ]);
    }

    // 新建客户联系人
    public function createAction()
    {
        if (Request::method() == 'POST') {
            $gets = Input::get();

            $rules = Form::rules([
                'table' => 'promotion',
            ]);
            $v = Validator::make($gets, $rules['rules'], $rules['messages'], $rules['attributes']);
            if ($v->fails()) {
                return $this->json(join('<br>',$v->errors()->all()));
            }
            $_provide = $gets['promotion'];
            $provide = Promotion::findOrNew($_provide['id']);
            $provide->fill($_provide)->save();

            return $this->json('恭喜您，操作成功。', url_referer('index'));
        }

        $id = (int)Input::get('id');
        $provide = Promotion::find($id);

        $options = [
            'table' => 'promotion',
        ];
        if ($provide->id) {
            $options['row'] = $provide;
            // 已经复核无法编辑
            if($provide->review == 1) {
                exit('<div class="wrapper text-center text-danger">发票已经复核无法编辑。</div>');
            }
        }
        $tpl = Form::make($options);
        
        return $this->display([
            'tpl'    => $tpl,
            'header' => $options,
        ], 'create');
    }

    // 创建客户联系人
    public function editAction()
    {
        return $this->createAction();
    }

    // 显示客户联系人
    public function showAction()
    {
        $id = (int)Input::get('id');
        $provide = Promotion::find($id);
        $options = [
            'table' => 'invoice_provide',
            'row'   => $provide,
        ];
        $tpl = Form::show($options);
        return $this->display([
            'tpl' => $tpl,
        ]);
    }

    // 复核发票
    public function reviewAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $id = array_filter((array)$id);

            if (empty($id)) {
                return $this->json('最少选择一行记录。');
            }

            $promotions = Promotion::whereIn('id', $id)->get();
            foreach ($promotions as $promotion) {
                $promotion->review = $promotion->review == 1 ? 0 : 1;
                $promotion->save();
            }
            return $this->json('恭喜你，操作成功。', url_referer('index'));
        }
    }

    // 删除客户联系人
    public function deleteAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $id = array_filter((array)$id);

            if (empty($id)) {
                return $this->json('最少选择一行记录。');
            }

            $promotions = Promotion::whereIn('id', $id)->get();
            foreach ($promotions as $promotion) {
                // 删除数据
                if ($promotion->deleted_by > 0) {
                    $promotion->delete();
                } else {
                    $promotion->deleted_at = time();
                    $promotion->deleted_by = auth()->id();
                    $promotion->save();
                }
            }
            return $this->json('恭喜你，操作成功。', url_referer('index'));
        }
    }
}
