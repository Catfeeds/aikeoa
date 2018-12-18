<div class="panel">

<table class="table">
    <tr>
        <td align="left">
        	<div class="pull-right">
        		<span class="help-inline"><i class="icon icon-info-sign"></i> 由于系统会缓存24小时数据，所以当前数据可能不是最新的数据。</span>
        	</div>
            <form id="query" name="query" action="{{url()}}" method="get">
                年份
                <select class="form-control input-inline input-sm" id='year' name='year' data-toggle="redirect" rel="{{$query['url']}}">
                    <option value="2018" @if($now_year == '2018') selected @endif >2018</option>
                    <option value="2017" @if($now_year == '2017') selected @endif >2017</option>
                    <option value="2016" @if($now_year == '2016') selected @endif >2016</option>
                    <option value="2015" @if($now_year == '2015') selected @endif >2015</option>
                    <option value="2014" @if($now_year == '2014') selected @endif >2014</option>
                </select>
                <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
        </td>
    </tr>
</table>

<div class="table-responsive">
<table class="table table-hover m-b-none">
<tr>
    <th align="center" width="30">部门</th>
     @if($months) @foreach($months as $v)
        <th align="center" width="40" nowrap="true">{{$v}}月</th>
     @endforeach @endif
</tr>

 @if($month_data) @foreach($month_data as $k => $value)
<tr>
    <td align="center"> @if($k==0) 课部管理 @else {{get_department($k,'name')}} @endif </td>
         @if($value) @foreach($value as $v)
            <td align="center">{{number_format($v, 2)}}</td>
         @endforeach @endif
</tr>
 @endforeach @endif
</table>
</div>

</div>