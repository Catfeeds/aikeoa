<div class="panel">

    <div class="wrapper">
        @include('transport-settlement/query')
	</div>
	
    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table b-t m-b-none table-hover">
	    	<thead>
			<tr>
				<th align="center">
                    <input class="select-all" type="checkbox">
		        </th>
			    <th align="center">单号</th>
                <th align="left">物流供应商</th>
                <th align="center">创建时间</th>
                <th align="center">创建人</th>
                <th align="center">ID</th>
			</tr>
			</thead>
			<tbody>
			 @if($rows)
             @foreach($rows as $row)
			    <tr>
			    	<td align="center">
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}">
                    </td>
			        <td align="center"><a href="{{url('show',['id'=>$row['id']])}}">{{$row['sn']}}</a></td>
                    <td align="left">{{$row['logistics_name']}}</td>
                    <td align="center">@datetime($row['created_at'])</td>
                    <td align="center">{{get_user($row['created_by'], 'nickname')}}</td>
                    <td align="center">{{$row['id']}}</td>
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