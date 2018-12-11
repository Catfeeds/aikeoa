<div class="panel">

    <div class="wrapper b-b b-light">
        @if(Auth::user()->role->name != 'customer')
        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            @include('data/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
        @endif
    </div>

<!--
<table class="tlist">
    @if(Auth::user()->role->name != 'customer')
    <form id="select" name="select" action="{{url()}}" method="get">
    <tr>
      <td align="left">
          @include('data/select')
          <button type="submit" class="btn btn-default btn-sm">过滤</button>
      </td>
    </tr>
    </form>
    @endif

</table>
-->

<table class="table">

    <tr>
        <th align="center" width="60">序号</th>
        <th align="left">客户名称</th>
        <th align="left" width="100">客户类型</th>
        <th width="150" align="right">金额合计(*普票)</th>
    </tr>
     @if($rows)
     @foreach($rows as $row)
        <tr>
            <td align="center">{{$row['username']}}</td>
            <td align="left">{{$row['nickname']}}</td>
            <td align="center">{{$customer_type[$row['post']]['title']}}</td>
            <td align="right">{{$row['money_sum']}}</td>
        </tr>
     @endforeach
     @endif
</table>
</div>
