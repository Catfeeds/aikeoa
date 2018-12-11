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

        <!--
        <div class="pull-right">
            @if(isset($access['create']))
                <a href="{{url('create')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
            @endif
        </div>

        <div class="input-group">
            <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown">
                批量操作
                <span class="caret"></span>
            </button>

            <ul class="dropdown-menu text-xs">
                <li><a href="javascript:optionSort('#myform','{{url('index')}}');"><i class="icon icon-sort-by-order"></i> 排序</a></li>
                <li class="divider"></li>
                @if(isset($access['delete']))
                <li><a href="javascript:optionDelete('#myform','{{url('delete')}}');"><i class="icon icon-trash"></i> 删除</a></li>
                @endif
            </ul>
        </div>
        -->

    </div>

    <form method="post" id="myform" name="myform">

    <input type="hidden" name="parent_id" value="{{$parent['id']}}">

    <div class="table-responsive">
        <table class="table m-b-none b-t table-hover">
            <thead>
            <tr>
                <th align="center" width="40">
                    <input class="select-all" type="checkbox">
                </th>
                <th align="left">名称</th>
                <th align="left">URL</th>
                <th align="center">验证</th>
                <th align="center">排序</th>
                <th align="center">编号</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
             @if($rows)
             @foreach($rows as $row)
                <tr>
                    <td align="center">
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row['id']}}">
                    </td>
                    <td align="left">
                    
                        {{str_repeat('<span class="level"></span>', $row['layer_level'])}}

                        @if($row['icon'])
                            <i class="fa fa-fw {{$row['icon']}}"></i>
                        @else
                            @if(sizeof($row['child']) == 1)
                                <i class="fa fa-fw fa-file-o"></i>
                            @else
                                <i class="fa fa-fw fa-folder-o"></i>
                            @endif
                        @endif

                        {{$row['name']}}

                    </td>
                    <td align="left">{{$row['url']}}</td>
                    <td align="center">@if($row['access'] == 1) 是 @else 否 @endif</td>
                    <td align="center">
                        <input type="text" name="sort[{{$row['id']}}]" class="form-control input-sort" value="{{$row['sort']}}">
                    </td>
                    <td align="center">{{$row['id']}}</td>
                    <td align="center">
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
            </div>
        </div>
    </footer>
</div>