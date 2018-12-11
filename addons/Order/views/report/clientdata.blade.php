<div class="panel">

    <div class="wrapper b-b b-light">
        <div class='h5'>{{$single['cat']}} * ({{$single['name']}} - {{$single['spec']}}) - {{$year}}年度({{$month}}月)未进货经销商列表</div>
    </div>

    <table class="table">
        <tr>
            <th width="40">序号</th>
            <th width="200">经销商</th>
            <th width="100">区域</th>
            <th align="left">单品</th>
        </tr>
        @if($customers)
        <?php $i = 0; ?>
        @foreach($customers as $key => $value)
        @if(empty($notpurchase[$key]))
        <tr>
            <td align="center">{{$i + 1}}</td>
            <td align="left">{{$value['customer_id']}}</td>
            <td align="center">{{$value['area']}}</td>
            <td align="left">{{$single['name']}} - {{$single['spec']}}</td>
        </tr>
        @endif
        <?php $i++; ?>
        @endforeach
        @endif
    </table>

</div>

<div class="panel">

    <div class="wrapper b-b b-light">
        <div class='h5'>{{$single['cat']}} * ({{$single['name']}} - {{$single['spec']}}) - {{$year}}度年经销商销售分析</div>
    </div>

    <table class="table">
        <tr>
            <th width="40">序号</th>
            <th width="200">经销商</th>
            <th width="100">区域</th>
            <th align="left">单品</th>
            <th width="100">金额</th>
        </tr>
    <?php $i = 0; ?>
    @if($single['all'])
    @foreach($single['all'] as $key => $value)
    <tr>
      <td align="center">{{$i + 1}}</td>
    	<td align="left">{{$customers[$key]['customer_id']}}</td>
        <td align="center">{{$customers[$key]['area']}}</td>
    	<td align="left">{{$single['name']}} - {{$single['spec']}}</td>
    	<td align="right">{{$value}}</td>
    </tr>
    <?php $i++; ?>
    @endforeach 
    @endif
    </table>

</div>