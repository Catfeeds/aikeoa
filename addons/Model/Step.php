<?php namespace Aike\Model;

use Aike\Index\BaseModel;

use DB;
use Auth;
use Input;

use AES;

class Step extends BaseModel
{
    protected $table = 'model_step';

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    // 检查转入条件
    public function checkCondition()
    {
        $gets = Input::get();

        // 获取用户职位
        $position = DB::table('user_position')->pluck('title', 'id');

        $start_user = Step::getMacroUser($gets['created_by']);
        $start_user['position_name'] = $position[$start_user['user_position']];

        // 当前经办工作用户信息
        $current_user = Step::getMacroUser(Auth::id());
        $current_user['position_name'] = $position[$current_user['user_position']];

        $form_data = [];
        $form_data['[start_user]']       = $start_user['user_name'];
        $form_data['[start_position]']   = $start_user['position_name'];
        $form_data['[start_group]']      = $start_user['group_name'];
        $form_data['[start_role]']       = $start_user['role_name'];
        $form_data['[start_department]'] = $start_user['department_name'];

        $form_data['[edit_user]']       = $current_user['user_name'];
        $form_data['[edit_position]']   = $current_user['position_name'];
        $form_data['[edit_group]']      = $current_user['group_name'];
        $form_data['[edit_role]']       = $current_user['role_name'];
        $form_data['[edit_department]'] = $current_user['department_name'];

        // $form_data['[步骤号]'] = "\$post['setp_id']";
        // $form_data['[流程设计步骤号]'] = $gets['step_number'];

        // 未完成
        //$form_data['[公共附件名称]'] = "\$post['attachment']";
        //$form_data['[公共附件个数]'] = "\$post['attachment']";

        $step = Step::where('id', $gets['current_step_id'])->first();

        if ($step['join'] == '') {
        } else {
            $join  = explode(',', $step['join']);
            $steps = DB::table('model_step')->whereIn('id', $join)->get();
        }

        if ($steps->count()) {
            // 流程转交条件检查
            $conditions = json_decode($step['condition'], true);

            $step_condition = [];
            $null_condition = 0;

            foreach ($steps as $_step) {
                $_conditions = $conditions[$_step['id']];

                if (empty($_conditions)) {
                    $null_condition ++;
                    continue;
                }
                // 存在条件
                if (sizeof($_conditions)) {
                    $wheres = $values = [];

                    foreach ($_conditions as $condition) {
                        $f = $condition['f'];

                        if (isset($form_data[$f])) {
                            $condition['f'] = "\$form_data['".$f."']";
                        } else {
                            // 分割字段名称
                            list($p, $k) = explode('.', $f);
                            // 把变量名称作为字符串赋值
                            $condition['f'] = "\$gets['".$p."']['".$k."']";
                        }
                        $wheres[] = join(' ', $condition);
                    }

                    // 检查主表条件
                    $where = join(' ', $wheres);
                    $test = eval("return $where;");

                    // 条件满足记录步骤数组
                    if ($test) {
                        $step_condition[] = $_step;
                    }

                    // 子表条件检查是否必要，暂时未实现
                    /*
                    foreach ($childrens as $children) {
                        $gets[$children];
                        foreach ($childrens as $children) {
                        }
                    }
                    */
                }
            }
            // 有一种情况，多个转入步骤都是空条件, 或单转入步骤条件为空
            if (count($steps) == $null_condition) {
                $step_condition = $steps;
            }
        }
        return $step_condition;
    }

    public function getMacroUser($user_id)
    {
        if (empty($user_id)) {
            return null;
        }

        return DB::table('user')
        ->LeftJoin('role', 'role.id', '=', 'user.role_id')
        ->LeftJoin('user_group', 'user_group.id', '=', 'user.group_id')
        ->LeftJoin('department', 'department.id', '=', 'user.department_id')
        ->where('user.id', $user_id)
        ->first([
            'user.post as user_position',
            'user.nickname as user_name',
            'role.title as role_name',
            'department.title as department_name',
            'user_group.name as group_name'
        ]);
    }

    // 审批流程状态处理
    public function getStatus($auth, $step, $row = [])
    {
        $id = (int)$row['id'];

        switch ($step['type']) {
            // 指定办理人
            case 'user':
                $ids = explode(',', $step['type_value']);
                if (in_array($auth['id'], $ids)) {
                    $user_id = $auth['id'];
                }
                break;

            // 负责人
            case 'owner':
                $user_id = $auth['salesman_id'];
                break;

            // 指定角色办理人
            case 'role':
                $ids = explode(',', $step['type_value']);
                $roles = DB::table('role')->whereIn('id', $ids)->pluck('name', 'id');
                if (isset($roles[$auth['role_id']])) {
                    $user_id = $auth['id'];
                }
                break;

            // 单据创建者
            case 'created_by':
                $user_id = $data['created_by'];
                break;

            // 直属领导
            case 'leader':
                $user_id = $auth['leader_id'];
                break;

            // 部门负责人
            case 'manager':
                $user_id = $auth['department']['manager'];
                break;

            // 主表字段值
            case 'field':
                if ($step['type_value'] == 'supplier_id') {
                    $supplier = DB::table('supplier')->find($row['supplier_id']);
                    $user_id  = $supplier['user_id'];
                }
                break;

            // 自定义
            case 'custom':
                $user_id = 0;
                break;
        }
        
        $res['user_id'] = $user_id;
        $res['sn']      = $step['sn'];
        $res['edit']    = $user_id == $auth['id'];
        $res['color']   = $step['color'] == '' ? 'default' : $step['color'];
        $res['name']    = $step['name'];
        $res['key']     = AES::encrypt($model['table'].'.'.$id, config('app.key'));

        // 新建表单设置编辑权限
        if ($row['step_sn'] == 1) {
            // 数据存在流程编号是1检查是否自己创建
            if ($row['id']) {
                $res['edit'] = $row['created_by'] == $auth['id'] ? 1 : 0;
            }
        }

        if ($res['edit']) {
            $html = '<a class="label label-'.$res['color'].' label-turn" href="javascript:;" onclick="app.turn(\''.$res['key'].'\');">'.$res['name'].'</a>';
        } else {
            $html = '<span class="label label-'.$res['color'].'">'.$res['name'].'</span>';
        }
        $res['html']  = $html;

        $bg = $res['edit'] == 1 ? 'bg-danger ' : '';
        $res['text'] = '<span class="'.$bg.'badge">'.$step['sn'].'</span> '.$step['name'];

        return $res;
    }
}
