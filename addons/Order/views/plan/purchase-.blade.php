<style type="text/css">
table.list td {border-left:1px solid #D8E8F6;}
table.list th {border-left:1px solid #D6E4F1;}
td,th {white-space:nowrap;}
</style>

<table class="list tab">
    <tr class="odd">
        <th align="left">产品名称</th>
        <th align="center">当前库存</th>
        <th align="center">库存差值</th>
        <th align="center">订单汇总</th>
        <th align="center">已打款待发</th>
        <th align="center">未打款待发</th>
    </tr>
      
    <tr>
        <th align="right">数据汇总(件)</th>
        <td align="right"><strong>{{array_sum($inventory)}}</strong></td>
        <td align="right">
            {{:$k = array_sum($inventory) - $all['a']}}
            <strong style="color: @if($k<0) red @else green @endif ;">{{$k}}</strong>
        </td>
        <td align="right"><strong>{{$all['a']}}</strong></td>
        <td align="right"><strong>{{$all['b']}}</strong></td>
        <td align="right"><strong>{{$all['c']}}</strong></td>
    </tr>

     @if($products) @foreach($products as $k => $v)
    <tr>
        <td align="top">{{$v['name']}} @if($v['spec'])  - {{$v['spec']}} @endif </td>
        <td align="right"><strong>{{$inventory[$k]}}</strong></td>
        <td align="right">
            {{:$s = $inventory[$k] - $moneyall[$k]}}
            <strong style="color: @if($s<0) red @else green @endif ;">{{$s}}</strong>
        </td>
        <td align="right">{{$moneyall[$k]}}</td>
        <td align="right">{{$money[$k]}}</td>
        <td align="right">{{$single[$k]}}</td>
    </tr>
     @endforeach @endif
</table>
