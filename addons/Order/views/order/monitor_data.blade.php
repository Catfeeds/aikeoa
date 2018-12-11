<table class="list tab">

<tr class="odd">
    <th width="5%">类型</th>
    <th width="20%">单品</th>
    <th width="10%">订单数量</th>
    <th width="10%">实发数量</th>
    <th width="6%">满足率</th>
    <th>原因分析</th>
</tr>

{{:$p = 0.00}}

 @if($res) @foreach($res as $k => $v)

{{:$now_history_amount = $order_history[$k]['amount']}}
{{:$now_info_amount = $order_data[$k]['fact_amount']}}

    <tr>
         @if(isset($add[$k]))
        <td class="center" style="color:#f90;">新曾
         @elseif(isset($delete[$k]))
        <td class="center" style="color:#f00;">删除
         @elseif($now_history_amount <> $now_info_amount)
        <td class="center" style="color:#f90;">修改
         @else
        <td class="center" style="color:#390;">正常
         @endif
        </td>
        <td class="left">{{$product[$k]['name']}}({{$product[$k]['spec']}})</td>
        <td class="right">{{$now_history_amount}}</td>
        <td class="right">{{$now_info_amount}}</td>
        <td class="right">

        {{:$a1 = $now_history_amount/$order_history['total_amount']}}
         @if($now_history_amount>0)
            {{:$a2 = $now_info_amount/$now_history_amount}}
         @endif
        {{:$p += $a1 * $a2}}
        {{number_format(($a1 * $a2) * 100,2)}}%

        </td>
        <td class="left">无</td>
    </tr>
     @endforeach @endif

    <tr>
        <th class="center">合计</th>
        <td class="left"></td>
        <th class="right">{{$order_history['total_amount']}}</th>
        <th class="right">{{$order_data['total_amount']}}</th>
        <th class="right">
            {{number_format($p * 100,2)}}%
        </th>
        <td class="left">无</td>
    </tr>

</table>
