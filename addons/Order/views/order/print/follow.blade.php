@include('order.print.query')

<table>
    <tr>
        <td class="title" colspan="2">
            {{$setting['print_title']}}{{$title}}
        </td>
    </tr>
    <tr>
        <td width="50%" align="left">订 单 号: {{$order['number']}}</td>
        <td width="50%" align="left">订单日期: {{$order['add_time'] > 0 ? date("Y-m-d H:i:s",$order['add_time']) : ""}}</td>
    </tr>
    <tr>
        <td align="left">客户名称: {{$customer['nickname']}}</td>
        <td align="left">公司电话: {{$customer['tel']}}</td>
    </tr>
    <tr>
        <td align="left">仓库地址: {{$customer['warehouse_address']}}</td>
        <td align="left">仓库电话: {{$customer['warehouse_tel']}}</td>
    </tr>

    <tr>
        <td align="left">仓库联系人: {{$customer['warehouse_contact']}}</td>
        <td align="left">仓库联系人手机: {{$customer['warehouse_mobile']}}</td>
    </tr>

    <tr>
        <td align="left">送货车辆长度: @if($order['transport_car_type']){{$order['transport_car_type']}}米@endif</td>
        <td align="left"></td>
    </tr>

    <tr>
        <td align="left" colspan="2">备注: {{$order['description']}}</td>
    </tr>
</table>

<table>
    <tr>
        <th width="7%">序号</th>
        <th width="22%">产品名称</th>
        <th width="8%">产品条码</th>
        <th width="8%">规格型号</th>
        <th width="7%">单位</th>
        <th width="7%">实发数量</th>
        <th width="7%">备注</th>
        <th width="7%">存货编码</th>
    </tr>

    {{:$t_fact_amount = $i = 0}}

    @if($orderinfo)
    @foreach($orderinfo as $v)

        @if($v['advert'] == 1)
            {{:$warehouse_status = 1}}
        @endif

        @if($v['advert'] == 0)

        {{:$type = $product_type[$v['type']]}}
        {{:$i++}}

        <tr>
            <td align="center">{{$i}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
            <td align="center">{{$v['barcode']}}</td>
            <td align="center">{{$v['spec']}}</td>
            <td align="center">件</td>
            <td align="right">{{$v['fact_amount']}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['content']}}</td>
            <td align="center">{{$v['stock_number']}}</td>
        </tr>
        {{:$t_fact_amount += $v['fact_amount']}}
        @endif

    @endforeach
    @endif

    <tr>
        <td align="center">合计</td>
        <td colspan="4"></td>
        <td  align="right">{{number_format($t_fact_amount,2)}}</td>
        <td colspan="2"></td>
    </tr>
</table>

@if($warehouse_status == 1)
<table>
    <tr>
        <th width="7%">序号</th>
        <th width="22%">物料名称</th>
        <th width="8%">物料条码</th>
        <th width="8%">规格型号</th>
        <th width="7%">单位</th>
        <th width="7%">实发数量</th>
        <th width="7%">备注</th>
    </tr>

    {{:$t_fact_amount = $i = 0}}

    @if($orderinfo)
    @foreach($orderinfo as $v)

        @if($v['advert'] == 1)

        {{:$type = $product_type[$v['type']]}}
        {{:$i++}}

        <tr>
            <td align="center">{{$i}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
            <td align="center">{{$v['barcode']}}</td>
            <td align="center">{{$v['spec']}}</td>
            <td align="center">件</td>
            <td align="right">{{$v['fact_amount']}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['content']}}</td>
        </tr>
        {{:$t_fact_amount += $v['fact_amount']}}
        @endif

    @endforeach
    @endif

    <tr>
        <td align="center">合计</td>
        <td colspan="4"></td>
        <td  align="right">{{$t_fact_amount}}</td>
        <td colspan="1"></td>
    </tr>
</table>
@endif

<table>
<tr>
    <td>制单人：<input type="text" class="text" value="向楠" /></td>
    <td>备注：<input type="text" class="text" style="width:300px;" value="" /></td>
</tr>
</table>