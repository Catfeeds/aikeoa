<table class="tlist">
    <tr>
        <td align="left">
            <form id="select" name="select" action="{{url()}}" method="get">
            @include('order/select')
        </td>
    </tr>

    <tr>
        <td align="left">
            <select id='year' name='year' data-toggle="redirect" rel="{{$query}}">
                 @if($years) @foreach($years as $v)
                    <option value="{{$v}}" @if($v==$selects['select']['year']) selected="true" @endif >{{$v}}年</option>
                 @endforeach @endif
            </select>
            &nbsp;
            <select id='month' name='month' data-toggle="redirect" rel="{{$query}}">
                <option value="">全年</option>
                 @if($months) @foreach($months as $v)
                    <option value="{{sprintf('%02d',$v)}}" @if($v==$selects['select']['month']) selected="true" @endif >{{$v}}月</option>
                 @endforeach @endif
            </select>
            &nbsp;
            <select id='day' name='day' data-toggle="redirect" rel="{{$query}}">
                <option value="">全月</option>
                 @if($selects['select']['month']>0)
                     @if($days) @foreach($days as $v)
                          <option value="{{sprintf('%02d',$v)}}" @if($v==$selects['select']['day']) selected="true" @endif >{{$v}}日</option>
                     @endforeach @endif
                 @endif
            </select>
            &nbsp;
            <select id="category_id" name="category_id" data-toggle="redirect" rel="{{$query}}">
            <option value="0">产品类别</option>
             @if($product_category) @foreach($product_category as $k => $v)
            <option value="{{$v['id']}}" @if($selects['select']['category_id']==$v['id']) selected @endif >{{$v['layer_space']}}{{$v['name']}}</option>
             @endforeach @endif
            </select>
            &nbsp;
            <select id="product_id" name="product_id" data-toggle="redirect" rel="{{$query}}">
            <option value="0">全部产品</option>
             @if($products) @foreach($products as $k => $v)
            <option value="{{$v['id']}}" @if($selects['select']['product_id']==$v['id']) selected @endif >{{$v['name']}} @if($v['spec'])  - {{$v['spec']}} @endif </option>
             @endforeach @endif
            </select>
        </td>
        </tr>
        <tr>
        <td>
            <select id="invoice_type" name="invoice_type" data-toggle="redirect" rel="{{$query}}">
                <option value="">开票类型</option>
                <option value="1" @if($selects['select']['invoice_type']=='1') selected @endif >税票</option>
                <option value="2" @if($selects['select']['invoice_type']=='2') selected @endif >普票</option>
            </select>
            &nbsp;
            <select id="time_type" name="time_type" data-toggle="redirect" rel="{{$query}}">
                <option value="add_time" @if($selects['select']['time_type']=='add_time') selected @endif >订单时间</option>
                <option value="delivery_time" @if($selects['select']['time_type']=='delivery_time') selected @endif >发货时间</option>
            </select>

            &nbsp;
            <select id="warehouse_id" name="warehouse_id" data-toggle="redirect" rel="{{$query}}">
                <option value="">全部仓库</option>
                 @if($warehouses) @foreach($warehouses as $k => $v)
                    <option value="{{$v['id']}}" @if($selects['select']['warehouse_id']==$v['id']) selected @endif >{{$v['layer_space']}}{{$v['title']}}</option>
                 @endforeach @endif
            </select>

            &nbsp;
            <select id="flow_id" name="flow_id" data-toggle="redirect" rel="{{$query}}">
                <option value="">审批流程</option>
                @if($flows) @foreach($flows as $k => $v)
                    <option value="{{$v['id']}}" @if($selects['select']['flow_id']==$v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach @endif
            </select>

            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </td>
    </tr>

    </form>
</table>

<table class="list tab">
<style>
th {white-space:nowrap;}
</style>
<tr class="odd">
    <th align="center" width="120">日期</th>
    <th align="right" width="40">客户数</th>
    <th align="right" width="40">订单数</th>
    <th align="right" width="100">件数</th>
    <th align="right" width="100">金额</th>
    <th align="right" width="100">重量</th>
</tr>
{{:$total = array()}}
 @if($res) @foreach($res as $k => $v)
<tr>
    <td align="center">{{$v['order_date']}}</td>
    <td align="right">{{$v['order_customer']}}</td>
    <td align="right">{{$v['order_count']}}</td>
    <td align="right">{{$v['order_amount']}}</td>
    <td align="right">{{$v['order_money']}}</td>
    <td align="right">{{$v['order_weight']}}</td>
</tr>
{{:$total['order_customer'] += $v['order_customer']}}
{{:$total['order_count'] += $v['order_count']}}
{{:$total['order_amount'] += $v['order_amount']}}
{{:$total['order_money'] += $v['order_money']}}
{{:$total['order_weight'] += $v['order_weight']}}
@endforeach @endif
<tr>
    <th align="center">合计</th>
    <th align="right">{{$total['order_customer']}}</th>
    <th align="right">{{$total['order_count']}}</th>
    <th align="right">{{$total['order_amount']}}</th>
    <th align="right">{{$total['order_money']}}</th>
    <th align="right">{{$total['order_weight']}}</th>
</tr>
</table>
