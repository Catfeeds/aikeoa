<table class="tlist">
    <tr>
        <td>
            <a href="{{url('sms')}}" class="btn btn-primary btn-sm">设置</a>
            <a href="{{url('sms',array('type'=>'test'))}}" class="btn btn-primary btn-sm">测试</a>
        </td>
    </tr>
</table>

@if($type == 'test')

<form method="post" action="{{url('sms')}}" id="myform" name="myform">
<table class="list">
    <tr class="x-line">
        <td width="140" align="right">发送手机</td>
        <td align="left">
            <input class="input-text" style="width:200px" name="data[sms_to]" type="text">
        </td>
    </tr>
    <tr class="x-line">
        <td align="right">发送内容</td>
        <td align="left">
            <textarea class="input-text" style="width:400px;height:50px" name="data[sms_text]" cols="50" rows="10"></textarea>
        </td>
    </tr>
</table>

<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 测试发送</button>

</form>

@else

<form method="post" action="{{url('add')}}" id="myform" name="myform">
<table class="list">
    <tr class="x-line">
        <td width="140" align="right">开启短信功能</td>
        <td align="left">
            <input name="data[sms_on]" id="sms_on_1" type="radio" value="1"@if($setting['sms_on'] == 1) checked="checked"@endif> <label for="sms_on_1">是</label>
            <input name="data[sms_on]" id="sms_on_2" type="radio" value="2"@if($setting['sms_on'] == 2) checked="checked"@endif> <label for="sms_on_2">否</label>
            <span class="help-inline"></span>
        </td>
    </tr>
    <tr class="x-line">
        <td align="right">短信序列号</td>
        <td align="left">
            <input class="input-text" style="width:200px" name="data[sms_serial_number]" type="text" value="{{$setting['sms_serial_number']}}">
        </td>
    </tr>
    <tr class="x-line">
        <td align="right">短信密码</td>
        <td align="left">
            <input class="input-text" style="width:200px" name="data[sms_password]" type="text" value="{{$setting['sms_password']}}">
        </td>
    </tr>
</table>

<input name="tab" type="hidden" value="{{$_action}}">
<button type="button" onclick="history.back();" class="btn btn-default">返回</button>
<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>

</form>

@endif
