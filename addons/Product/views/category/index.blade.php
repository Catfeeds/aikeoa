<div class="panel">

<div class="wrapper">
    @if(isset($access['add']))
        <a href="{{url('add')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
    @endif
</div>

<form method="post" id="myform" name="myform">
<div class="table-responsive">
<table class="table m-b-none table-hover">
    <tr>
    <th align="left">名称</th>
    <th align="center">代码</th>
    <th align="center">状态</th>
    <th align="left">备注</th>
    <th align="center">排序</th>
    <th align="center">ID</th>
    <th></th>
	</tr>
  @if($rows)
  @foreach($rows as $row)
  <tr>
    <td align="left">{{$row->layer_html}}{{$row->name}}</td>
    <td align="center">{{$row->code}}</td>
    <td align="center">@if($row->status == 1) 启用 @else 停用 @endif</td>
    <td align="left">{{$row->title}}</td>
    <td align="center">
      <input type="text" class="form-control input-sort" name="id[{{$row->id}}]" value="{{$row->sort}}" />
    </td>
    <td align="center">{{$row->id}}</td>
    <td align="center">
      <a class="option" href="{{url('add', ['id'=>$row->id])}}"> 编辑 </a>
      <a class="option" href="javascript:app.confirm('{{url('delete',['id'=>$row->id])}}','确定要删除吗？');">删除</a>
    </td>
  </tr>
   @endforeach 
   @endif
</table>
</div>

</form>

    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-1 hidden-xs">
            <button type="button" onclick="optionSort('#myform','{{url('index')}}');" class="btn btn-primary btn-sm"><i class="icon icon-sort-by-order"></i> 排序</button>
        </div>
        <div class="col-sm-11 text-right text-center-xs">
        </div>
      </div>
    </footer>
</div>
