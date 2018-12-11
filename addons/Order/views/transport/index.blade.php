<table class="tlist">
    <tr>
        <td align="left">
            <form id="select" name="select" action="{{url()}}" method="get">
                @include('order/select')
                <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
        </td>
    </tr>
</table>

<style type="text/css">
    td,th {white-space:nowrap;}
</style>
<table class="list tab">

<tr class="odd">
    <th align="center" width="80">订单号</th>
    <th align="left">公司名称</th>
    <th align="center" width="110">订单时间</th>
    <th align="left">订单数量</th>
    <th align="left">订单金额</th>
    <th align="center" width="120">发货时间</th>
    <th align="left">发货数量</th>
    <th align="left">发货金额</th>
    <th align="left">发货方式</th>
    <th align="left">承运公司</th>
    <th align="left">承运司机电话</th>
    <th align="center" width="120">预达时间</th>
    <th align="left">代垫运费</th>
    <th align="center" width="120">实达时间</th>
</tr>

 @if($rows) @foreach($rows as $k => $v)
<tr>
    <td align="center"><a href="{{url('order/view',['id'=>$v['order_id']])}}">{{$v['number']}}</a></td>
    <td align="left">{{$v['company_name']}}</td>
    <td align="center">{{$v['add_time'] > 0 ? date("Y-m-d H:i:s",$v['add_time']) : ""}}</td>
    <td align="right">{{$v['amount']}}</td>
    <td align="right">{{$v['money']}}</td>
    <td align="center">{{$v['delivery_time'] > 0 ? date("Y-m-d H:i:s",$v['delivery_time']) : ""}}</td>
    <td align="right"> @if($v['fact_amount']<>$v['amount']) <strong class="red">{{$v['fact_amount']}}</strong> @else {{$v['fact_amount']}} @endif </td>
    <td align="right">{{$v['fact_money']}}</td>
    <td align="left">{{$v['manner']}}</td>
    <td align="left">{{$v['carriage']}}</td>
    <td align="left">{{$v['phone']}}</td>
    <td align="center">{{$v['advance_arrival_time'] > 0 ? date("Y-m-d H:i:s",$v['advance_arrival_time']) : ""}}</td>
    <td align="left">{{$v['freight']}}</td>
    <td align="center">{{$v['arrival_time'] > 0 ? date("Y-m-d H:i:s",$v['arrival_time']) : ""}}</td>
</tr>
 @endforeach @endif

<form>
</table>

<div class="pull-right">
{{$rows->render()}}
</div>
