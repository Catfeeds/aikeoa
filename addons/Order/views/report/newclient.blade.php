<div class="panel">

    <div class="wrapper b-b b-light">
        @if(Auth::user()->role->name != 'customer')
        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            @include('data/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
        @endif
    </div>

    <table class="table">
        <tr>
            <th align="center" width="100">序号</th>
            <th align="left">客户名称</th>
            <th align="left" width="120">客户类型</th>
            <th align="center" width="120">销售金额</th>
        </tr>

        {{:$i = 0}}
        @if($rows[$nowYear])
        @foreach($rows[$nowYear] as $k => $v)
        @if(empty($rows[$lastYear][$k]['money_sum']))
        {{:$i++}}
            <tr>
                <td align="center">{{$i}}</td>
                <td align="left">{{$v['company_name']}} <span style="color:green;">[{{$v['number']}}]</span></td>
                <td align="center">{{$customer_type[$v['post']]['title']}}</td>
                <td align="right">{{$v['money_sum']}} <a href="{{url('order/order/index')}}?salesman_id={{$v['salesman_id']}}&province_id={{$v['province_id']}}&city_id={{$v['city_id']}}&customer_id={{$v['customer_id']}}">[查]</a></td>
            </tr>
        @endif
        @endforeach
        @endif
    </table>
</div>