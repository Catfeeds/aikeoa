<table class="table table-form"  width="100%">
<tr>
	<th align="center" width="60">序号</th>
    <th align="center" width="140">审核人</th>
    <th align="center" width="160">角色</th>
    <th align="center" width="80">岗位效率</th>
    <th align="center" width="80">审核类型</th>
    <th align="left">审核内容</th>
    <th align="center" width="160">审核时间</th>
</tr>

<?php 
$status = array(
    'start' => '开始',
    'next'  => '正常',
    'last'  => '退回',
    'deny'  => '拒绝',
    'end'   => '结束',
);

$end = $order['add_time'];

?>

<tbody>
@if($audit_info)
@foreach($audit_info as $k => $v)
<tr>
	<td align="center" title="{{$v['step_id']}}">{{$k + 1}}</td>
    <td align="center">{{$v['nickname']}}</td>
    <td align="center">{{$v['role_name']}}</td>
    <td align="center">
    <?php 
        $start = $v['add_time'];
        $_start = Carbon\Carbon::createFromTimeStamp($start);
        $_end = Carbon\Carbon::createFromTimeStamp($end);
        echo $_start->diffInHours($_end);
        $end = $start;
    ?>小时
    </td>
    <td align="center">{{$status[$v['step_state']]}}</td>
    <td align="left">{{$v['content']}}</td>
    <td align="center">@datetime($v['add_time'])</td>
</tr>
@endforeach
@endif
</tbody>

</table>
