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
        <th align="center" width="60">序号</th>
        <th align="left" width="100">客户代码</th>
        <th align="left">客户名称</th>
        <th align="center" width="100">客户类型</th>
        <th align="right" width="200">去年金额</th>
        <th align="right" width="200">今年金额</th>
    </tr>

    <?php $n = 1; ?>
    @if($rows)
    @foreach($rows as $row)
        <tr>
            <td align="center">{{$n}}</td>
            <td align="center">{{$row['username']}}</td>
            <td align="left">{{$row['nickname']}}</td>
            <td align="center">{{$customer_type[$row['post']]['title']}}</td>
            <td align="right">{{$data[$year1][$row['id']]}}</td>
            <td align="right">{{$data[$year2][$row['id']]}}</td>
        </tr>
        <?php $n++; ?>
    @endforeach
    @endif
    </table>
</div>