<form method="post" action="{{url()}}" id="sort" name="sort">

<table class="list tab">
    <tr class="odd">
    <th align="center" width="40">排序</th>
    <th align="center" width="40">编号</th>
    <th align="center" width="200">名称</th>
    <th>描述</th>
    <th width="80">操作</th>
	</tr>
   @if($rows) 
   @foreach($rows as $v)
  <tr>
  <td align="center">
      <input type="text" class="input-text" size="3" name="id[{{$v['id']}}]" value="{{$v['sort']}}">
  </td>
  <td align="center">{{$v['id']}}</td>
  <td align="left">{{$v['title']}}</td>
  <td align="left">{{$v['remark']}}</td>
  <td align="center">
    <a class="option" href="{{url('category_add')}}?id={{$v['id']}}"> 编辑 </a>
    <a class="option" href="javascript:app.confirm('{{url('category_delete')}}?id={{$v['id']}}','确定要删除吗？');"> 删除 </a>
  </td>
  </tr>
   @endforeach 
   @endif
</table>

<button type="submit" class="btn btn-primary btn-sm"><i class="icon icon-sort-by-order"></i> 排序</button>
