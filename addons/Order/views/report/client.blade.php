<div class="panel">

    <div class="wrapper b-b b-light">
        <div class='h4'>{{$year_id}}年度发生交易客户数[{{sizeof($single['customer'])}}]</div>
    </div>

    <div class="wrapper b-b b-light">

        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            
            @if(Auth::user()->role->name != 'customer')
                @include('data/select')
                &nbsp;
            @endif
            <select class="form-control input-sm" id='year' name='year' data-toggle="redirect" rel="{{$query}}">
                @if($years)
                    @foreach($years as $v)
                    <option value="{{$v}}" @if($select['select']['year']==$v) selected @endif>{{$v}}年</option>
                    @endforeach
                @endif
            </select>

            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
    </div>

    <table class="table">
    <tr>
        <th>品类</th>
        <th>单品</th>

        <th>总销售家数</th>

        @if($months)
        @foreach($months as $k => $v)
            <th>{{$v}}月</th>
        @endforeach
        @endif

        @if($single['sum'])
        @foreach($single['sum'] as $k => $v)
        <tr>
            <td align="center">{{$single['category'][$k]}}</td>
            <td align="left"><a href="{{url('clientdata')}}?aspect_id={{$select['select']['aspect_id']}}&region_id={{$select['select']['region_id']}}&circle_id={{$select['select']['circle_id']}}&customer_id={{$select['select']['customer_id']}}&product_id={{$k}}&year={{$year}}">[查]</a> {{$single['product'][$k]['product_name']}} - {{$single['product'][$k]['product_spec']}}</td>
            <td align="right">{{sizeof($single['all'][$k])}}</td>
            @if($months)
            @foreach($months as $v2)
            <td align="right">
                {{:$sum = sizeof($v[$v2])}}
                @if($sum>0) {{$sum}} @else <span style="color:#ccc;">0</span> @endif
            </td>
           @endforeach
           @endif
        </tr>
        @endforeach
        @endif
    </table>
</div>