   @if(Auth::user()->role->name != 'customer')

   @if(Auth::user()->role->name != 'salesman')

   <div class="form-group">
    <select id='salesman_id' name='salesman_id' data-toggle="redirect" class='form-control input-sm' @if(Auth::user()->role->name == 'salesman') disabled="true" @endif rel="{{$selects['query']}}">
        <option value="0">区域</option>
        @if($selects['salesman'])
            @foreach($selects['salesman'] as $k => $v)
                <option value="{{$v['id']}}" @if($selects['select']['salesman_id']==$v['id']) selected="true" @endif >{{$v['nickname']}}</option>
            @endforeach
        @endif
    </select>
    </div>
    
    @endif

    <div class="form-group">
    <select id='province_id' name='province_id' class='form-control input-sm' data-toggle="redirect" rel="{{$selects['query']}}">
      <option value="0">省份</option>
       @if($selects['province']) @foreach($selects['province'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['province_id']==$v['id']) selected="true" @endif >{{$v['name']}}</option>
       @endforeach @endif
    </select>
    </div>

    <div class="form-group">
    <select id='city_id' name='city_id' class='form-control input-sm' data-toggle="redirect" rel="{{$selects['query']}}">
      <option value="0">城市</option>
       @if($selects['city']) @foreach($selects['city'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['city_id']==$v['id']) selected="true" @endif >{{$v['name']}}</option>
       @endforeach @endif
    </select>
    </div>

    <div class="form-group">
    <select id='customer_id' name='customer_id' class='form-control input-sm' data-toggle="redirect" rel="{{$selects['query']}}">
      <option value="0">客户</option>
       @if($selects['customer']) @foreach($selects['customer'] as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['customer_id']==$v['id']) selected="true" @endif >{{$v['company_name']}}</option>
       @endforeach @endif
    </select>
    </div>
@endif

@if(isset($selects['select']['type_id']))
&nbsp;
<div class="form-group">
<select class='form-control input-sm' data-toggle="redirect" name="type_id" id="type_id" rel="{{$selects['query']}}">
    <option value="">客户类型</option>
     @if($types)
     @foreach($types as $k => $v)
        <option value="{{$v['id']}}" @if($selects['select']['type_id']==$k) selected @endif>{{$v['title']}}</option>
     @endforeach 
     @endif
</select>
</div>
@endif

@if(isset($selects['select']['status']))
&nbsp;
<div class="form-group">
<select class='form-control input-sm' data-toggle="redirect" id='status' name='status' rel="{{$selects['query']}}">
    <option value='1' @if($selects['select']['status']=='1') selected @endif>正常客户</option>
    <option value='0' @if($selects['select']['status']=='0') selected @endif>停用客户</option>
</select>
</div>
@endif

@if(isset($selects['select']['invoice']))
&nbsp;
<div class="form-group">
<select class='form-control input-sm' data-toggle="redirect" name="invoice" id="invoice" rel="{{$selects['query']}}">
    <option value="">开票类型</option>
    @foreach(option('customer.invoice') as $v)
      <option value="{{$v['id']}}" @if($selects['select']['invoice'] == $v['id']) selected @endif>{{$v['name']}}</option>
    @endforeach
</select>
</div>
@endif

&nbsp;
<div class="form-group">
<select id='search_key' class="form-control input-sm" name='search_key'>
    <option value="user.username" @if($selects['select']['search_key']=='user.username') selected="true" @endif >客户代码</option>
    <option value="user.nickname" @if($selects['select']['search_key']=='user.nickname') selected="true" @endif >客户名称</option>
</select>
</div>

<div class="form-group">
<select id='search_condition' class="form-control input-sm" name='search_condition'>
    <option value="like" @if($selects['select']['search_condition']=='like') selected="true" @endif >包含</option>
    <option value="=" @if($selects['select']['search_condition']=='=') selected="true" @endif >等于</option>
</select>
</div>

<div class="input-group">
    <input type="text" class="form-control input-sm" name="search_value" value="{{$selects['select']['search_value']}}">
    <span class="input-group-btn">
        <button type="submit" class="btn btn-default btn-sm">过滤</button>
    </span>
</div>
