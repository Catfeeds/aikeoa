<form id="myform" name="myform" action="{{url()}}" method="post">
<table class="list">
  <tr>
    <th align="left" colspan="5">选择合并订单的主体订单</th>
  </tr>

	<tr class="odd">
    <th align="left" width="70">选择主订单</th>
    <th align="center" width="40">编号</th>
    <th width="120">订单号</th>
    <th align="center" width="50">数量</th>
    <th align="left">订单时间</th>
	</tr>
<tr>
 @if($order) @foreach($order as $k => $v)
<tr>
  <td align="center"><input type="radio" value="{{$v['id']}}" name="subject" /></td>
  <td align="center">{{$v['id']}}</td>
  <td align="center">{{$v['number']}}</td>
  <td align="right">{{$v['amount']}}</td>
  <td>{{$v['add_time'] > 0 ? date("Y-m-d H:i:s",$v['add_time']) : ""}}</td>
</tr>
 @endforeach @endif
</table>

<table class="list">
    <tr class="odd">
        <th align="left">合并方式</th>
    </tr>
    <tr>
        <td>
            <input type="radio" value="1" name="manner" style="vertical-align:middle;" checked="true" />累加模式 <span class="help-inline">(合并后单价以主订单为准，所有产品累加到主订单)</span>
        </td>
    </tr>
    <tr>
        <td>
            <span style="color:#f00;"> 注意: </span>审核流程规则是按照主体订单为准，请注意！
        </td>
    </tr>
</table>

<input type="hidden" name="json" value='{{$json}}' />
<button type="button" onclick="history.back();" class="btn btn-default">返回</button>
<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>

</form>
