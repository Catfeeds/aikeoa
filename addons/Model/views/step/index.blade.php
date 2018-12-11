 <div class="panel">

     @include('query')

    <form method="post" action="{{url()}}" id="myform" name="myform">

    <table class="table m-b-none b-t">
        <tr>
            <th align="right" width="10%">流程名称</th>
            <td align="left">{{$model->name}}</td>
            <th align="right" width="10%">表</th>
            <td align="left">{{$model->table}}</td>
        </tr>
    </table>
</div>

<div class="panel">
    <table class="table table-hover" id="table-sortable" url="{{url()}}">
        <tr>
            <th align="left">进程名称</th>
            <th align="left">转入进程</th>
            <th align="center">类型</th>
            <th align="center">颜色</th>
            <th align="center">ID</th>
            <th align="center"></th>
        </tr>
        @foreach($rows as $row)
        <tr id="{{$row->id}}">
            <td align="left" @if($row->sn > 0) class="move" @endif>
                @if($row->sn > 0)
                    <span class="badge">{{$row->sn}}</span>
                @endif
                {{$row->name}}
            </td>
            <td align="left">
                <?php $joins = explode(',', $row->join); ?>
                @foreach($joins as $id)
                    @if($rows[$id]['sn'] > 0)
                        <span class="badge">{{$rows[$id]['sn']}}</span> 
                    @endif
                    {{$rows[$id]['name']}}
                @endforeach
            </td>
            <td align="center">{{$row->type}}</td>
            <td align="center"><span class="label label-{{$row->color}}">{{$row->color}}</span></td>
            <td align="center">{{$row->id}}</td>
            <td align="center">
                @if($row->sn > 0)
                <a class="option" href="{{url('condition',['model_id'=>$model->id,'id'=>$row->id])}}">条件</a>
                <a class="option" href="{{url('create',['model_id'=>$model->id,'id'=>$row->id])}}">编辑</a>
                @endif
                <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row->id])}}','确定要删除吗？');" href="javascript:;">删除</a>
            </td>
        </tr>
        @endforeach
    </table>
    </form>
</div>