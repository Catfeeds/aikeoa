<?php namespace Aike\Model\Controllers;

use DB;
use Auth;
use Input;
use Request;
use Validator;
use Config;
use Schema;

use App\Jobs\SendSms;

use Aike\Model\Step;
use Aike\Model\Model;
use Aike\Model\Field;
use Aike\Model\Permission;
use Aike\Model\StepLog;
use Aike\Index\Attachment;

use Aike\User\User;
use Aike\Index\Notification;

use AES;
use Hook;
use Dialog;
use JPush;

use Aike\Index\Controllers\DefaultController;

class FormController extends DefaultController
{
    public $permission = ['turn', 'step', 'user', 'freeturn', 'freestep', 'freeuser', 'store', 'log', 'draft', 'delete', 'print', 'export', 'count'];

    public $last_log_id = 0;

    /**
     * 保存表单有审批
     */
    public function turnAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $master = $gets['master'];

            $keys = AES::decrypt($master['key'], config('app.key'));
            list($table, $id) = explode('.', $keys);

            if (empty($gets['next_step_id'])) {
                return $this->json('审批进程必须选择。');
            }

            // 审批步骤数据
            $step = Step::where('id', $gets['current_step_id'])->first();

            $nextSteps = Step::whereIn('id', $gets['next_step_id'])->get();

            $next_step_user = $gets['next_step_user'];

            // 并发选项
            if ($step['concurrent'] > 0) {
                // 并发主办人检查
                foreach ($nextSteps as $nextStep) {
                    if (empty($next_step_user[$nextStep['id']])) {
                        return $this->json('['.$nextStep['sn'].']流程主办人不能为空。');
                    }
                }
            }

            $model = Model::with('fields', 'children.fields')->where('table', $table)->first();

            // 主表和子表字段
            $fields[$table] = $model->fields->toArray();
            $slaves = [];
            foreach ($model->children as $children) {
                $slaves[$children->table] = $children->table;
                $fields[$children->table] = $children->fields->toArray();
            }

            // 转到步骤条件检查
            if ($gets['next_step_type'] == 'next') {
                $_permission = Permission::where('id', $step['permission_id'])->first();
                $permissions = json_decode($_permission['data'], true);

                $rules = $names = [];

                // 验证规则生成
                if ($permissions) {
                    foreach ($permissions as $t => $permission) {
                        $split = isset($slaves[$t]) ? 'rows.*.' : '';

                        foreach ($fields[$t] as $v) {
                            $row = $permission[$v['field']];
                            if ($row['v']) {
                                $v['field'] = $split.$v['field'];
                                $names[$t.'.'.$v['field']] = $v['name'];
                                $rules[$t.'.'.$v['field']] = $row['v'];
                            }
                        }
                    }
                }

                if ($rules) {
                    $v = Validator::make($gets, $rules, [], $names);
                    if ($v->fails()) {
                        // 字段验证错误
                        $errors = $v->errors()->all();
                        return $this->json(join('<br>', $errors));
                    }
                }
            }

            // 检查是否结束
            foreach ($nextSteps as $nextStep) {
                if ($nextStep['sn'] == 0) {
                    // 查询单据全部未办理的步骤
                    $count = DB::table('model_step_log')->where('model_id', $step['model_id'])
                    ->where('table_id', $id)
                    ->where('updated_id', 0)
                    ->count();

                    // 如果只有一条并且是结束
                    if ($count == 1) {
                        // 设置流程结束状态
                        $gets['next_step_type'] = 'end';
                    }
                }
            }

            // 默认状态
            $gets[$table]['step_status'] = $gets['next_step_type'];

            // 设置转交下一步的序号
            $step_sn = DB::table('model_step')->whereIn('id', $gets['next_step_id'])->pluck('sn')->implode(',');
            $gets[$table]['step_sn'] = $step_sn;

            // 保存数据
            $id = $this->store($model, $gets, $id, 'turn');
            $gets[$table]['id'] = $id;

            // 获取转交来的流程记录ID
            $gets['step_log_id'] = $this->last_log_id > 0 ? $this->last_log_id : $gets['step_log_id'];

