@include('layouts/map')

<div class="panel">

    <div class="wrapper">
        <form id="myform" name="myform" action="{{url()}}" method="get">
            @include('select')
            <button type="submit" class="btn btn-default btn-sm">搜索</button>
        </form>
    </div>

    <form method="post" id="myform" name="myform">

    <div class="table-responsive">

        <table class="table m-b-none table-hover">
            <tr>
                <th align="center">提交人</th>
                <th align="center">类别</th>
                <th align="left">主题说明</th>
                <th align="left">现场说明</th>
                <th align="center">共享人</th>
                <th align="center">时间</th>
                <th align="center" width="40">ID</th>
                <th align="center" width="100"></th>
            </tr>
            @if($rows) 
            @foreach($rows as $v)
            <tr>
            <td align="center">{{$v['nickname']}}</td>
            <td align="center">{{$v['category_name']}}</td>
            <td align="left">{{$v['title']}}</td>
            <td align="left">{{$v['remark']}}</td>
            <td align="center">{{Dialog::text('user',$v['share_user'])}}</td>
            <td align="center">{{$v['add_time'] > 0 ? date("Y-m-d H:i:s",$v['add_time']) : ""}}</td>
            <td align="center">{{$v['id']}}</td>
            <td align="center">
                <a class="option" href="javascript:;" onclick='viewBox("location","详细信息","{{url('view',['id'=>$v['id']])}}");'>查看</a>
                <a class="option" href="javascript:app.confirm('{{url('delete',['id'=>$v['id']])}}','确定要删除吗？');">删除</a>
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