<div class="panel">

    <div class="wrapper">
        @if(isset($access['create']))
        	<a href="{{url('create')}}" class="btn btn-info btn-sm"><i class="icon icon-plus"></i> 新建</a>
    	@endif
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
		<table class="table table table-hover m-b-none b-t table-hover">
			<thead>
			<tr>
				<th align="left">名称</th>
				<th align="center">类别负责人</th>
				<th align="left">描述</th>
				<th align="center">状态</th>
				<th align="center">ID</th>
				<th align="center"></th>
			</tr>
			</thead>
			@if($rows) @foreach($rows as $row)
			<tr>
				<td align="left">{{$row['name']}}</td>
				<td align="center">{{get_user($row['user_id'], 'nickname')}}</td>
				<td align="left">{{$row['description']}}</td>
				<td align="center"> @if($row['status']==1) <span class="green">正常</span> @else <span class="red">禁用</span> @endif </td>
				<td align="center">{{$row['id']}}</td>
				<td align="center">
					@if(isset($access['edit']))
						<a class="option" href="{{url('create',['id'=>$row['id']])}}"> 编辑 </a>
					@endif
					@if(isset($access['delete']))
						<a class="option" onclick="app.confirm('{{url('delete',['id'=>$row['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
					@endif
				</td>
			</tr>
			@endforeach @endif
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