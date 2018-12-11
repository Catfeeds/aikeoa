 @if(Auth::user()->role->name != 'customer')
	
	 @if(Auth::user()->role->name != 'salesman')
	    <select id='salesman_id' name='salesman_id' data-toggle="redirect" @if(Auth::user()->role->name == 'salesman')  disabled="true"  @endif  rel="{{$query}}">
	      <option value="0">区域</option>
	       @if($selects['salesman']) @foreach($selects['salesman'] as $k => $v)
	        <option value="{{$v['id']}}" @if($selects['select']['salesman_id']==$v['id']) selected="true" @endif >{{$v['nickname']}}</option>
	       @endforeach @endif
	    </select>
	    &nbsp;
     @endif

    <select id='province_id' name='province_id' data-toggle="redirect" rel="{{$query}}">
      <option value="0">省份</option>
       @if($selects['province']) @foreach($selects['province'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['province_id']==$v['id']) selected="true" @endif >{{$v['name']}}</option>
       @endforeach @endif
    </select>
    &nbsp;
    <select id='city_id' name='city_id' data-toggle="redirect" rel="{{$query}}">
      <option value="0">城市</option>
       @if($selects['city']) @foreach($selects['city'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['city_id']==$v['id']) selected="true" @endif >{{$v['name']}}</option>
       @endforeach @endif
    </select>
    &nbsp;
    <select id='customer_id' name='customer_id' data-toggle="redirect" rel="{{$query}}">
      <option value="0">客户</option>
       @if($selects['customer']) @foreach($selects['customer'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['customer_id']==$v['id']) selected="true" @endif >{{$v['company_name']}}</option>
       @endforeach @endif
    </select>
 @endif

 @if(isset($selects['select']['type']))
    &nbsp;
	<select id='type' name='type' data-toggle="redirect" rel="{{$query}}">
		<option value="0">订单类型</option>
		 @if($order_type) @foreach($order_type as $k => $v)
				<option value="{{$k}}" @if($selects['select']['type']==$k) selected="true" @endif >{{$v['layer_space']}}{{$v['title']}}</option>
		 @endforeach @endif
	</select>
 @endif

 @if(isset($selects['select']['sdate']))
	&nbsp;发货日期:
	<input type="text" name="sdate" data-toggle="date" size="13" id="sdate" value="{{$selects['select']['sdate']}}" readonly />
	-
	<input type="text" name="edate" data-toggle="date" size="13" id="edate" value="{{$selects['select']['edate']}}" readonly />
 @endif
