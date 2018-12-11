<div class="panel">

    <div class="wrapper">

        <div class="pull-right">
            <a class="btn btn-sm btn-default" href="javascript:optionSort('#myform','{{url('index')}}');"><i class="icon icon-sort-by-order"></i> 排序</a>
            @if(isset($access['delete']))
                <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger"><i class="icon icon-trash"></i> 删除</button>
            @endif
        </div>

        @if($parent)
            <a href="{{url('index', ['parent_id'=>$parent['parent_id']])}}" class="btn btn-sm btn-default"><i class="icon icon-menu-left"></i> {{$parent['name']}}</a>
        @endif

        @if(isset($access['create']))
            <a href="{{url('create', ['parent_id'=>$parent_id])}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif

    </div>

    <form method="post" id="myform" name="myform">

    <input type="hidden" name="parent_id" value="{{$parent['id']}}">

    <div class="table-responsive">
        <table class="table m-b-none table-hover b-t">
            <thead>
            <tr>
                <th align="center" width="40">
                    <input class="select-all" type="checkbox">
                </th>
                <th align="left" width="200">名称</th>
                <th align="left">值</th>
                <th align="center" width="80">排序</th>
                <th align="center" width="80">编号</th>
                <th align="center" width="200"></th>
            </tr>
            </thead>
            <tbody>
             @if($rows)
             @foreach($rows as $row)
                <tr>
                    <td align="center">
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row['id']}}">
                    </td>
                    <td align="left">{{$row['name']}}</td>
                    <td align="left">{{$row['value']}}</td>
                    <td align="center">
                        <input type="text" name="sort[{{$row['id']}}]" class="form-control input-sort" value="{{$row['sort']}}">
                    </td>
                    <td align="center">{{$row['id']}}</td>
                    <td align="center">
                        <a class="option" href="{{url('index',['parent_id'=>$row['id']])}}">子选项</a>
                        @if(isset($access['create']))
                          <a class="option" href="{{url('create',['id'=>$row['id'], 'parent_id'=>$row['parent_id']])}}">编辑</a>
                        @endif
                    </td>
                </tr>
             @endforeach
             @endif
            </tbody>
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