 <div class="panel">

     @include('query')

    <form method="post" action="{{url()}}" id="myform" name="myform">

    <table class="table table-hover m-b-none b-t" id="table-sortable" url="{{url()}}">
        <thead>
            <tr>
                <th align="left">视图名称</th>
                <th align="center">视图类型</th>
                <th align="center">客户端</th>
                <th align="center">权限范围</th>
                <th align="center">ID</th>
                <th align="center"></th>
            </tr>
        </thead>
        <tbody>
        @if($rows)
            @foreach($rows as $row)
            <tr id="{{$row['id']}}">
                <td align="left" class="move">{{$row['name']}}</td>
                <td align="center">{{$row['type']}}</td>
                <td align="center">{{$row['client']}}</td>
                <td align="center">{{$row['receive_name']}}</td>
                <td align="center">{{$row['id']}}</td>
                <td align="center">
                    <a class="option" href="{{url('create',['model_id'=>$row['model_id'],'id'=>$row['id']])}}">编辑</a>
                    @if($row['system'] == 0)
                    <a class="option" onclick="app.confirm('{{url('delete',['model_id'=>$row['model_id'],'id'=>$row['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    </form>
</div>