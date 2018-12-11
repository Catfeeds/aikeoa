@include('order.print.query')
<table>

<tr>
    <td class="title" colspan="2">{{$setting['print_title']}}{{$title}}</td>
</tr>
<tbody>
<tr>
    <td width="50%" align="left">客户名称:{{$customer['nickname']}}</td>
    <td width="50%" align="left">车牌号：</td>
</tr>
<tr>
    <td align="left">订 单 号:{{$order['number']}}</td>
    <td align="left">订单日期:{{$order['add_time'] > 0 ? date("Y-m-d H:i:s",$order['add_time']) : ""}}</td>
</tr>
<tr>
    <td align="left" colspan="2">备注:{{$v['content']}}</td>
</tr>
</tbody>
</table>

<table>
    <tr>
        <th width="40">序号</th>
        <th width="40">类型</th>
        <th>产品名称</th>
        <th width="40">规格型号</th>
        <th width="60">单位</th>
        <th width="70">订货重量</th>
        <th width="70">订货数量</th>
        <th width="70">实发数量</th>
        <th width="120">生产批号</th>
    </tr>

    {{:$t_amount = $t_fact_amount = $i = $t_weight = 0}}

    <tbody>
     @if($orderinfo)
     @foreach($orderinfo as $v)

        @if($v['advert'] == 1)
            {{:$warehouse_status = 1}}
        @endif

        @if($v['advert'] == 0)

        {{:$type = $orderType[$v['type']]}}
        {{:$i ++}}
        <tr>
            <td align="center">{{$i}}</td>
            <td align="center" style="white-space:nowrap;">{{$type['title']}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
            <td align="center">{{$v['spec']}}</td>
            <td align="center">件</td>
            <td align="right">{{number_format($v['weight'] * $v['amount'],2)}}</td>
            <td align="right">{{$v['amount']}}</td>
            <td align="right"></td>
            <td align="left">{{$v['batch_number']}}</td>
        </tr>

        {{:$t_amount += $v['amount']}}
        {{:$t_fact_amount += $v['fact_amount']}}
        {{:$t_weight += $v['amount'] * $v['weight']}}

        @endif

    @endforeach @endif

    <tr>
        <td align="center">合计</td>
        <td colspan="4"></td>
        <td align="right">{{number_format($t_weight,2)}}</td>
        <td align="right">{{number_format($t_amount,2)}}</td>
        <td align="right"></td>
        <td></td>
    </tr>
    </tbody>
</table>

@if($warehouse_status == 1)
<table>
    <tr>
        <th width="40">序号</th>
        <th width="40">类型</th>
        <th>物料名称</th>
        <th width="40">规格型号</th>
        <th width="60">单位</th>
        <th width="70">订货重量</th>
        <th width="70">订货数量</th>
        <th width="70">实发数量</th>
        <th width="120">生产批号</th>
    </tr>

    {{:$t_amount = $t_fact_amount = $i = $t_weight = 0}}

    <tbody>
    @if($orderinfo) @foreach($orderinfo as $v)

        @if($v['advert'] == 1)
            {{:$warehouse_status = 1}}
        @endif

        @if($v['advert'] == 1)

        {{:$type = $orderType[$v['type']]}}
        {{:$i ++}}
        <tr>
            <td align="center">{{$i}}</td>
            <td align="center" style="white-space:nowrap;">{{$type['title']}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
            <td align="center">{{$v['spec']}}</td>
            <td align="center">件</td>
            <td align="right">{{$v['weight'] * $v['amount']}}</td>
            <td align="right">{{$v['amount']}}</td>
            <td align="right"></td>
            <td align="left"></td>
        </tr>

        {{:$t_amount += $v['amount']}}
        {{:$t_fact_amount += $v['fact_amount']}}
        {{:$t_weight += $v['amount'] * $v['weight']}}

        @endif

    @endforeach @endif

    <tr>
        <td align="center">合计</td>
        <td colspan="4"></td>
        <td align="right">{{$t_weight}}</td>
        <td align="right">{{$t_amount}}</td>
        <td align="right"></td>
        <td></td>
    </tr>
    </tbody>
</table>
 @endif

<table>
    <tr>
        <td>制单人：<input type="text" class="text" value="向楠" /></td>
        <td>审核人：<input type="text" class="text" value="" /></td>
    </tr>

    <tr>
        <td>发货人：<input type="text" class="text" value="" /></td>
        <td>备&nbsp;&nbsp;注：<input class="text" type="text" value="" /></td>
    </tr>
</table>