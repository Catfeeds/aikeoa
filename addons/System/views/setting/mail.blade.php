<div class="panel">

<form class="form-horizontal" method="post" action="{{url('store')}}" id="myform" name="myform">

<div class="table-responsive">
<table class="table table-form">
<tr>
    <td width="120" align="right">SMTP服务器</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="mail_server" name="data[mail_server]" value="{{$setting['mail_server']}}">
    </td>
</tr>
<tr>
    <td align="right">SMTP发送端口</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="mail_port" name="data[mail_port]" value="{{$setting['mail_port']}}">
    </td>
</tr>
<tr>
    <td align="right">发信人邮件地址</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="mail_from" name="data[mail_from]" value="{{$setting['mail_from']}}">
    </td>
</tr>
<tr>
    <td align="right">SMTP验证用户名</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="mail_username" name="data[mail_username]" value="{{$setting['mail_username']}}">
    </td>
</tr>
<tr>
    <td align="right">SMTP验证密码</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="mail_password" name="data[mail_password]" value="{{$setting['mail_password']}}">
    </td>
</tr>
<tr>
    <td align="right"></td>
    <td align="left">
        <label class="i-checks i-checks-sm"><input name="data[mail_encryption]" id="mail_encryption" type="checkbox" value="ssl"@if($setting['mail_encryption'] == 'ssl') checked @endif><i></i> 使用 SSL</label>
        <span class="help-inline"></span>
    </td>
</tr>
<tr>
    <td align="right"></td>
    <td align="left">
        <button type="submit" class="btn btn-info">提交</button>
        <a href="{{url('mail_test')}}" class="btn btn-danger">测试邮件</a>
    </td>
</tr>

        </form>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('#mail_encryption').on('click', function() {

        var checked = $(this).prop('checked');
        if(checked) {
            $('#mail_port').val('587');
        } else {
            $('#mail_port').val('25');
        }
    });
})
</script>