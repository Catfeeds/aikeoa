<div class="panel">

<div class="wrapper">

    <div class="pull-right">
        @if(isset($access['add']))
            <a href="{{url('add', ['layer' => $layer])}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif
    </div>

    <div class="input-group">
        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown">
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu text-xs">
            <li><a href="javascript:optionSort('#myform','{{url()}}');"><i class="icon icon-sort-by-order"></i> 排序</a></li>
        </ul>
    </div>

</div>

<form method="post" action="{{url()}}" id="myform" name="myform">
<table class="table m-b-none table-hover">
    <tr>
    <th align="left">名称</th>
    @if($layer > 1)
        <th align="center">上级</th>
    @endif
    @if($layer == 3)
        <th align="center">审批人</th>
        <th align="center">查阅人</th>
    @endif
    <th>备注</th>
    <th align="center">排序</th>
    <th align="center">编号</th>
    <th></th>
	</tr>
   @if($rows)
   @foreach($rows as $row)
  <tr>
  <td align="left">
    {{$row['name']}}
  </td>
    @if($layer > 1)
        <td align="center">{{$row->parent->name}}</td>
    @endif
    @if($layer == 3)
        <td align="center">{{Dialog::text('user', $row['owner_user_id'])}}</td>
        <td align="center">{{Dialog::text('user', $row['owner_assist'])}}</td>
    @endif
  <td align="left">{{$row['description']}}</td>
  <td align="center">
    <input type="text" class="form-control input-sort" name="sort[{{$row['id']}}]" value="{{$row['sort']}}" />
  </td>
  <td align="center">{{$row['id']}}</td>
  <td align="center">
    <a class="option" href="{{url('add', ['layer' => $row['layer'], 'id' => $row['id']])}}"> 编辑 </a>
    <a class="option" href="javascript:app.confirm('{{url('delete',['id'=>$row['id']])}}','确定要删除吗？');">删除</a>
  </td>
  </tr>
  @endforeach 
  @endif
</table>

<footer class="panel-footer">
      <div class="row">
        <div class="col-sm-1 hidden-xs">
        </div>
        <div class="col-sm-11 text-right text-center-xs">
        </div>
      </div>
    </footer>
</div>

</form>