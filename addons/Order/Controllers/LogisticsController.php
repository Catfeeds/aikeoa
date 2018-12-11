<?php namespace Aike\Order\Controllers;

use DB;
use Input;
use Request;
use Auth;
use Validator;

use Aike\Model\Form;
use Aike\Model\Table;

use Aike\User\User;
use Aike\Order\Logistics;

use Aike\Index\Controllers\DefaultController;

class LogisticsController extends DefaultController
{
    public $permission = ['dialog'];

    public function indexAction()
    {
        $options = [];
        $users = User::authoriseAccess();

        // 用户权限
        if ($users) {
            $options['whereIn'] = [];
        }
        return Table::make('logistics', $options);
    }

    // 计划显示
    public function showAction(Request $request)
    {
        $options = [];
        return Table::show('logistics', $options);
    }

    // 新建计划
    public function createAction(Request $request)
    {
        $options = [];
        return Form::make('logistics', $options);
    }

    // 新建计划
    public function editAction(Request $request)
    {
        $options = [];
        return Form::make('logistics', $options);
    }

    // 删除计划
    public function deleteAction(Request $request)
    {
        $options = [];
        return Form::remove('logistics', $options);
    }

    /**
     * 弹出层信息
     */
    public function dialogAction()
    {
        $gets = Input::get();

        $search = search_form([
            'advanced' => '',
            'prefix'   => '',
            'offset'   => '',
            'sort'     => '',
            'order'    => '',
            'limit'    => '',
        ], [
            ['text','logistics.name','名称'],
        ]);
        $query  = $search['query'];

        if (Request::method() == 'POST' || Request::isJson() || $gets['isjson']) {
            
            $model = DB::table('logistics');

            // 排序方式
            if ($query['sort'] && $query['order']) {
                $model->orderBy('logistics.'.$query['sort'], $query['order']);
            }

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $model->selectRaw("logistics.*");

            if ($query['limit']) {
                $rows = $model->paginate($query['limit']);
            } else {
                $rows['total'] = $model->count();
                $rows['data']  = $model->get();
            }
            return response()->json($rows);
        }

        return $this->render(array(
            'search' => $search,
            'query'  => $query,
            'gets'   => $gets,
        ));
    }
}
