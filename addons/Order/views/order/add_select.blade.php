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

@if(isset($selects['select']['step_id']))
&nbsp;
<select id="step_id" name="step_id" data-toggle="redirect" rel="{{$query}}">
    <option value="0">流程</option>
    @if($audit_config)
    @foreach($audit_config as $k => $v)
    @if($k > 0 && $k < 5)
        <option value="{{$k}}" @if($selects['select']['step_id'] == $k) selected @endif>{{$k}}</option>
    @endif
    @endforeach @endif
</select>
@endif

 @if(isset($selects['select']['number']))
&nbsp;订单号:
<input type="text" class="input-text" name="number" id="number" size="10" value="{{$selects['select']['number']}}">
 @endif

 @if(isset($selects['select']['sdate']))
&nbsp;日期:
<input type="text" name="sdate" data-toggle="date" class="date input-text" size="13" id="sdate" value="{{$selects['select']['sdate']}}" readonly>
-
<input type="text" name="edate" data-toggle="date" class="date input-text" size="13" id="edate" value="{{$selects['select']['edate']}}" readonly>
 @endif

 @if(isset($selects['select']['depot']))
仓位:
    <select id='depot' name='depot' data-toggle="redirect" rel="{{$query}}">
    <option value="">全部</option>
    <option value="1" @if($selects['select']['depot']=='1')  selected @endif >1</option>
    <option value="2" @if($selects['select']['depot']=='2')  selected @endif >2</option>
    <option value="3" @if($selects['select']['depot']=='3')  selected @endif >3</option>
</select>
@endif