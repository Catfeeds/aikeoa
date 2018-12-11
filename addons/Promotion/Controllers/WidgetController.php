<?php namespace Aike\Promotion\Controllers;

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

            $model = DB::table('promotion')->leftjoin('model_step', 'model_step.number', '=', 'promotion.step_number')
            ->leftjoin('model', 'model.id', '=', 'model_step.model_id')
            ->where('model.table', 'promotion');

            if ($circle['whereIn']) {
                foreach ($circle['whereIn'] as $key => $where) {
                    $model->whereIn($key, $where);
                }
            }

            $rows = $model->where('promotion.status', 0)
            ->where('promotion.step_number', '>', 1)
            ->where('promotion.deleted_by', 0)
            ->groupby('promotion.step_number')
            ->selectRaw('promotion.step_number, count(promotion.id) as count,model_step.name')
            ->get();

            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }
}
