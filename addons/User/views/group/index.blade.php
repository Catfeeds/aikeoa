<div class="panel">

    <div class="wrapper">

        <div class="pull-right">
            <a class="btn btn-sm btn-default" href="javascript:optionSort('#myform','{{url('index')}}');"><i class="icon icon-sort-by-order"></i> 排序</a>
            @if(isset($access['delete']))
                <a class="btn btn-sm btn-danger" href="javascript:optionDelete('#myform','{{url('delete')}}');"><i class="icon icon-remove"></i> 删除</a>
            @endif
        </div>

        @if(isset($access['add']))
            <a href="{{url('add')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif

    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
    <table class="table m-b-none b-t table-hover">
        <thead>
            <tr>
                <th>
                    <input class="select-all" type="checkbox">
                </th>
                <th align="left">名称</th>
                <th align="center">排序</th>
                <th align="center">ID</th>
                <th align="center"></th>
            </tr>
        </thead>
        @if($rows)
        @foreach($rows as $row)
        <tr>
            <td align="center">
                <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"@if($row->system == 1) disabled @endif>
            </td>
            <td align="left">{{$row['name']}}</td>
            <td align="center">
                <input type="text" name="sort[{{$row['id']}}]" class="form-control input-sort" value="{{$row['sort']}}">
            </td>
            <td align="center">{{$row['id']}}</td>
            <td align="center">
                <a class="option" href="{{url('add',['id'=>$row['id']])}}">编辑</a>
            </td>
        </tr>
        @endforeach
        @endif
    </table>
    </div>
    </form>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-11 text-right text-center-xs">
            </div>
        </div>
    </div>
</div>