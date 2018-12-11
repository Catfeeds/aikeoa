<form method="post" action="{{url()}}" id="myform" name="myform">

<div class="panel">

    <div class="panel-heading tabs-box">
        <ul class="nav nav-tabs">
            <li class="@if(Request::action() == 'profile') active @endif">
                <a class="text-sm" href="{{url('profile')}}">我的资料</a>
            </li>
            <li class="@if(Request::action() == 'password') active @endif">
                <a class="text-sm" href="{{url('password')}}">修改密码</a>
            </li>
        </ul>
    </div>

    <div class="panel-body">

        <div class="row">

            <div class="col-sm-2">
                <div class="text-center">
                    <span class="thumb-lg w-auto-folded avatar m-t-sm">
                        <a href="javascript:avatar();" class="hinted" title="修改头像"><img src="{{avatar()}}" id="user-avatar" class="img-full" alt="{{Auth::user()->nickname}}"></a>
                        <div class="h4 font-thin m-t-sm">{{Auth::user()->username}}</div>
                    </span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">旧密码</label>
                    <input type="password" class="form-control input-sm" id="old_password" name="old_password" placeholder="请输入旧密码">
                </div>

                <div class="form-group">
                    <label class="control-label">新密码</label>
                    <input type="password" class="form-control input-sm" id="new_password" name="new_password" placeholder="请输入新密码">
                </div>

                <div class="form-group">
                    <label class="control-label">确认新密码</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control input-sm" placeholder="请输入再次输入新密码">
                </div>

            </div>

        </div>

    </div>
    <div class="panel-footer">
        <div class="col-sm-offset-2">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        </div>
    </div>
</div>

</form>

<script>
function avatar() {
    viewDialog({
        title: '修改头像',
        url: '{{url("avatar")}}',
        dialogClass: 'modal-lg',
        id: 'avatar-dialog',
        buttons: [{
            text: "确定",
            'class': "btn-default",
            click: function() {
                $(this).dialog("close");
            }
        }]
    });
}
</script>
