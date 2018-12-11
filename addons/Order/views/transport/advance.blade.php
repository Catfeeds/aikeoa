<div class="panel">

    <div class="wrapper">
    @if(Auth::user()->role->name != 'customer')

            <form id="select" class="form-inline" name="select" action="{{url()}}" method="get">
                @include('order/select')
                <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
    @endif
    </div>

<table class="table m-b-none b-t table-hover">

<tr>
    <th align="center" width="160">计划出货时间</th>
    <th align="center">仓位</th>
    <th align="center">仓号</th>
    <th align="center">订单号</th>
    <th align="center">订单数量</th>
    <th align="left" width="160">客户名称</th>
    <th align="center">承运车牌</th>
    <th align="center" width="120">承运公司</th>
    <th align="center">点击打印</th>
</tr>

 @if($res) @foreach($res as $k => $v)
<tr>
    <td align="center">{{$v['advance_time'] > 0 ? date("Y-m-d H:i:s",$v['advance_time']) : ""}}</td>
    <td align="center">{{$v['advance_depot']}}</td>
    <td align="center">{{$v['advance_depot_number']}}</td>
    <td align="center">{{$v['number']}}</td>
    <td align="right">{{$v->datas->sum('amount')}}</td>
    <td align="left">{{$v['company_name']}}</td>
    <td align="center">{{$v['advance_car_number']}}</td>
    <td align="center">{{$v['advance_car_company']}}</td>
    <td align="center"><a target="_blank" href="{{url('order/print',['order_id'=>$v['id']])}}">打印出货单</a></td>
</tr>
 @endforeach @endif

<form>
</table>

<footer class="panel-footer">
      <div class="row">
        <div class="col-sm-4 hidden-xs">
        </div>
        <div class="col-sm-8 text-right text-center-xs">
            {{$res->render()}}
        </div>
      </div>
    </footer>

</div>