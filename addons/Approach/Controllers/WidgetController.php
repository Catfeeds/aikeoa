<?php namespace Aike\Approach\Controllers;

use DB;
use Auth;
use Input;
use Request;

use select;

use Aike\Index\Controllers\DefaultController;

class WidgetController extends DefaultController
{
    public $permission = ['index'];
    
    public function indexAction()
    {
        if (Request::isJson()) {

            // 客户圈权限
            $circle = select::circleCustomer();

            $model = DB::table('approach')
            ->leftjoin('model_step', 'model_step.number', '=', 'approach.step_number')
            ->leftjoin('model', 'model.id', '=', 'model_step.model_id')
            ->where('model.table', 'approach');

            if ($circle['whereIn']) {
                foreach ($circle['whereIn'] as $key => $where) {
                    $model->whereIn($key, $where);
                }
            }

            $rows = $model->where('approach.status', 0)
            ->where('approach.step_number', '>', 1)
            ->where('approach.deleted_by', 0)
            ->groupby('approach.step_number')
            ->selectRaw('approach.step_number, count(approach.id) as total_count, model_step.name')
            ->get();

            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }
}
