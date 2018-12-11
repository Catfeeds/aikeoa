<div class="panel">

    <div class="wrapper">
        @include('account/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table m-b-none table-hover">
        <tr>
            <th align="center">
                <label class="i-checks i-checks-sm m-b-none">
                    <input class="select-all" type="checkbox"><i></i>
                </label>
            </th>
            <th align="center">单据日期</th>
            <th align="left">单据编号</th>
            <th align="center">开始日期</th>
            <th align="center">结束日期</th>
            <th align="center">客户代码</th>
            <th align="left">客户名称</th>
            <th align="right">余额</th>
            <th align="center">状态</th>
            <th align="left">备注</th>
            <th align="center">创建者</th>
            <th align="center" width="100"></th>
        </tr>

        @foreach($rows as $row)

        <tr>
            <td align="center">
                <label class="i-checks i-checks-sm m-b-none">
                    <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"><i></i>
                </label>
            </td>
            <td align="center">@datetime($row->created_at)</td>
            <td align="left">{{$row->sn}}</td>
            <td align="center">{{$row->start_at}}</td>
            <td align="center">{{$row->end_at}}</td>
            <td align="center">{{$row->customer->user->username}}</td>
            <td align="left">{{$row->customer->user->nickname}}</td>
            <td align="right">{{$row->balance}}</td>
            <td align="center">
                @if($row['status'] == 1)
                    已审核
                @else
                    <span class="text-danger">等待客户审核</span>
                @endif
            </td>
            <td align="left"></td>
            <td align="center">{{get_user($row->created_by, 'nickname')}}</td>
            <td align="center">
                <a class="option" href="javascript:viewBox('show','查看','{{url('show', ['id'=>$row->id])}}');">查看</a>
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
