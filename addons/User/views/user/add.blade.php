<form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">

<div class="panel">

<div class="table-responsive">

<table class="table table-form">
    <tr>
        <td width="10%" align="right">姓名</td>
        <td width="40%" align="left">
            <input type="text" id="nickname" name="nickname" class="form-control input-inline input-sm" value="{{$row['nickname']}}">
        </td>

        <td width="10%" align="right">生日</td>
        <td width="40%" align="left">
            <input type="text" id="birthday" name="birthday" value="{{$row->birthday}}" data-toggle="date" class="form-control input-inline input-sm">
        </td>
    </tr>
    <tr>
        <td align="right">用户名</td>
        <td align="left">
            <input type="text" id="username" name="username" value="{{$row->username}}" class="form-control input-inline input-sm">
        </td>

        <td align="right">密码</td>
        <td align="left">
            <input type="text" id="password" name="password" placeholder="不修改密码请留空。" class="form-control input-inline input-sm">
        </td>
    </tr>
    <tr>
        <td align="right">手机</td>
        <td align="left">
            <input type="text" id="mobile" name="mobile" value="{{$row->mobile}}" class="form-control input-inline input-sm">
        </td>

        <td align="right">工作电话</td>
        <td align="left">
            <input type="text" id="tel" name="tel" value="{{$row->tel}}" class="form-control input-inline input-sm">
        </td>
    </tr>
    <tr>
        <td align="right">邮箱</td>
        <td align="left">
            <input type="text" id="email" name="email" value="{{$row->email}}" class="form-control input-inline input-sm">
        </td>

        <td align="right">微信</td>
        <td align="left">
            <input type="text" id="weixin" name="weixin" value="{{$row->weixin}}" class="form-control input-inline input-sm">
        </td>
    </tr>
    <tr>
        <td align="right">职位</td>
        <td align="left">
            <select class="form-control input-inline input-sm" name="post" id="post">
                <option value=""> - </option>
                @if($positions)
                @foreach($positions as $position)
                    <option value="{{$position->id}}" @if($row->post ==$position->id) selected @endif>{{$position->title}}</option>
                @endforeach
                @endif
            </select>
        </td>
        <td align="right">性别</td>
        <td align="left">
            <select class="form-control input-inline input-sm" name="gender" id="gender">
                @foreach(option('user.gender') as $gender)
                    <option value="{{$gender['id']}}" @if($row->gender == $gender['id']) selected @endif>{{$gender['name']}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td align="right">部门</td>
        <td align="left">
            {{Dialog::user('department','department_id', $row->department_id, 0, 0)}}
        </td>
        <td align="right">角色</td>
        <td align="left">
            {{Dialog::user('role','role_id', $row->role_id, 0, 0)}}
        </td>
    </tr>
    <tr>
        <td align="right">直属领导</td>
        <td align="left">
            {{Dialog::user('user','leader_id', $row->leader_id, 0, 0)}}
        </td>
        <td align="right">人事资料</td>
        <td align="left">{{Dialog::user('hr','hr_id', $row->hr->id, 0, 0)}}</td>
    </tr>
    <tr>
        <td align="right">账号状态</td>
        <td align="left">
            <select class="form-control input-inline input-sm" name="status" id="status">
                <option value="1" @if($row->status == '1') selected @endif>启用</option>
                <option value="0" @if($row->status == '0') selected @endif>停用</option>
            </select>
        </td>
        <td align="right">安全密钥</td>
        <td align="left">
            <div class="w-full">
                <code id="secret">{{$row->auth_secret}}</code>
                <a class="btn btn-primary btn-xs" onclick="getSecret();" href="javascript:;" title="更新密钥后之前的密钥会失效。">更新</a>
            </div>
        </td>
    </tr>
    <tr>
        <td align="right">绑定IP</td>
        <td align="left">
            <textarea name="auth_ip" class="form-control input-sm" id="auth_ip" placeholder="请填写绑定IP地址，允许多行。">{{$row->auth_ip}}</textarea>
        </td>
        <td align="right">绑定设备ID</td>
        <td align="left">
            <textarea name="auth_device_id" class="form-control input-sm" id="auth_device_id" placeholder="请填写绑定设备ID，允许多行。">{{$row->auth_device_id}}</textarea>
        </td>
    </tr>
    <tr>
        <td align="right">其他选项</td>
        <td align="left">
            @if(is_admin())
            <label class="checkbox-inline i-checks i-checks-sm"><input type="checkbox" name="admin" id="admin" value="1" @if($row->admin == 1) checked @endif><i></i> 超级管理员</label>
            @endif
            <label class="checkbox-inline i-checks i-checks-sm"><input type="checkbox" name="auth_totp" id="auth_totp" value="1" @if($row->auth_totp == 1) checked @endif><i></i> 二次验证</label>
            <label class="checkbox-inline i-checks i-checks-sm"><input type="checkbox" name="auth_device" id="auth_device" value="1" @if($row->auth_device == 1) checked @endif><i></i> 验证设备ID</label>
        </td>
        <td align="right"></td>
        <td align="left">
        </td>
    </tr>

    <tr>
        <td align="left" colspan="4">
            <input type="hidden" name="id" value="{{$row->id}}">
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
        </td>
    </tr>

</table>

</div>
</div>
</form>

<script type="text/javascript">

$('#myform').submit(function() {
    var url  = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function(res) {
        if(res.status) {
            $.toastr('success', res.data, '提醒');
            window.location.href = res.url;
        } else {
            $.toastr('error', res.data, '错误');
        }
    }, 'json');
    return false;
});

function getSecret() {
    $.messager.confirm('安全密钥', '确定要更新安全密钥。', function() {
        $.post("{{url('secret')}}",{id:'{{$row->id}}'}, function(res) {
            $("#secret").html(res.data);
        },'json');
    });
}
</script>