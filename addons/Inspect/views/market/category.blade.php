<form method="post" action="{{url()}}" id="sort" name="sort">

<table class="list tab table-hover">
  <thead>
    <tr class="odd x-line">
    <th align="center">排序</th>
    <th align="center">编号</th>
    <th align="left">名称</th>
    <th align="left">描述</th>
    <th></th>
	</tr>
  </thead>
  <tbody>
   @if($rows) @foreach($rows as $v)
  <tr class="x-line">
  <td align="center">
      <input type="text" class="form-control input-sort" name="sort[{{$v['id']}}]" value="{{$v['sort']}}">
  </td>
  <td align="center">{{$v['id']}}</td>
  <td align="left">{{$v['title']}}</td>
  <td align="left">{{$v['remark']}}</td>
  <td align="center">
    <a class="option" href="{{url('category_add')}}?id={{$v['id']}}"> 编辑 </a>
    <a class="option" onclick="app.confirm('{{url('category_delete',['id'=>$v['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
  </td>
  </tr>
   @endforeach @endif
   </tbody>
</table>

<button type="submit" class="btn btn-primary btn-sm"><i class="icon icon-sort-by-order"></i> 排序</button>
