<select id='category_id' name='category_id' class="form-control input-sm input-inline" data-toggle="redirect" rel="{{$query}}">
  <option value="0">巡查类别</option>
   @if($categorys)
   @foreach($categorys as $k => $v)
    <option value="{{$v['id']}}" @if($selects['select']['category_id']==$v['id']) selected="true" @endif >{{$v['title']}}</option>
   @endforeach 
   @endif
</select>

 @if(Auth::user()->role->name != 'customer')

     @if(Auth::user()->role->name != 'salesman')
    <select id='salesman_id' name='salesman_id' class="form-control input-sm input-inline" data-toggle="redirect" @if(Auth::user()->role->name == 'salesman') disabled="true" @endif rel="{{$query}}">
      <option value="0">区域</option>
       @if($selects['salesman'])
       @foreach($selects['salesman'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['salesman_id']==$v['id']) selected="true" @endif >{{$v['nickname']}}</option>
       @endforeach 
       @endif
    </select>
    &nbsp;
    @endif


    <select id='province_id' name='province_id' class="form-control input-sm input-inline" data-toggle="redirect" rel="{{$query}}">
      <option value="0">省份</option>
       @if($selects['province'])
       @foreach($selects['province'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['province_id']==$v['id']) selected="true" @endif >{{$v['name']}}</option>
       @endforeach 
       @endif
    </select>
    &nbsp;
    <select id='city_id' name='city_id' class="form-control input-sm input-inline" data-toggle="redirect" rel="{{$query}}">
      <option value="0">城市</option>
       @if($selects['city'])
       @foreach($selects['city'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['city_id']==$v['id']) selected="true" @endif >{{$v['name']}}</option>
       @endforeach 
       @endif
    </select>
    &nbsp;
    <select id='customer_id' name='customer_id' class="form-control input-sm input-inline" data-toggle="redirect" rel="{{$query}}">
      <option value="0">客户</option>
       @if($selects['customer'])
       @foreach($selects['customer'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['customer_id']==$v['id']) selected="true" @endif >{{$v['company_name']}}</option>
       @endforeach 
       @endif
    </select>
 @endif

 @if(isset($selects['select']['sdate']))
&nbsp;上传日期:
<input type="text" name="sdate" data-toggle="date" class="form-control input-sm input-inline" size="13" id="sdate" value="{{$selects['select']['sdate']}}" readonly>
-
<input type="text" name="edate" data-toggle="date" class="form-control input-sm input-inline" size="13" id="edate" value="{{$selects['select']['edate']}}" readonly>
 @endif

&nbsp;
查找
<select id='search_key' name='search_key' class="form-control input-sm input-inline">
    <option value="c.nickname" @if($selects['select']['search_key'] == 'c.nickname') selected @endif>客户名称</option>
</select>

<select id='search_condition' name='search_condition' class="form-control input-sm input-inline">
    <option value="like" @if($selects['select']['search_condition']=='like') selected @endif >包含</option>
    <option value="=" @if($selects['select']['search_condition']=='=') selected @endif >等于</option>
</select>
<input type="text" class="form-control input-sm input-inline" size="20" name="search_value" value="{{$selects['select']['search_value']}}" />
