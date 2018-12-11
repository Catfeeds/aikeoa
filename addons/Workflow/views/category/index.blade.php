<div class="panel">

    <div class="wrapper">
        @if(isset($access['add']))
            <a href="{{url('add')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif

        @if(isset($access['delete']))
        <!--
        <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger">删除</button>
        -->
        @endif
    </div>

    <form method="post" action="{{url()}}" id="myform" name="myform">
        <table class="table m-b-none b-t table-thead-fixed">
            <tr>
                <th align="left">名称</th>
                <th align="left">描述</th>
                <th align="center">排序</th>
                <th align="center">编号</th>
                <th></th>
        	</tr>
            @if($rows)
            @foreach($rows as $v)
            <tr>
                <td align="left">{{$v['title']}}</td>
                <td align="left">{{$v['remark']}}</td>
                <td align="center">
                    <input type="text" class="form-control input-sort" name="sort[{{$v['id']}}]" value="{{$v['sort']}}">
                </td>
                <td align="center">{{$v['id']}}</td>
                <td align="center">
                    <a class="option" href="{{url('add',['id'=>$v['id']])}}">编辑</a>
                    <a class="option" href="javascript:app.confirm('{{url('delete',['id'=>$v['id']])}}','确定要删除吗？');">删除</a>
                </td>
            </tr>
            @endforeach 
            @endif
        </table>

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-1">
                    <button type="button" onclick="optionSort('#myform','{{url('index')}}');" class="btn btn-sm btn-default"> 排序</button>
                </div>
                <div class="col-sm-11 text-right text-center-xs"></div>
            </div>
        </div>
    </form>
</div>
