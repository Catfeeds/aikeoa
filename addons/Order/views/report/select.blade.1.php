 @if(Auth::user()->role->name != 'salesman')

    <!--
  <select class="form-control input-sm" id='salesman_id' name='salesman_id' data-toggle="redirect" @if(Auth::user()->role->name == 'salesman') disabled @endif rel="{{$query}}">
    <option value="0">区域</option>
     @if($select['salesman']) @foreach($select['salesman'] as $k => $v)
      <option value="{{$v['id']}}" @if($select['select']['salesman_id']==$v['id']) selected @endif>{{$v['nickname']}}</option>
     @endforeach @endif
  </select>
  -->
  
    <select class="form-control input-sm" id='aspect_id' name='aspect_id' data-toggle="redirect" @if(Auth::user()->role->name == 'salesman') disabled @endif rel="{{$query}}">
    <option value="0">方面</option>
     @if($select['aspects']) @foreach($select['aspects'] as $k => $v)
      <option value="{{$v['id']}}" @if($select['select']['aspect_id']==$v['id']) selected @endif>{{$v['name']}}</option>
     @endforeach @endif
  </select>

   <select class="form-control input-sm" id='region_id' name='region_id' data-toggle="redirect" rel="{{$query}}">
  <option value="0">区域</option>
   @if($select['regions']) @foreach($select['regions'] as $k => $v)
    <option value="{{$v['id']}}" @if($select['select']['region_id']==$v['id']) selected @endif>{{$v['name']}}</option>
   @endforeach @endif
</select>

 @endif

 <select class="form-control input-sm" id='circle_id' name='circle_id' data-toggle="redirect" rel="{{$query}}">
  <option value="0">客户圈</option>
   @if($select['circles']) @foreach($select['circles'] as $k => $v)
    <option value="{{$v['id']}}" @if($select['select']['circle_id']==$v['id']) selected @endif>{{$v['name']}}</option>
   @endforeach @endif
</select>

<select class="form-control input-sm" id='customer_id' name='customer_id' data-toggle="redirect" rel="{{$query}}">
  <option value="0">客户</option>
   @if($select['customer']) @foreach($select['customer'] as $k => $v)
    <option value="{{$v['id']}}" @if($select['select']['customer_id']==$v['id']) selected @endif>{{$v['company_name']}}</option>
   @endforeach @endif
</select>

<!--
<select class="form-control input-sm" id='province_id' name='province_id' data-toggle="redirect" rel="{{$query}}">
  <option value="0">省份</option>
   @if($select['province']) @foreach($select['province'] as $k => $v)
    <option value="{{$v['id']}}" @if($select['select']['province_id']==$v['id']) selected @endif>{{$v['name']}}</option>
   @endforeach @endif
</select>

&nbsp;

<select class="form-control input-sm" id='city_id' name='city_id' data-toggle="redirect" rel="{{$query}}">
  <option value="0">城市</option>
   @if($select['city']) @foreach($select['city'] as $k => $v)
    <option value="{{$v['id']}}" @if($select['select']['city_id']==$v['id']) selected @endif>{{$v['name']}}</option>
   @endforeach @endif
</select>

&nbsp;

<select class="form-control input-sm" id='customer_id' name='customer_id' data-toggle="redirect" rel="{{$query}}">
  <option value="0">客户</option>
   @if($select['customer']) @foreach($select['customer'] as $k => $v)
    <option value="{{$v['id']}}" @if($select['select']['customer_id']==$v['id']) selected @endif>{{$v['company_name']}}</option>
   @endforeach @endif
</select>
-->

@if($select['select']['time_type'])
&nbsp;
<select class="form-control input-sm" id='time_type' name='time_type' data-toggle="redirect" rel="{{$query}}">
    <option value="delivery_time" @if($select['select']['time_type']=='delivery_time') selected @endif>发货模式</option>
    <option value="add_time" @if($select['select']['time_type']=='add_time') selected @endif>订单模式</option>
</select>

@endif

@if($select['select']['sdate'])
    &nbsp;
    日期
    <input class="form-control input-sm" data-toggle="date" value="{{$select['select']['sdate']}}" size="13" name="sdate" id="sdate">
@endif

@if($select['select']['date'])
    -
    <input class="form-control input-sm" data-toggle="date" value="{{$select['select']['date']}}" size="13" name="date" id="date">
@endif
