<div class="panel">
    <div class="wrapper-sm">
        @if(isset($access['monitor_export']))
        <div class="pull-right">
            <a type="button" class="btn btn-default btn-sm" onclick="optionDefault('#myform','{{url('monitor_export')}}');"><i class="fa fa-share-square-o"></i> 导出本页</a>
        </div>
        @endif
        <form id="select" name="select" action="{{url()}}" method="get">
            @include('order/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
    </div>

<form id="myform" name="myform" method="post">
<table class="b-t table table-hover">
<tr>
    <th align="center">
        <input type="checkbox" class="select-all">
    </th>
    <th>订单号</th>
    <th>客户名称</th>
    <th>下单到付款天数</th>
    <th>付款到发货天数</th>
    <th>发货到到货天数</th>
    <th>订单满足率</th>
    <th>订单单量</th>
    <th>发货数量</th>
</tr>
@if($rows) @foreach($rows as $k => $v)
<?php
$v['sum_fact_amount'] = $v->datas->sum('fact_amount');
$v['sum_amount'] = $v->datas->sum('amount');
?>
<tr>
    <td align="center"><input type="checkbox" class="select-row" name="order_id[]" value="{{$v['id']}}" /></td>
    <td align="center"><a href="{{url('view')}}?id={{$v['id']}}">{{$v['number']}}</a></td>
    <td align="left">{{$v['company_name']}}</td>

    @if($v['add_time']>0 && $v['pay_time'] > 0)
        {{:$now_time = round(($v['pay_time']-$v['add_time'])/86400)}}
        <td align="center" @if($now_time >= 3) style="color:#f00;" @endif >{{$now_time}}天</td>
     @else
        <td align="center">无</td>
     @endif

     @if($v['pay_time']>0 && $v['delivery_time'] > 0)
        {{:$now_time = round(($v['delivery_time']-$v['pay_time'])/86400)}}
        <td align="center" @if($now_time >= 3) style="color:#f00;" @endif >{{$now_time}}天</td>
     @else
        <td align="center">无</td>
     @endif

     @if($v['delivery_time'] > 0 && $v['arrival_time'] > 0)
        {{:$now_time = round(($v['arrival_time']-$v['delivery_time'])/86400)}}
        <td align="center" @if($now_time >= 5) style="color:#f00;" @endif >{{$now_time}}天</td>
     @else
        <td align="center">无</td>
     @endif

    <td align="center">
    <a title="点击查看详情" href="{{url('monitor_data')}}?id={{$v['id']}}">

         @if($v['sum_fact_amount']>0&&$v['sum_amount']>0)
            {{:$p = number_format(($v['sum_fact_amount']/$v['sum_amount']) * 100, 2)}}
             @if($p>100) 100.00 @else {{$p}} @endif %
         @else
            无
         @endif
    </a>
    </td>
    <td align="right">{{$v['sum_amount']}}</td>
    <td align="right"> @if($v['delivery_time']>0) {{$v['sum_fact_amount']}} @else 无 @endif </td>
</tr>
@endforeach @endif

</table>
</form>

<footer class="panel-footer">
    <div class="row">
        <div class="col-sm-4 hidden-xs"></div>
        <div class="col-sm-8 text-right text-center-xs">
            {{$rows->render()}}
        </div>
    </div>
</footer>

</div>
