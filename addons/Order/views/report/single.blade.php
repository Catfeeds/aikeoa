<div class="panel">

    <div class="wrapper">
        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            @if(Auth::user()->role->name != 'customer')
                @include('data/select')
                &nbsp;
            @endif
            <select class="form-control input-sm" id='category_id' name='category_id' data-toggle="redirect" rel="{{$query}}">
                @if($categorys)
                @foreach($categorys as $k => $v)
                    @if($v['parent_id'] == 0)
                    <option value="{{$v['id']}}" @if($select['select']['category_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                    @endif
                @endforeach
                @endif
            </select>
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
        
    </div>
</div>

{{:$months = range(1,12)}}
@if($single['year'])
@foreach($single['year'] as $year => $total)
	
	{{:arsort($total['money'])}}
    <div class="panel">
	<table class="table table-bordered">
		<tr>
			<th colspan="14">
                <h4>{{$year}}年 - 单品销售排名(按占比)分析</h4>
            </th>
		</tr>

		<tr>
		<th align="center">产品名称</th>
		@if($months)
        @foreach($months as $month)
			<th align="center">
				{{$month}}月
			</th>
		@endforeach
        @endif
		<th align="center">合计</th>
		</tr>

		@if($total['money'])
        @foreach($total['money'] as $key => $product)

			{{:$total = array_sum((array)$product)}}
			<tr>
			<td>
				{{$single['name'][$key]}} - {{$single['spec'][$key]}}
			</td>

			@if($months)
            @foreach($months as $month)
				<td align="right">
					<div title="金额">{{(int)$single['money'][$year][$key][$month]}}</div>
					<div title="件数">{{(int)$single['amount'][$year][$key][$month]}}</div>
					<div style="color:green;" title="该单品本月/本年的占比">
						 @if($single['money'][$year][$key][$month] > 0)
							{{number_format(($single['money'][$year][$key][$month]/$total)*100,2)}}%
						 @endif
					</div>
				</td>
			@endforeach 
            @endif
			<th align="right">
				<div title="金额">{{$total}}</div>
				<div title="件数">{{(int)array_sum($single['amount'][$year][$key])}}</div>
				<div style="color:red;" title="本年单品/品类的占比">
				 @if($total > 0)
					{{number_format(($total/$single['money2'][$year])*100,2)}}%
				 @endif
				</div>
			</th>
		</tr>
		@endforeach
        @endif
	</table>
    </div>
@endforeach
@endif