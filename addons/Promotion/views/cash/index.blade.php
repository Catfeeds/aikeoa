<div class="panel">

    <div class="wrapper">
        @include('cash/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table m-b-none">
        <tr>
            <th align="center" width="40">
                <input class="select-all" type="checkbox">
            </th>
            <th align="left" width="200">促销编号</th>
            <th align="left">客户名称</th>
            <th align="center">兑现日期</th>
            <th align="right">兑现金额</th>
            <th align="center" width="200"></th>
        </tr>

        @foreach($rows as $row)
        <tr>
            <td align="center">
                <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}">
            </td>
            <td align="left">{{$row->promotion->number}}</td>
            <td align="left">{{$row->nickname}}</td>
            <td align="center">@date($row->date)</td>
            <td align="right">{{$row->money}}</td>
            <td align="center">
                <a class="option" href="javascript:viewBox('show','查看','{{url('show', ['id'=>$row->id])}}');">查看</a>
                <button type="button" class="option" data-toggle="dialog-form" data-title="编辑" data-url="{{url('create', ['id'=>$row->id])}}" data-id="window-form">编辑</button>
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
