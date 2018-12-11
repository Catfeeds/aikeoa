@include('order.print.query')

<table>

<tr>
    <td class="title" colspan="2">{{$setting['print_title']}}{{$title}}</td>
</tr>
<tr>
    <td align="left" colspan="2">车牌号：</td>
</tr>

<!--
<tr>
    <td colspan="2" style="white-space:normal;">本单为营运课仓库员出货凭据，出货后交付交财务会计与发货员所提交的发货单进行对照审核确认。</td>
</tr>
<tr>
    <td align="left">订 单 号: {{$order['number']}}</td>
    <td align="left">订单日期: {{$order['add_time'] > 0 ? date("Y-m-d H:i:s",$order['add_time']) : ""}}</td>
</tr>
<tr>
    <td align="left">客户名称: {{$customer['company_name']}}</td>
    <td align="left">预发时间: {{$transport['advance_time'] > 0 ? date("Y-m-d H:i:s",$transport['advance_time']) : ""}}</td>
</tr>
<tr>
    <td align="left">承运公司: {{$transport['advance_car_company']}}</td>
    <td align="left">装货车牌: {{$transport['advance_car_number']}}</td>
</tr>
<tr>
    <td align="left">预发仓位: {{$transport['advance_depot']}}</td>
    <td align="left">预发仓号: {{$transport['advance_depot_number']}}</td>
</tr>
-->

</table>

<table>
    <tr>
        <th width="40">序号</th>
        <th width="40">类型</th>
        <th>产品名称</th>
        <th width="40">规格型号</th>
        <th width="60">单位</th>
        <th width="70">重量</th>
        <th width="70">订单数量</th>
        <th width="70">实发数量</th>
        <th width="120">生产批号</th>
    </tr>

    {{:$t_amount = $t_fact_amount = $i = $t_weight = 0}}

@if($orderinfo)
@foreach($orderinfo as $v)

    {{:$type = $orderType[$v['type']]}}
    {{:$i ++}}
<tr>
    <td align="center">{{$i}}</td>
    <td align="center" style="white-space:nowrap;">{{$type['title']}}</td>
    <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
    <td align="center" style="white-space:nowrap;">{{$v['spec']}}</td>
    <td align="center">件</td>
    <td align="right">{{number_format($v['weight'] * $v['amount'],2)}}</td>
    <td align="right">{{$v['amount']}}</td>
    <td align="right"></td>
    <td align="left"></td>
</tr>

{{:$t_amount += $v['amount']}}
{{:$t_fact_amount += $v['fact_amount']}}
{{:$t_weight += $v['amount'] * $v['weight']}}

@endforeach
@endif

<tr>
    <td align="center">合计</td>
    <td colspan="4"></td>
    <td align="right">{{number_format($t_weight,2)}}</td>
    <td align="right">{{number_format($t_amount,2)}}</td>
    <td align="right"></td>
    <td></td>
</tr>
<tr>
    <td align="left" colspan="9">本订单单品总数：{{$select['total']['0']}} 本出货单品总数：{{$select['total']['1']}}</td>
</tr>
</table>

<table>
<tr>
    <td>制单人：<input type="text" class="text" value="向楠" /></td>
    <td>审核人：<input type="text" class="text" value="" /></td>
</tr>

<tr>
    <td>发货人：<input type="text" class="text" value="" /></td>
    <td>备&nbsp;&nbsp;注：<input type="text" class="text" value="" /></td>
</tr>

</table>
