<div class="panel">

    <div class="wrapper">
        @include('contract/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table m-b-none">
        <tr>
            <th align="center" width="80">客户代码</th>
            <th align="left">客户名称</th>
            <th align="left">客户地区</th>
            <th align="center" width="120">有效日期</th>
            <th align="center" width="140"></th>
        </tr>

        @foreach($rows as $row)
        <tr>
            <td align="center">{{get_user($row->customer_id, 'username')}}</td>
            <td align="left">{{get_user($row->customer_id, 'nickname')}}</td>
            <td align="left">
                {{$region[$row->user->province_id]}}
                {{$region[$row->user->city_id]}}
                {{$region[$row->user->county_id]}}
            </td>
            <td align="center">@date($row->end_time)</td>
            <td align="center">
                <a class="option" href="{{url('view', ['id'=>$row->id])}}">查看</a>
                <a class="option" href="{{url('add', ['id'=>$row->id])}}">编辑</a>
                <a class="option" onclick="app.confirm('{{url('delete', ['id'=>$row->id])}}','确定要删除吗？');" href="javascript:;">删除</a>
            </td>
        </tr>
        @endforeach
        </table>
    </div>
    </form>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-1 hidden-xs">
            </div>
            <div class="col-sm-11 text-right text-center-xs">
                {{$rows->render()}}
            </div>
        </div>
    </div>

</div>
