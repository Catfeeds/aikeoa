<div class="panel">

    <div class="wrapper b-b b-light">
        @if(Auth::user()->role->name != 'customer')
        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            @include('data/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
        @endif
    </div>

<div class="table-responsive">

<table class="table table-bordered">

<tr>

<th style="white-space:nowrap">
客户圈
</th>

@if($categorys)
    @foreach($categorys as $k => $v)
        @if($v['parent_id'] == 0)
            <th nowrap="true">{{$v['name']}}</th>
        @endif
    @endforeach
@endif

<th>总销售额</th>
<th>促销费比</th>
<th>消费促销</th>
<th>渠道促销</th>
<th>经销促销</th>
</tr>

@if($now_year_single['money'])
@foreach($now_year_single['money'] as $key => $value)
<tr>
<td rowspan="3" align="left" style="white-space:nowrap;vertical-align:middle;">
    {{$circles[$key]}}
	<a href="{{url('citydata')}}?circle_id={{$key}}&year={{$now_year}}">
    [查月销]
	</a>
</td>

@if($categorys)
@foreach($categorys as $k => $v)
    @if($v['parent_id'] == 0)
	   <td align="right">
	       {{(int)$value[$k]}}
	   </td>
    @endif
@endforeach
@endif

<td style="vertical-align:middle;" align="right">{{$now_year_single['totalcost'][$key]}}</td>

<td rowspan="3" style="vertical-align:middle;" align="right">
 @if($promotions['all'][$key])
	{{:$c = ($promotions['all'][$key]/$now_year_single['totalcost'][$key])}}
	{{number_format($c*100, 2)}}%
 @else
	0.00%
 @endif
</td>

{{:$ps = $promotions[$key]}}
<td rowspan="3" style="vertical-align:middle;" align="right">
    {{$ps[1] > 0 ? $ps[1] : 0}} <a href="{{url('promotion')}}?tag={{$tag}}&id={{$key}}&category=1">[查]</a>
</td>
<td rowspan="3" style="vertical-align:middle;" align="right">
    {{$ps[2] > 0 ? $ps[2] : 0}} <a href="{{url('promotion')}}?tag={{$tag}}&id={{$key}}&category=2">[查]</a>
</td>
<td rowspan="3" style="vertical-align:middle;" align="right">
    {{$ps[3] > 0 ? $ps[3] : 0}} <a href="{{url('promotion')}}?tag={{$tag}}&id={{$key}}&category=3">[查]</a>
</td>
</tr>

<tr>

@if($categorys)
    @foreach($categorys as $category_id => $category)
        @if($category['parent_id'] == 0)
        <td align="right" title="去年同期增长率" nowrap="true">
            <?php $scale = $oldscale[$key][$category_id]; ?>
            @if($scale == 0)
              去年无
            @elseif($scale > 0)
              <span style="color:green">{{number_format($scale * 100, 2)}}%</span>
            @else
              <span style="color:red">{{number_format($scale * 100, 2)}}%</span>
            @endif
        </td>
        @endif
    @endforeach
@endif

<?php 
    $_year = $now_year_single['totalcost'][$key] - $old_year_single['totalcost'][$key];
    if ($old_year_single['totalcost'][$key]) {
        $_total = number_format($_year / $old_year_single['totalcost'][$key] * 100, 2);
    } else {
        $_total = 0;
    }
?>
<td style="vertical-align:middle;" align="right">
    @if($_total > 0)
        {{$_total}}%
    @else
        <span style="color:red">{{$_total}}%</span>
    @endif
</td>

</tr>

<tr>

	@if($categorys)
    @foreach($categorys as $k => $v)
        @if($v['parent_id'] == 0)
    		<td align="right" title="占区域该品类百分比">
            @if($value[$v['id']] > 0)
    		  {{(int)number_format(($value[$v['id']] / $now_year_single[cat][$v['id']]) * 100, 2)}}%
            @endif
    		</td>
        @endif
    @endforeach
    @endif

    <td style="vertical-align:middle;" align="right"></td>
    
</tr>

@endforeach
@endif

</table>
</div>

</div>