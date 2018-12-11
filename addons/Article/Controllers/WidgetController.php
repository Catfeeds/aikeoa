<?php namespace Aike\Article\Controllers;

use DB;
use Auth;
use Request;
use Aike\Index\Controllers\DefaultController;

class WidgetController extends DefaultController
{
    public $permission = ['index'];

    public function indexAction()
    {
        if (Request::isJson()) {
            $model = DB::table('article')
            ->permission('receive_id')
            ->orderBy('created_at', 'desc');

            // 查询是否已经阅读
            $reader = function ($q) {
                $q->selectRaw('1')
                ->from('article_reader')
                ->whereRaw('article_reader.article_id = article.id')
                ->where('article_reader.created_by', auth()->id());
            };
            $model->whereNotExists($reader);

            $rows = $model->get(['id', 'title', 'created_at']);
            
            $json['total'] = sizeof($rows);
            $json['data'] = $rows;
            return response()->json($json);
        }
        return $this->render();
    }
}
