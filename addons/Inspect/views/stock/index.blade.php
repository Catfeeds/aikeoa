@include('layouts/map')

<div class="panel">

    <div class="wrapper">
        @include('stock/query')
    </div>

    <form method="post" id="myform" name="myform">

    <div class="table-responsive">

        <table class="table m-b-none table-hover">
            <tr>
                <th align="center">序号</th>
                <th align="center">编号</th>
                <th align="center">区域</th>
                <th align="left">客户名称</th>
                <th align="center">创建者</th>
                <th align="center">创建时间</th>
                <th align="center"></th>
            </tr>
            @if($rows)
            @foreach($rows as $n => $v)
            <tr>
            <td align="center">{{$n+1}}</td>
            <td align="center">{{$v['id']}}</td>
            <td align="center">{{get_user($v['add_user_id'], 'nickname')}}</td>
            <td align="left">{{$v['company_name']}}</td>
            <td align="center">{{$v['nickname']}}</td>
            <td align="center">@datetime($v['add_time'])</td>
            <td align="center">
              <a class="option" href="#view_{{$v['id']}}" onclick='viewBox("","详细信息","{{url('view',['id'=>$v['id']])}}","lg");'>查看</a>
              @if(isset($access['print']))
                <a class="option" target="_blank" href="{{url('print',['id'=>$v['id']])}}">报告</a>
                @endif
              <a class="option" onclick="app.confirm('{{url('delete',['id'=>$v['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
            </td>
            </tr>
            @endforeach
            @endif
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