            // 写入下一步骤审核日志
            foreach ($nextSteps as $nextStep) {
                if ($nextStep['sn'] > 0) {
                    DB::table('model_step_log')->insert([
                        'model_id'     => $model['id'],
                        'table'        => $table,
                        'table_id'     => $id,
                        'parent_id'    => $gets['step_log_id'],
                        'step_id'      => $nextStep['id'],
                        'step_sn'      => $nextStep['sn'],
                        'step_name'    => $nextStep['name'],
                        'user_id'      => (int)$next_step_user[$nextStep['id']],
                        'step_status'  => 'draft',
                        'created_id'   => Auth::id(),
                        'created_by'   => Auth::user()->nickname,
                    ]);
                }
            }

            if ($step['merge'] == 1) {
                // 写入下一步骤审核日志
                foreach ($nextSteps as $nextStep) {
                    if ($nextStep['sn'] == 0) {
                        // 更新已办日志
                        DB::table('model_step_log')->where('table', $table)
                        ->where('table_id', $id)
                        ->where('id', $gets['step_log_id'])
                        ->update([
                            'step_status' => $gets['next_step_type'],
                            'description' => $gets['description'],
                            'updated_id'  => Auth::id(),
                            'updated_at'  => time(),
                        ]);
                    }
                }
            } else {
                // 当前为办理日志
                $step_log = DB::table('model_step_log')->where('table', $table)
                ->where('table_id', $id)
                ->where('id', $gets['step_log_id'])
                ->where('updated_id', 0)
                ->first();

                // 读取上一步的所有未办理数据
                $step_logs = DB::table('model_step_log')->where('table', $table)
                ->where('table_id', $id)
                ->where('parent_id', $step_log['parent_id'])
                ->where('updated_id', 0)
                ->get();

                foreach ($step_logs as $log) {
                    $step_type = $log['id'] == $step_log['id'] ? $gets['next_step_type'] : 'merge';

                    // 更新已办日志
                    DB::table('model_step_log')->where('table', $table)
                    ->where('table_id', $id)
                    ->where('id', $log['id'])
                    ->update([
                        'step_status' => $step_type,
                        'description' => $gets['description'],
                        'updated_id'  => Auth::id(),
                        'updated_at'  => time(),
                    ]);
                }
            }

            // 通知
            $this->notification($model, $next_step_user, $gets[$table], $gets);

