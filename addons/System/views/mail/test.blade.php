<div class="panel">

<form class="form-horizontal" method="post" action="{{url('mail_test')}}" id="myform" name="myform">

<table class="table table-form">
<tr>
    <td width="120" align="right">收信人邮箱地址</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="company" name="mail_to" value="{{old('mail_to', $mail_to)}}">
    </td>
</tr>
<tr>
    <td align="right"></td>
    <td align="left">
        <button type="submit" class="btn btn-info">发送</button>
        <a href="{{url('mail')}}" class="btn btn-default">返回</a>
    </td>
</tr>
</table>
</form>
</div>