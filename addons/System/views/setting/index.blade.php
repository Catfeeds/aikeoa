<div class="panel">

<form class="form-horizontal" method="post" action="{{url('store')}}" id="myform" name="myform">

<div class="table-responsive">
<table class="table table-form">
<tr>
    <td width="10%" align="right">系统名称</td>
    <td width="60%" align="left">
        <input class="form-control input-sm" type="text" id="title" name="data[title]" value="{{$setting['title']}}">
    </td>
    <td width="30%" align="left">
    </td>
</tr>
<tr>
    <td align="right">金额小数位数</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="money_decimal" name="data[money_decimal]" value="{{$setting['money_decimal']}}">
    </td>
    <td align="left"></td>
</tr>
<tr>
    <td align="right">数量小数位数</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="quantity_decimal" name="data[quantity_decimal]" value="{{$setting['quantity_decimal']}}">
    </td>
    <td align="left"></td>
</tr>

<tr>
    <td align="right">文件上传限制</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="upload_max" name="data[upload_max]" value="{{$setting['upload_max']}}">
    </td>
    <td align="left"><span class="help-inline">上传文件最大(MB)</span></td>
</tr>
<tr>
    <td align="right">文件上传格式</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="upload_type" name="data[upload_type]" value="{{$setting['upload_type']}}">
    </td>
    <td align="left"></td>
</tr>

<tr>
    <td align="right">图片上传限制</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="image_upload_max" name="data[image_upload_max]" value="{{$setting['image_upload_max']}}">
    </td>
    <td align="left"><span class="help-inline">上传文件最大(MB)</span></td>
</tr>
<tr>
    <td align="right">图片上传格式</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="image_upload_type" name="data[image_upload_type]" value="{{$setting['image_upload_type']}}">
    </td>
    <td align="left"></td>
</tr>

<tr>
    <td align="right">日期格式</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="date_format" name="data[date_format]" value="{{$setting['date_format']}}">
    </td>
    <td align="left"></td>
</tr>
<tr>
    <td align="right">时间格式</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="time_format" name="data[time_format]" value="{{$setting['time_format']}}">
    </td>
    <td align="left"></td>
</tr>
<tr>
    <td align="right">日期时间格式</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="datetime_format" name="data[datetime_format]" value="{{$setting['datetime_format']}}">
    </td>
    <td align="left"></td>
</tr>
<tr>
    <td align="right">登录尝试次数(次)</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="login_try" name="data[login_try]" value="{{$setting['login_try']}}">
    </td>
    <td align="left"><span class="help-inline">最大次数尝试失败后会禁用一段时间</span></td>
</tr>
<tr>
    <td align="right">登录失败后锁定(秒)</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="login_lock" name="data[login_lock]" value="{{$setting['login_lock']}}">
    </td>
    <td align="left"></td>
</tr>
<tr>
    <td align="right">登录失败显示验证码(次)</td>
    <td align="left">
        <input class="form-control input-sm" type="text" id="login_captcha" name="data[login_captcha]" value="{{$setting['login_captcha']}}">
    </td>
    <td align="left"></td>
</tr>
<tr>
    <td align="right"></td>
    <td align="left">
        <button type="submit" class="btn btn-info">提交</button>
    </td>
    <td align="left"></td>
</tr>
</table>

</div>

</form>

</div>
