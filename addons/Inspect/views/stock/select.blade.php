 @if(Auth::user()->role->name != 'customer')

     @if(Auth::user()->role->name != 'salesman')
    <select id='salesman_id' name='salesman_id' data-toggle="redirect" @if(Auth::user()->role->name == 'salesman') disabled @endif rel="{{$url}}">
      <option value="0">区域</option>
       @if($selects['salesman'])
       @foreach($selects['salesman'] as $k => $v)
        <option value="{{$v['id']}}" @if($query['salesman_id'] == $v['id']) selected @endif>{{$v['nickname']}}</option>
       @endforeach 
       @endif
    </select>
    &nbsp;
    @endif

    <select id='province_id' name='province_id' data-toggle="redirect" rel="{{$url}}">
      <option value="0">省份</option>
       @if($selects['province'])
       @foreach($selects['province'] as $k => $v)
        <option value="{{$v['id']}}" @if($query['province_id']==$v['id']) selected @endif>{{$v['name']}}</option>
       @endforeach 
       @endif
    </select>
    &nbsp;
    <select id='city_id' name='city_id' data-toggle="redirect" rel="{{$url}}">
      <option value="0">城市</option>
       @if($selects['city'])
       @foreach($selects['city'] as $k => $v)
        <option value="{{$v['id']}}" @if($query['city_id']==$v['id']) selected @endif>{{$v['name']}}</option>
       @endforeach 
       @endif
    </select>
    &nbsp;
    <select id='customer_id' name='customer_id' data-toggle="redirect" rel="{{$url}}">
      <option value="0">客户</option>
       @if($selects['customer'])
       @foreach($selects['customer'] as $k => $v)
        <option value="{{$v['id']}}" @if($query['customer_id']==$v['id']) selected @endif>{{$v['company_name']}}</option>
       @endforeach 
       @endif
    </select>
 @endif

 @if(isset($query['sdate']))
&nbsp;上传日期:
<input type="text" name="sdate" data-toggle="date" class="input-text" size="13" id="sdate" value="{{$query['sdate']}}" readonly>
-
<input type="text" name="edate" data-toggle="date" class="input-text" size="13" id="edate" value="{{$query['edate']}}" readonly>
 @endif

&nbsp;
查找
<select id='search_key' name='search_key'>
    <option value="c.nickname" @if($query['search_key'] == 'c.nickname') selected @endif>客户名称</option>
</select>

<select id='search_condition' name='search_condition'>
    <option value="like" @if($query['search_condition']=='like') selected @endif>包含</option>
    <option value="=" @if($query['search_condition']=='=') selected @endif>等于</option>
</select>
<input type="text" class="input-text" size="20" name="search_value" value="{{$query['search_value']}}" />
