<div class="panel">

    <div class="wrapper">

        <div class="pull-right">
            <a class="btn btn-sm btn-default" href="javascript:optionSort('#myform','{{url('index')}}');"><i class="icon icon-sort-by-order"></i> 排序</a>
            @if(isset($access['delete']))
                <a class="btn btn-sm btn-danger" href="javascript:optionDelete('#myform','{{url('delete')}}');"><i class="icon icon-trash"></i> 删除</a>
            @endif
        </div>

        @if(isset($access['create']))
            <a href="{{url('create')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif

    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table m-b-none b-t table-hover">
            <thead>
            <tr>
                <th style="width:20px;">
                    <input class="select-all" type="checkbox">
                </th>
                <th align="left" width="200">名称</th>
                <th align="left">链接</th>
                <th align="left">权限</th>
                <th align="center" width="80">排序</th>
                <th align="center" width="60">ID</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
             @if($rows)
             @foreach($rows as $row)
                <tr>
                    <td>
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row['id']}}">
                    </td>
                    <td align="left">{{$row['name']}}</td>
                    <td align="left">{{$row['path']}}</td>
                    <td align="left">{{$row['receive_name']}}</td>
                    <td align="center">
                        <input type="text" name="sort[{{$row['id']}}]" class="form-control input-sort" value="{{$row['sort']}}">
                    </td>
                    <td align="center">{{$row['id']}}</td>
                    <td align="center">
                       @if(isset($access['create']))
                          <a class="option" href="{{url('create',['id'=>$row['id']])}}">编辑</a>
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