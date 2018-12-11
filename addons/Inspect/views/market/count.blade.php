<div class="panel">

    <div class="wrapper">
        @include('market/query')
    </div>

    <form method="post" id="myform" name="myform">

    <div class="table-responsive">

        <table class="table m-b-none table-hover">
            <tr>
                <th align="center">序号</th>
                <th align="left">区域</th>
                <th align="right">客户数量</th>
                <th align="right">巡店数量</th>
            </tr>
            <?php $n = 1; $customer_count = 0; $count = 0; ?>
            @if($rows)
            @foreach($rows as $v)
            <tr>
            <td align="center">{{$n}}</td>
            <td align="left">{{get_user($v['region_id'], 'nickname')}}</td>
            <td align="right">{{array_sum($v['customer_count'])}}</td>
            <td align="right">{{$v['count']}}</td>
            </tr>
            <?php $n++; $count = $count + $v['count']; $customer_count = $customer_count + array_sum($v['customer_count']); ?>
            @endforeach
            @endif
            <tr>
                <th align="center">合计</th>
                <th align="right"></th>
                <th align="right">{{$customer_count}}</th>
                <th align="right">{{$count}}</th>
            </tr>
        </table>

    </div>
    </form>

</div>