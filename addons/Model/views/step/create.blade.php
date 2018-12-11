<form method="post" action="{{url()}}" id="myform" name="myform">

<div class="panel">
    <table class="table table-form">
        <tr>
            <td align="right" width="10%">进程名称<span class="red">*</span></td>
            <td>
                <input type="text" id="name" name="name" value="{{$row->name}}" class="form-control input-sm input-inline">
            </td>
        </tr>
        
        <tr>
            <td align="right">进程序号 <span class="red">*</span></td>
            <td>
                <input type="text" id="sn" name="sn" value="{{$row->sn}}" class="form-control input-sm input-inline">
            </td>
        </tr>

        <tr>
            <td align="right">下一进程</td>
            <td>
                @foreach($steps as $step)
                    <label class="checkbox-inline">
                        <input name="join[]" type="checkbox" id="join_{{$step->id}}" value="{{$step->id}}" @if($step->id == $row->id) disabled @endif @if(in_array($step->id, $row->join)) checked @endif>
                        @if($step->sn > 0)
                            <span class="badge">{{$step->sn}}</span> 
                        @endif
                        {{$step->name}}
                    </label>
                @endforeach
            </td>
        </tr>

        <tr>
            <td align="right">进程类型</td>
            <td class="form-inline">
                <select class="form-control input-sm input-inline" name="type" id="type">
                    <option value="leader" @if($row->type == 'leader') selected @endif>直属领导</option>
                    <option value="manager" @if($row->type == 'manager') selected @endif>部门负责人</option>
                    <option value="owner" @if($row->type == 'owner') selected @endif>负责人</option>
                    <option value="school_owner" @if($row->type == 'school_owner') selected @endif>学校负责人</option>
                    <option value="school_service" @if($row->type == 'school_service') selected @endif>学校营运负责人</option>
                    <option value="user" @if($row->type == 'user') selected @endif>指定办理人</option>
                    <option value="role" @if($row->type == 'role') selected @endif>指定角色</option>
                    <option value="created_by" @if($row->type == 'created_by') selected @endif>单据创建者</option>
                    <option value="field" @if($row->type == 'field') selected @endif>指定字段</option>
                    <option value="position" @if($row->type == 'position') selected @endif>职位</option>
                </select>

                <span id="type_user" class="type" style="display:none;">
                    {{Dialog::user('user','type_value[user]', $row->type_value, 1, 0)}}
                </span>

                <span id="type_role" class="type" style="display:none;">
                    {{Dialog::user('role','type_value[role]', $row->type_value, 1, 0)}}
                </span>

                <span id="type_field" class="type" style="display:none;">
                    <select class="form-control input-sm" name="type_value[field]">
                        @if($columns[$model->table]['fields'])
                        @foreach($columns[$model->table]['fields'] as $column)
                            <option value="{{$column->field}}" @if($row->type_value == $column->field) selected @endif>{{$column->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </span>
            </td>
        </tr>

        <tr>
            <td align="right">并发选项</td>
            <td>
                <select class="form-control input-sm input-inline" name="concurrent" id="concurrent">
                    <option value="0" @if($row->concurrent == 0) selected @endif>禁止</option>
                    <option value="1" @if($row->concurrent == 1) selected @endif>允许</option>
                    <option value="2" @if($row->concurrent == 2) selected @endif>强行</option>
                </select>
            </td>
        </tr>

        <tr>
            <td align="right">并发合并</td>
            <td>
                <select class="form-control input-sm input-inline" name="merge" id="merge">
                    <option value="0" @if($row->merge == 0) selected @endif>无</option>
                    <option value="1" @if($row->merge == 1) selected @endif>强行</option>
                </select>
            </td>
        </tr>

        <tr>
            <td align="right">表单权限</td>
            <td>
                <select class="form-control input-sm input-inline" name="permission_id" id="permission_id">
                    <option value=""> - </option>
                    @foreach($permissions as $permission)
                        <option value="{{$permission['id']}}" @if($row->permission_id == $permission['id']) selected @endif>{{$permission['name']}}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <td align="right">颜色</td>
            <td>
                <select class="form-control input-sm input-inline" name="color">
                    @foreach($colors as $color)
                        <option value="{{$color}}" class="bg-{{$color}}" @if($row->color == $color) selected @endif>{{$color}}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <td align="right">提醒</td>
            <td>
                <label class="checkbox-inline"><input name="notify[sms]" type="checkbox" id="notify-sms" value="1" @if($row->notify['sms'] == 1) checked @endif>短信</label>
            </td>
        </tr>

        <tr>
            <td align="right">抄送</td>
            <td class="form-inline">
                {{Dialog::user('user','notify_users', $row->notify_users, 1, 0)}}
            </td>
        </tr>
        <!--
        <tr>
            <td align="right">提醒内容</td>
            <td>
                <input type="text" id="notify-text" name="notify[text]" value="{{$row->notify['text']}}" class="form-control input-sm input-inline">
            </td>
        </tr>
        -->
        <tr>
            <td align="right">退回</td>
            <td>
                <select class="form-control input-sm input-inline" name="back" id="back">
                    <option value="0" @if($row->back == 0) selected @endif>无</option>
                    <option value="1" @if($row->back == '1') selected @endif>上一步</option>
                </select>
            </td>
        </tr>

    </table>
</div>

<div class="panel">
    <table class="table table-form m-b-none">
        <tr>
            <td>
                <input type="hidden" name="id" value="{{$row->id}}">
                <input type="hidden" name="model_id" value="{{$model->id}}">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 保存</button>
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            </td>
        </tr>
    </table>
</div>

</form>

<script type="text/javascript">
$(function() {
    userType('{{$row->type}}');
    $('#type').on('change', function() {
        userType(this.value);
    });
    /*
    $('.field-edit').on('click', function() {
        var key = $(this).data('key');
        $('#' + key + '_secret').prop('disabled', this.checked);
    });

    $('.field-secret').on('click', function() {
        var key = $(this).data('key');
        $('#' + key + '_edit').prop('disabled', this.checked);
    });
    */
});

function userType(type) {
    type = type || '';
    $('.type').hide().prop('disabled', true);
    $('#type_' + type).show().prop('disabled', false);
}

</script>