            $msg = $gets['next_step_type'] == 'next' ? '审批' : '退回';
            session()->flash('message', $model['name'].$msg.'成功。');
            return $this->json($gets['step_referer'], true);
        }

        $keys = AES::decrypt($gets['key'], config('app.key'));
        list($table, $id) = explode('.', $keys);

        // 新建流程时编号为0
        if ($id) {
            $row = DB::table($table)->find($id);
        } else {
            $row['step_sn'] = 1;
        }

        $model = Model::where('table', $table)->first();

        $step = Step::where('model_id', $model->id)
        ->where('sn', $row['step_sn'])
        ->first();

        $join  = explode(',', $step->join);
        $steps = Step::where('model_id', $model->id)
        ->whereIn('id', $join)
        ->get();

        return $this->render([
            'steps'  => $steps,
            'step'   => $step,
            'notify' => $notify,
            'table'  => $table,
        ]);
    }

    /**
     * 自由流程保存表单有审批
     */
    public function freeturnAction()
    {
        $gets = Input::get();

        if (Request::method() == 'POST') {
            $master = $gets['master'];

            $keys = AES::decrypt($master['key'], config('app.key'));
            list($table, $id) = explode('.', $keys);

            $next_step_user = $gets['next_step_user'];

            if ($next_step_user == '') {
                return $this->json('审批人不能为空。');
            }

            $model = Model::with('fields', 'children.fields')->where('table', $table)->first();

            // 主表和子表字段
            $fields[$table] = $model->fields->toArray();
            $slaves = [];
            foreach ($model->children as $children) {
                $slaves[$children->table] = $children->table;
                $fields[$children->table] = $children->fields->toArray();
            }

            // 转到步骤条件检查
            if ($gets['next_step_type'] == 'next') {
                $_permission = Permission::where('id', $master['permission_id'])->first();
                $permissions = json_decode($_permission['data'], true);

                $rules = $names = [];

                // 验证规则生成
                if ($permissions) {
                    foreach ($permissions as $t => $permission) {
                        $split = isset($slaves[$t]) ? 'rows.*.' : '';

                        foreach ($fields[$t] as $v) {
                            $row = $permission[$v['field']];
                            if ($row['v']) {
                                $v['field'] = $split.$v['field'];
                                $names[$t.'.'.$v['field']] = $v['name'];
                                $rules[$t.'.'.$v['field']] = $row['v'];
                            }
                        }
                    }
                }

                if ($rules) {
                    $v = Validator::make($gets, $rules, [], $names);
                    if ($v->fails()) {
                        // 字段验证错误
                        $errors = $v->errors()->all();
                        return $this->json(join('<br>', $errors));
                    }
                }
            }

            // 默认状态
            $gets[$table]['step_status'] = $gets['next_step_type'];

            // 结束时设置主表状态
            if ($gets['next_step_type'] == 'end') {
                $gets[$table]['status'] = 1;
            }

            // 当前待办记录
            $step_log = StepLog::where('table', $table)
            ->where('table_id', $id)
            ->where('id', $gets['step_log_id'])
            ->first();

            // 无记录
            if (empty($step_log)) {
                $step_log['step_sn'] = 0;
            }

            $gets[$table]['step_sn'] = $step_log['step_sn'] + 1;

            // 保存数据
            $id = $this->store($model, $gets, $id);
            $gets[$table]['id'] = $id;

            if ($gets['next_step_type'] == 'next') {
                $user = DB::table('user')
                ->where('id', $gets['next_step_user'])
                ->first();

                StepLog::insert([
                    'model_id'     => $model->id,
                    'table'        => $table,
                    'table_id'     => $id,
                    'parent_id'    => $gets['step_log_id'],
                    'step_id'      => 0,
                    'step_sn'      => $step_log['step_sn'] + 1,
                    'step_name'    => $user['nickname'].'审批',
                    'user_id'      => $gets['next_step_user'],
                    'step_status'  => 'draft',
                    'description'  => '',
                    'created_id'   => Auth::id(),
                    'created_by'   => Auth::user()->nickname,
                ]);
            }

            // 更新已办日志
            StepLog::where('table', $table)
            ->where('table_id', $id)
            ->where('id', $gets['step_log_id'])
            ->update([
                'step_status' => $gets['next_step_type'],
                'description' => $gets['description'],
                'updated_id'  => Auth::id(),
                'updated_at'  => time(),
            ]);

            // 通知
            $this->notification($model, $next_step_user, $gets[$table], $gets);

            $msg = $gets['next_step_type'] == 'next' ? '审批' : '退回';
            session()->flash('message', $model['name'].$msg.'成功。');
            return $this->json($gets['step_referer'], true);
        }

        $keys = AES::decrypt($gets['key'], config('app.key'));
        list($table, $id) = explode('.', $keys);

        // 新建流程时编号为0
        if ($id) {
            $row = DB::table($table)->find($id);
        } else {
            $row['step_sn'] = 1;
        }

        return $this->render([
            'row'   => $row,
            'table' => $table,
        ]);
    }

    /**
     * 保存表单无流程
     */
    public function storeAction()
    {
        $gets = Input::get();

        $master = $gets['master'];

        $keys = AES::decrypt($master['key'], config('app.key'));
        list($table, $id) = explode('.', $keys);

        if (Request::method() == 'POST') {
            $models[$master['table']] = $master;
            if ($gets['models']) {
                $models = array_merge($models, $gets['models']);
            }

            $rules  = $attributes = [];

            $permission = Permission::where('id', $models[$table]['permission_id'])->first();
            $permission = json_decode($permission['data'], true);

            foreach ($models as $t => $m) {
                $model  = Model::where('table', $t)->first();
                $fields = Field::where('model_id', $model->id)->orderBy('sort', 'asc')->get();
                
                foreach ($fields as $field) {
                    $_field = $field['field'];
                    $_permission = $permission[$t][$_field];

                    if ($_permission['v']) {
                        $k = $m['type'] == 1 ? $t.'.rows.*.'.$_field : $t.'.'.$_field;
                        $rules[$k]      = $_permission['v'];
                        $attributes[$k] = $field['name'];
                    }
                    // 省市区
                    if ($field['form_type'] == 'address') {
                        $gets[$t][$_field] = join("\n", (array)$gets[$t][$_field]);
                    }
                    // 多文件
                    if ($field['form_type'] == 'files') {
                        $gets[$t][$_field] = join(',', (array)$gets[$t][$_field]);
                    }
                }
            }

            // 开始验证数据
            $v = Validator::make($gets, $rules, [], $attributes);
            if ($v->fails()) {
                $errors = $v->errors()->all();
                return $this->json(join('<br>', $errors));
            }

            $model = Model::with('fields', 'children.fields')->where('table', $table)->first();
            
            // 保存数据
            $this->store($model, $gets, $id, 'save');

            session()->flash('message', $model['name'].'保存成功。');
            return $this->json($gets['step_referer'], true);
        }
    }

    /**
     * 保存草稿
     */
    public function draftAction()
    {
        $gets = Input::get();

        $master = $gets['master'];

        $keys = AES::decrypt($master['key'], config('app.key'));
        list($table, $id) = explode('.', $keys);

        if (Request::method() == 'POST') {
            $models[$master['table']] = $master;
            
            if ($gets['models']) {
                $models = array_merge($models, $gets['models']);
            }

            if ($id == 0) {
                $gets[$table]['step_sn']     = 1;
                $gets[$table]['step_status'] = 'draft';
            }

            $permission = Permission::where('id', $models[$table]['permission_id'])->first();
            $permission = json_decode($permission['data'], true);

            $rules  = $attributes = [];

            foreach ($models as $t => $m) {
                $model  = Model::where('table', $t)->first();
                $fields = Field::where('model_id', $model->id)->orderBy('sort', 'asc')->get();

                foreach ($fields as $field) {
                    $_field = $field['field'];
                    $_permission = $permission[$t][$_field];

                    if ($_permission['v']) {
                        $k = $m['type'] == 1 ? $t.'.rows.*.'.$_field : $t.'.'.$_field;
                        $rules[$k]      = $_permission['v'];
                        $attributes[$k] = $field['name'];
                    }
                    // 省市区
                    if ($field['form_type'] == 'address') {
                        $gets[$t][$_field] = join("\n", (array)$gets[$t][$_field]);
                    }
                    // 多文件
                    if ($field['form_type'] == 'files') {
                        $gets[$t][$_field] = join(',', (array)$gets[$t][$_field]);
                    }
                }
            }

            // 开始验证数据
            $v = Validator::make($gets, $rules, [], $attributes);
            if ($v->fails()) {
                $errors = $v->errors()->all();
                return $this->json(join('<br>', $errors));
            }

            $model = Model::with('fields', 'children.fields')->where('table', $table)->first();
            
            // 保存数据
            $id = $this->store($model, $gets, $id, 'draft');
            
            // 保存草稿跳转到编辑界面
            $url = url($gets['uri'].'/edit', ['id' => $id]);

            session()->flash('message', $model['name'].'草稿保存成功。');
            return $this->json($url, true);
        }
    }

    public function tokensMatch()
    {
        $req = request();

        $sessionToken = $req->session()->token();

        $token = $req->input('_token') ?: $req->header('X-CSRF-TOKEN');

        if (! $token && $header = $req->header('X-XSRF-TOKEN')) {
            $token = decrypt($header);
        }

        if (! is_string($sessionToken) || ! is_string($token)) {
            abort_error('Token Mismatch');
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public function store($model, $gets, $id, $type)
    {
        // 当前用户
        $auth = Auth::user();

        // 检查表单Token
        $this->tokensMatch();
        
        // 主表名
        $table = $model->table;

        // 主表数据
        $master = $gets[$table];

        $fields = $model->fields->toArray();

        foreach ($fields as $field) {
            $column = $field['field'];
            // 多文件
            if ($field['form_type'] == 'files') {
                if ($master[$column]) {
                    $master[$column] = join(',', (array)$master[$column]);
                }
            }
        }

        if ($gets['next_step_type']) {
            $master['step_status'] = $gets['next_step_type'];
        }

        $datas = $deleteds = [];

        foreach ($model->children as $children) {
            $deleteds[$children->table] = $gets[$children->table]['deleteds'];

            $datas[] = [
                'table'    => $children->table,
                'type'     => $children->type,
                'relation' => $children->relation,
                'data'     => (array)$gets[$children->table]['rows']
            ];
        }

        // 过滤数据
        $_data = Hook::fire($table.'.onBeforeStore', ['table' => $table, 'gets' => $gets, 'master' => $master, 'datas' => $datas]);
        extract($_data);

        // 插入还是编辑
        $insert = $id > 0 ? 0 : 1;

        // 更新主表
        if ($id) {
            DB::table($table)->where('id', $id)->update($master);
        } else {
            $id = DB::table($table)->insertGetId($master);
        }

        // 更新单据编码
        DB::table('model')->where('id', $model->id)->update(['data_sn' => $id]);

        foreach ($datas as $data) {
            $rows = $data['data'];

            // 多行子表
            if ($data['type'] == 1) {
                // 删除列表数据
                $deleted = $deleteds[$data['table']];
                if (!empty($deleted)) {
                    DB::table($data['table'])->whereIn('id', $deleted)->delete();
                }

                foreach ($rows as $row) {
                    // 关联键编号
                    $row[$data['relation']] = $id;

                    // 事件过滤数据
                    $_event = Hook::fire($data['table'].'.onBeforeStore', ['table' => $table, 'row' => $row]);
                    $row = $_event['row'];

                    if ($row['id']) {
                        DB::table($data['table'])->where('id', $row['id'])->update($row);
                    } else {
                        DB::table($data['table'])->insert($row);
                    }
                }
            
                // 附表暂时未实现
            } else {
                /*
                if($rows['id']) {
                    DB::table($data['table'])->where('id', $rows['id'])->update($rows);
                } else {
                    DB::table($data['table'])->insert($rows);
                }
                */
            }
        }

        // 草稿或者第一步创建步骤
        if ($insert == 1) {
            if ($model['is_flow']) {
                // 读取第一步流程
                $step = DB::table('model_step')->where('model_id', $model['id'])->where('sn', 1)->first();

                $log = [
                    'model_id'    => $model['id'],
                    'table'       => $table,
                    'table_id'    => $id,
                    'parent_id'   => 0,
                    'step_id'     => $step['id'],
                    'step_sn'     => $step['sn'],
                    'step_name'   => $step['name'],
                    'user_id'     => $auth['id'],
                    'step_status' => 'draft',
                    'created_id'  => $auth['id'],
                    'created_by'  => $auth['nickname'],
                ];

                if ($type == 'turn') {
                    $log['step_status'] = 'next';
                    $log['updated_id']  = $auth['id'];
                    $log['updated_by']  = $auth['nickname'];
                    $log['updated_at']  = time();
                }

                // 写入第一步办理节点
                $this->last_log_id = DB::table('model_step_log')->insertGetId($log);
            }
        }

        // 保存后执行
        Hook::fire($table.'.onAfterStore', ['table' => $table, 'gets' => $gets, 'master' => $master, 'datas' => $datas, 'id' => $id]);

        // 附件发布
        Attachment::publish();

        return $id;
    }

    /**
     * 获取办理步骤
     */
    public function stepAction()
    {
        $gets    = Input::get();
        $type    = $gets['next_step_type'];
        $step_id = $gets['current_step_id'];

        $master = $gets['master'];

        $step = Step::where('id', $step_id)->first();

        if ($type == 'next') {
            $steps = Step::checkCondition();
        } else {
            // 退回上一步，这里有bug
            $logs = StepLog::where('table', $master['table'])
            ->where('step_sn', '<', $step['sn'])
            ->where('table_id', $master['id'])
            ->orderBy('id', 'desc')
            ->get();

            if ($step['sn'] > 1) {
                if ($logs->count()) {
                    $step['sn'] = $logs[0]['step_sn'];
                } else {
                    $step['sn'] = 1;
                }
            }
            $steps = Step::where('model_id', $step->model_id)->where('sn', $step['sn'])->get();
        }

        // 并发选项
        $concurrent = $step['concurrent'];
        $input_type = $concurrent > 0 ? 'checkbox' : 'radio';

        $tpl     = '';
        $stepIds = [];

        foreach ($steps as $i => $step) {
            $checked = $onclick = '';
            
            if ($concurrent == 2) {
                // 强行并发
                $stepIds[] = $step['id'];
                $checked = 'checked';
                $onclick = 'onclick="return false"';
            } else {
                $onclick = 'class="next-step-id"';
                if ($i == 0) {
                    $stepIds[] = $step['id'];
                    $checked = 'checked';
                }
            }

            $name = 'next_step_id[]';

            $tpl .= '<div><label class="i-checks i-checks-sm">';
            $tpl .= '<input '.$checked.' '.$onclick.' type="'.$input_type.'" concurrent="'.$concurrent.'" name="'.$name.'" value="'.$step['id'].'"><i></i>'.($step['sn'] == 0 ? '' : ' 第 <span class="badge bg-default">'.$step['sn'].'</span> ').$step['name'];
            $tpl .= '</label></div>';
        }

        $master = $gets['master'];
        $model = Model::where('table', $master['table'])->first();
        $data = $gets[$master['table']];

        $notify = json_decode($step['notify'], true);
        $notify_sms = (int)$notify['sms'];

        $notify_text = '请您及时办理由'.auth()->user()->nickname.'转交的'.$model['name'].'(ID:#id#)，谢谢！';

        return response()->json([
            'tpl'          => $tpl,
            'sn'           => $stepIds,
            'concurrent'   => $concurrent,
            'notify_sms'   => $notify_sms,
            'notify_text'  => $notify_text,
        ]);
    }

    /**
     * 获取办理步骤
     */
    public function freestepAction()
    {
        $gets    = Input::get();
        $type    = $gets['next_step_type'];
        $step_id = $gets['current_step_id'];

        $master = $gets['master'];

        if ($type == 'next') {
            $steps = '';
        } else {
            // 退回上一步，这里有bug
            $logs = StepLog::where('table', $master['table'])
            ->where('step_sn', '<', $step['sn'])
            ->where('table_id', $master['id'])
            ->orderBy('id', 'desc')
            ->get();

            if ($step['sn'] > 1) {
                if (empty($logs)) {
                    $step['sn'] = 1;
                } else {
                    $step['sn'] = $logs[0]['step_sn'];
                }
            }
        }

        $master = $gets['master'];
        $model = Model::where('table', $master['table'])->first();
        $data = $gets[$master['table']];

        $notify_text = '请您及时办理由'.auth()->user()->nickname.'转交的'.$model['name'].'(ID:#id#)，谢谢！';

        $e = '<tr><td align="right">审批人</td><td align="left">'.Dialog::user('user', 'next_step_user', '', 0, 0).'</td></tr>';
        $e .= '<tr><td align="right">抄送人</td><td align="left">'.Dialog::user('user', 'next_step_cc', '', 1, 0).'</td></tr>';

        return response()->json([
            'tpl'          => $e,
            'sn'           => $stepIds,
            'notify_users' => $notify_users,
            'concurrent'   => $concurrent,
            'notify_sms'   => $notify_sms,
            'notify_text'  => $notify_text,
        ]);
    }

    /**
     * 审批用户
     */
    public function userAction()
    {
        $gets   = Input::get();
        $master = $gets['master'];

        $keys = AES::decrypt($master['key'], config('app.key'));
        list($table, $id) = explode('.', $keys);

        $step_id = $gets['step_id'];

        $step = Step::where('id', $step_id)->first();

        $next_step_cc = Dialog::user('user', 'next_step_cc', $step['notify_users'], 1, 0);
        $cc = '<td align="right">抄送人</td><td align="left">'.$next_step_cc.'</td>';

        $json = [];

        // 结束节点
        if ($step['sn'] == '0') {
            $json['cc'] = $cc;
            return $this->json($json, true);
        }

        $data = $gets[$table];

        $users = [];

        switch ($step['type']) {
            // 指定办理人
            case 'user':
                $users = explode(',', $step['type_value']);
                break;

            // 负责人
            case 'owner':
                $users = [$auth->owner_id];
                break;

            // 指定角色办理人
            case 'role':
                $roles = explode(',', $step['type_value']);
                $users = DB::table('user')->whereIn('role_id', $roles)->pluck('id');
                break;

            // 单据创建者
            case 'created_by':
                $row = DB::table($table)->find($id);
                $users = [$row['created_by']];
                break;

            // 直属领导
            case 'leader':
                $users = [$auth->leader_id];
                break;

            // 部门负责人
            case 'manager':
                $users = [$auth->department->manager];
                break;

            // 主表字段值
            case 'field':
                if ($step['type_value'] == 'supplier_id') {
                    $supplier = DB::table('supplier')->find($data['supplier_id']);
                    $users  = [$supplier['user_id']];
                }
                break;

            // 自定义
            case 'custom':
                $users = [];
                break;
        }

        $_users = DB::table('user')->whereIn('id', $users)->pluck('nickname', 'id');

        if ($_users->isEmpty()) {
            $json['user'] = '<td align="right">主办人</td><td align="left">无</td>';
            return $this->json($json, true);
        }

        $e = '<td align="right">主办人</td><td align="left"><select class="form-control input-sm input-select2" style="width:100%;" name="next_step_user['.$step_id.']" data-placeholder="请选择办理人"><option value=""></option>';
        $i = 0;
        foreach ($_users as $key => $_step) {
            $selected = $i == 0 ? ' selected' : '';
            $e .= '<option value="'.$key.'"'.$selected.'>'.$_step.'</option>';
            $i ++;
        }
        $e .= '</select></td>';

        $json['user'] = $e;
        $json['cc']   = $cc;
        
        return $this->json($json, true);
    }

    /**
     * 审批记录
     */
    public function logAction()
    {
        $key  = Input::get('key');
        $keys = AES::decrypt($key, config('app.key'));
        list($table, $id) = explode('.', $keys);

        $rows = DB::table('model_step_log')->where('table', $table)
        ->where('table_id', $id)
        ->orderBy('id', 'asc')
        ->get();

        return $this->render([
            'rows' => $rows,
        ]);
    }

    /**
     * 统计待办流程数量
     */
    public function countAction()
    {
        $rows = DB::table('model_step_log')->where('user_id', auth()->id())
        ->where('updated_id', 0)
        ->selectRaw('`table`,count(id) as count')
        ->groupBy('table')
        ->pluck('count', 'table');
        $rows['all'] = $rows->sum();
 
        return json_encode($rows);
    }

    // 删除模型数据
    public function deleteAction()
    {
        $id = Input::get('id');
        if (empty($id)) {
            return $this->json('最少选择一行记录。');
        }

        $gets = Input::get();

        $table = $gets['table'];

        // 主模型字段
        $master = DB::table('model')->where('table', $table)->first();

        // 删除前执行
        Hook::fire($table.'.delete.before', ['table' => $table, 'gets' => $gets, 'master' => $master]);

        // 查询子表
        $models = DB::table('model')->where('parent_id', $master['id'])->get();
        if ($models->count()) {
            foreach ($models as $model) {
                // 删除子表数据
                DB::table($model['table'])->whereIn($model['relation'], $id)->delete();
            }
        }
        
        // 这里以后加入删除文件选项
        DB::table($table)->whereIn('id', $id)->delete();
        
        // 删除审批记录
        DB::table('model_step_log')->where('table', $table)->whereIn('table_id', $id)->delete();

        // 删除后执行
        Hook::fire($table.'.delete.after', ['table' => $table, 'gets' => $gets, 'master' => $master]);

        $msg = $master['name'].'删除成功。';
        session()->flash('message', $msg);
        return $this->json($msg, true);
    }

    public function notification($model, $user_id, $data, $gets)
    {
        $gets['next_step_id'] = is_array($gets['next_step_id']) ? $gets['next_step_id'] : array($gets['next_step_id']);
        $step_ids = array_keys($gets['next_step_id']);

        $step = Step::where('model_id', $model['id'])
        ->whereIn('id', $step_ids)
        ->first();

        $find    = array('#id#');
        $replace = array($data['id']);
        $content = str_replace($find, $replace, $gets['notify_text']);

        $id  = (int)$data['id'];
        $uri = $gets['uri'].'/show';

        // 传入的下一步不是数组
        $user_id = is_array($user_id) ? $user_id : explode(',', $user_id);

        // 合并主办人和抄送人进行提醒
        $_users = array_merge($user_id, explode(',', $gets['next_step_cc']));

        // 过滤掉重复和空值
        $users = [];
        foreach ($_users as $user) {
            if ($user > 0) {
                $users[$user] = $user;
            }
        }
        sort($users);

        if ($users) {
            // 写入消息提醒
            foreach ($users as $user) {
                DB::table('user_message')->insert([
                    'name'    => $model->name,
                    'node'    => $model->table,
                    'node_id' => $id,
                    'content' => $content,
                    'uri'     => $uri,
                    'read_by' => $user,
                ]);
            }

            // 推送信息
            $receive = ['alias'=>$users];
            $extras  = [
                'name'  => $model->name,
                'uri'   => $uri,
                'query' => ['id' => $id],
                'type'  => $model->table,
            ];
            // $res = JPush::send($receive, $content, $extras);
        }

        if ($user_id && $gets['notify_sms']) {
            $mobiles = DB::table('user')
            ->whereIn('id', $user_id)
            ->where('mobile', '!=', '')
            ->pluck('mobile');

            if ($mobiles->count()) {
                SendSms::dispatch($mobiles, $content);
            }
        }
    }
}
