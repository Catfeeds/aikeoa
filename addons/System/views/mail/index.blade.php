<div class="panel">

    <div class="wrapper">
 
        <div class="pull-right">
            @if(isset($access['delete']))
                <a class="btn btn-sm btn-danger" href="javascript:optionDelete('#myform','{{url('delete')}}');"><i class="icon icon-trash"></i> 删除</a>
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
                <th style="width:20px;">
                    <input class="select-all" type="checkbox">
                </th>
                <th align="left">名称</th>
                <th align="left">邮箱帐号</th>
                <th align="left">SMTP服务器</th>
                <th align="left">服务器端口</th>
                <th align="center">状态</th>
                <th align="center">排序</th>
                <th align="center">编号</th>
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
                    <td align="left">{{$row['user']}}</td>
                    <td align="left">{{$row['smtp']}}</td>
                    <td align="left">{{$row['port']}}</td>
                    <td align="center">
                        @if($row['status'] == 1) 启用 @else 停用 @endif
                    </td>
                    <td align="center">
                        <input type="text" name="sort[{{$row['id']}}]" class="form-control input-sort" value="{{$row['sort']}}">
                    </td>
                    <td align="center">{{$row['id']}}</td>
                    <td align="center">
                       @if(isset($access['edit']))
                          <a class="option" href="{{url('edit',['id'=>$row['id']])}}">编辑</a>
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