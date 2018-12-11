<?php namespace Aike\Customer\Controllers;

use Input;
use Auth;
use Request;
use Validator;
use DB;

use Aike\Customer\Business;
use Aike\User\User;
use Aike\Index\Attachment;
use Aike\Index\Notification;

use select;

use Aike\Index\Controllers\DefaultController;

class BusinessController extends DefaultController
{
    public $permission = ['index','salesman','store'];

    // 商机列表
    public function indexAction()
    {
        // 筛选客户
        $filter = select::customer();
        $columns = [
            ['text','customer_business.name','客户名称'],
        ];
        if ($filter['role_type'] == 'salesman') {
            $columns[] = ['text','customer_business.address','客户地区'];
            $columns[] = ['text','customer_business.type','客户类型'];
        }

        if ($filter['role_type'] == 'all') {
            $columns[] = ['text','user.nickname','创建者'];
            $columns[] = ['owner','customer_business.user_id','负责人'];
            $columns[] = ['text','customer_business.address','客户地区'];
            $columns[] = ['text','customer_business.type','客户类型'];
        }

        $search = search_form([
            'status'   => 1,
            'referer'  => 1
        ], $columns);

        $query  = $search['query'];
        
        $model = Business::leftJoin('user', 'user.id', '=', 'customer_business.created_by')
        ->select(['customer_business.*']);

        $level = authorise();
        if ($level < 4) {
            $model->where('customer_business.user_id', Auth::id());
        }

        foreach ($search['where'] as $where) {
            if ($where['active']) {
                $model->search($where);
            }
        }

        if ($query['order'] && $query['srot']) {
            $model->orderBy($query['srot'], $query['order']);
        } else {
            $model->orderBy('customer_business.id', 'desc');
        }

        $rows = $model->paginate($search['limit']);
 
        if (Request::wantsJson()) {
            return $rows->toJson();
        }

        $rows = $rows->appends($query);

        return $this->display(array(
            'rows'    => $rows,
            'search'  => $search,
            'selects' => $selects,
        ));
    }

    // 客户资料查看
    public function showAction()
    {
        $id = (int)Input::get('id');
        $row = DB::table('customer_business')
        ->leftJoin('user', 'user.id', '=', 'customer_business.user_id')
        ->where('customer_business.id', $id)
        ->first(['customer_business.*','user.nickname']);

        // 返回json
        $row['address'] = str_replace("\n", " ", $row['address']);
        $attachments = Attachment::view($row['attachment']);
        $row['attachments'] = $attachments['main'];
        return response()->json($row);
    }

    // 负责人列表
    public function salesmanAction()
    {
        if (Request::wantsJson()) {
            $users = User::leftJoin('role', 'role.id', '=', 'user.role_id')
            ->where('role.name', 'salesman')
            ->where('user.status', 1)
            ->get(['user.id', 'user.username', 'user.nickname']);
            return $this->json($users);
        }
    }

    // 储存商机
    public function storeAction()
    {
        if (Input::isJson()) {
            $gets = json_decode(Request::getContent(), true);
        } else {
            $gets = Input::get();
        }

        $row = new Business;

        $rules = [
            'source'  => 'required',
            'user_id' => 'required',
            'name'    => 'required',
            // 'attachment' => 'min:1|array|required',
        ];
        
        $v = Validator::make($gets, $rules, Business::$_messages);
        if ($v->fails()) {
            return $this->json($v->errors());
        }

        // 地区
        if (is_array($gets['address'])) {
            $gets['address'] = join("\n", $gets['address']);
        }

        // 保存base64图片数据
        // $gets['attachment'] = Attachment::base64($gets['attachment'], 'customer');

        if (is_array($gets['attachment'])) {
            $gets['attachment'] = Attachment::base64($gets['attachment'], 'customer');
        } else {
            $gets['attachment'] = Attachment::files('image', 'customer');
        }

        $row->fill($gets)->save();

        $user = User::find($gets['user_id']);

        notify()->sms([$gets['contacts_phone']], '感谢您对川南公司的关注', '负责您的业务人员是'.$user['nickname'].'，电话'.$user['mobile'].'。您可与其沟通或登陆www.cnnzfood.com');

        return $this->json('恭喜你，操作成功。', true);
    }

    // 删除商机
    public function destroyAction()
    {
        if (Request::method() == 'POST') {
            $id = Input::get('id');
            $rows = Business::whereIn('id', $id)->get();
            if ($rows) {
                foreach ($rows as $row) {
                    Attachment::delete($row->attachment);
                    $row->delete();
                }
            }
            return $this->success('index', '恭喜你，删除成功。');
        }
    }
}
