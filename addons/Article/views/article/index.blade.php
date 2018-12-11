<!--
<ul class="breadcrumb">
	<li><i class="fa fa-home"></i> 资讯</li>
	<li class="active"><a href="javascript:;">企业公告</a></li>
	<li class="active">Components</li>
</ul>
-->
<div class="panel">

	@include('tabs')

    <div class="wrapper">
        @include('article/query')
	</div>
	
    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table b-t m-b-none table-hover">
	    	<thead>
			<tr>
				<th align="center">
                    <input class="select-all" type="checkbox">
		        </th>
			    <th align="left">标题</th>
			    <th align="center">状态</th>
			    <th align="center">类别</th>
			    <th align="center">发布人</th>
			    <th align="center">{{url_order($search,'created_at','发布时间')}}</th>
				<th align="center">{{url_order($search,'id','ID')}}</th>
			    <th align="center"></th>
			</tr>
			</thead>
			<tbody>
			 @if($rows)
             @foreach($rows as $row)
			    <tr>
			    	<td align="center">
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}">
                    </td>
			        
			        <td align="left"><a href="{{url('view',['id'=>$row->id])}}">{{$row->title}}</a></td>
			        <td align="center">
			           @if($row->expired_at > 0 && $row->expired_at < time())
			            <span class="label label-danger">过期</span>
			           @else
			            <span class="label label-success">正常</span>
			           @endif </td>
			        <td align="center">{{option('article.category', $row->category_id)}}</td>
			        <td align="center">{{get_user($row->created_by, 'nickname')}}</td>
			        <td align="center">@datetime($row->created_at)</td>
					<td align="center">{{$row->id}}</a></td>
			        <td align="center">
			           @if(isset($access['create']))
			              <a class="option" href="{{url('create',['id'=>$row->id])}}">编辑</a>
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