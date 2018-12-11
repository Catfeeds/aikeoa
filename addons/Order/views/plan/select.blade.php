<select class="form-control input-inline input-sm" id="warehouse_id" name="warehouse_id" data-toggle="redirect" rel="{{$query}}">
	<option value="0">全部仓库</option>
	@if($warehouses)
    @foreach($warehouses as $k => $v)
		<option value="{{$v['id']}}" @if($selects['select']['warehouse_id']==$v['id']) selected @endif >{{$v['layer_space']}}{{$v['title']}}</option>
	@endforeach 
    @endif
</select>

<select class="form-control input-inline input-sm" id='category_id' name='category_id' data-toggle="redirect" rel="{{$query}}">
	<option value="0">产品类别</option>
	 @if($categorys) @foreach($categorys as $k => $v)
		<option value="{{$v['id']}}" @if($selects['select']['category_id']==$v['id']) selected="true" @endif >{{$v['layer_space']}}{{$v['name']}}</option>
	 @endforeach @endif
</select>

@if(isset($selects['select']['sdate']))
&nbsp;日期:
<input type="text" name="sdate" class="form-control input-inline input-sm" data-toggle="date" size="13" id="sdate" value="{{$selects['select']['sdate']}}" readonly>
-
<input type="text" name="edate" class="form-control input-inline input-sm" data-toggle="date" size="13" id="edate" value="{{$selects['select']['edate']}}" readonly>
@endif
<input name="tpl" type="hidden" value="{{$selects['select']['tpl']}}">
