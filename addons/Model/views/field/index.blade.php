<div class="panel">

    @include('query')

    <form method="post" id="myform" name="myform">

        <table class="table m-b-none table-hover b-t" id="table-sortable" url="{{url()}}">
        <thead>
            <tr>
                <th align="left">字段别名</th>
                <th align="left">字段名</th>
                <th align="left">字段类型</th>
                <th align="left">字段索引</th>
                <th align="left">表单类型</th>
                <th align="center">ID</th>
                <th align="center"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($master->fields as $row)
            <tr id="{{$row['id']}}">
                <td align="left" class="move">{{$row['name']}}</td>
                <td align="left">{{$row['field']}}</td>
                <td align="left">{{$row['type']}}({{$row['length']}})</td>
                <td align="left">{{$row['index']}}</td>
                <td align="left">{{$row['form_type']}}</td>
                <td align="center">{{$row['id']}}</td>
                <td align="center">
                    <a class="option" href="{{url('create',['parent_id'=>$model_id,'model_id'=>$row['model_id'],'id'=>$row['id']])}}">编辑</a>
                    @if($row['system'] == 0)
                        <a class="option" href="javascript:app.confirm('{{url('delete',['id'=>$row['id'],'model_id'=>$model_id])}}','确定要删除吗？');">删除</a>
                    @endif
                </td>
            </tr>
            @endforeach

            @foreach($sublist as $rows)
            @foreach($rows->fields as $row)
            <tr id="{{$row['id']}}">
                <td align="left" class="move"><span class="label label-primary">{{$rows->name}}</span> {{$row['name']}}</td>
                <td align="left">{{$row['field']}}</td>
                <td align="left">{{$row['type']}}({{$row['length']}})</td>
                <td align="left">{{$row['index']}}</td>
                <td align="left">{{$row['form_type']}}</td>
                <td align="center">{{$row['id']}}</td>
                <td align="center">
                    <a class="option" href="{{url('create',['parent_id'=>$model_id,'model_id'=>$row['model_id'],'id'=>$row['id']])}}">编辑</a>
                    @if($row['system'] == 0)
                        <a class="option" href="javascript:app.confirm('{{url('delete',['model_id'=>$model_id,'id'=>$row['id']])}}','确定要删除吗？');">删除</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
        </table>

    </form>

</div>