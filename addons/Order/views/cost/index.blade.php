<table class="tlist">
    <tr>
        <td align="left">
            <form id="myform" name="myform" action="{{url()}}" method="get">
              @include('cost/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
        </td>
    </tr>
</table>

<table class="list tab">
    <thead>
    <tr class="odd">
        <th width="200" align="center">费用类型</th>
        <th width="80" align="center">支持金额</th>
        <th width="160" align="center">发货时间</th>
        <th width="140" align="center">订单号</th>
        <th align="left">客户名称</th>
    </tr>
    </thead>

    <tbody>
    {{:$total_amount = 0}}
     @if($res) @foreach($res as $k => $v)
    <tr>
        <td align="left">{{$order_type[$v['type']]['text']}}</td>
        <td align="right">{{$v['amount']}}</td>
        <td align="center">{{$v['delivery_time'] > 0 ? date("Y-m-d H:i:s",$v['delivery_time']) : ""}}</td>
        <td align="left"><a href="{{url('order/view',['id'=>$v['id']])}}">[查]</a> {{$v['number']}}</td>
        <td align="left">[<a href="{{url('customer/customer/view',['id'=>$v['customer_id']])}}">查</a>] {{$v['company_name']}}</td>
    </tr>
    {{:$total_amount += $v['amount']}}
     @endforeach @endif
    <tr>
        <td align="center"><strong>合计</strong></td>
        <td align="right">{{$total_amount}}</td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="right"></td>
    </tr>
    </tbody>
</table>
