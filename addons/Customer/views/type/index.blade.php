<div class="panel">

    <div class="wrapper">
        @include('type/query')
    </div>

<form method="post" action="{{url()}}" id="myform" name="myform">

<table class="table m-b-none">
    <tr>
    <th align="left">名称</th>
    <th align="left">备注</th>
    <th align="center">排序</th>
    <th align="center">ID</th>
    <th></th>
	</tr>
  @foreach($rows as $row)
  <tr>
  <td align="left">{{$row['name']}}</td>
  <td align="left">{{$row['remark']}}</td>
  <td align="center">
    <input type="text" class="form-control input-sort" name="sort[{{$row['id']}}]" value="{{$row['sort']}}" />
</td>
<td align="center">{{$row['id']}}</td>
  <td align="center">
    <a class="option" href="{{url('add', ['id' => $row['id']])}}"> 编辑 </a>
    <a class="option" onclick="app.confirm('{{url('delete', ['id' => $row['id']])}}','确定要删除吗？');" href="javascript:;"> 删除 </a>
  </td>
  </tr>
  @endforeach
</table>

<div class="panel-footer">
    <div class="row">
        <div class="col-sm-1 hidden-xs">
            <button type="submit" class="btn btn-default btn-sm"><i class="icon icon-sort-by-order"></i> 排序</button>
        </div>
        <div class="col-sm-11 text-right text-center-xs">
        </div>
    </div>
</div>

</div>