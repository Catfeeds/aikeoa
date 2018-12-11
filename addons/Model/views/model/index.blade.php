<div class="panel">

        <div class="wrapper">
            <div class="pull-right">
                <a class="btn btn-sm btn-default" href="javascript:optionSort('#myform','{{url('index')}}');"><i class="icon icon-sort-by-order"></i> 排序</a>
            </div>
            <a class="btn btn-info btn-sm" href="{{url('create')}}"><i class="icon icon-plus"></i> 新建</a>
        </div>
    
        <form method="post" id="myform" name="myform">
        <div class="table-responsive">
        <table class="table m-b-none table-hover b-t">
        <thead>
            <tr>
                <th>
                    <input class="select-all" type="checkbox">
                </th>
                <th align="left">名称</th>
                <th align="left">表名</th>
                @if($parent_id)
                <th align="left">关联外键</th>
                @endif
                <th align="center">排序</th>
                <th align="center">ID</th>
                <th align="center"></th>
            </tr>
        </thead>
        @foreach($rows as $row)
        <tr>
            <td align="center">
                <input class="select-row" type="checkbox" name="id[]" value="{{$row['id']}}">
            </td>
            <td align="left">
                {{$row['name']}}
            </td>
            <td align="left">{{$row->table}}</td>
            @if($parent_id)
            <td align="left">{{$row->relation}}</td>
            @endif
            <td align="center">
                <input type="text" name="sort[{{$row['id']}}]" class="form-control input-sort" value="{{$row['sort']}}">
            </td>
            <td align="center">{{$row->id}}</td>
            <td align="center">
                <a class="option" href="{{url('field/index',['model_id'=>$row->id])}}"> 字段 </a>
                @if($row->parent_id == 0)
                    <a class="option" href="{{url('index',['parent_id'=>$row->id])}}"> 子表 </a>
                    <a class="option" href="{{url('template/index',['model_id'=>$row->id])}}"> 视图 </a>
                    @if($row->is_flow == 1)
                        <a class="option" href="{{url('step/index',['model_id'=>$row->id])}}"> 流程 </a>
                    @endif
                    <a class="option" href="{{url('permission/index',['model_id'=>$row->id])}}"> 权限 </a>
                @endif
                <a class="option" href="{{url('create',['id'=>$row->id])}}"> 编辑 </a>
                @if($row->system == 0)
                    <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row->id])}}','确定要删除吗？');" href="javascript:;">删除</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    </div>
    </form>
    
    <footer class="panel-footer">
            <div class="row">
                <div class="col-sm-1 hidden-xs">
                </div>
                <div class="col-sm-11 text-right text-center-xs">
                    {{$rows->render()}}
                </div>
            </div>
        </footer>
    
    </div>