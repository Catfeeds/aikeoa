<div class="form-group">
    <label class="control-label">人员</label>
    <select id="car_user_id" name="car_user_id" data-toggle="redirect" class="form-control input-sm" rel="{{url($atcion,$query)}}">
        <option value=""> - </option>
        @foreach($car_user as $user_id)
            <option value="{{$user_id}}" @if($query['car_user_id'] == $user_id)selected @endif>{{get_user($user_id,'nickname')}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="control-label">车牌号</label>
    <select id="car_id" name="car_id" data-toggle="redirect" class="form-control input-sm" rel="{{url($atcion,$query)}}">
        <option value=""> - </option>
        @foreach($cars as $car)
          <option value="{{$car['id']}}" @if($query['car_id'] == $car['id'])selected @endif>{{$car['car_number']}}</option>
        @endforeach
    </select>
</div>

<div class="input-group">
    <span class="bg-white input-group-addon">日期</span>
    <input type="text" name="start_at" class="form-control input-sm" data-toggle="date" size="13" id="start_at" value="{{$query['start_at']}}" readonly>
</div>

<div class="input-group">
    <span class="bg-white input-group-addon"> - </span>
    <input type="text" name="end_at" class="form-control input-sm" data-toggle="date" size="13" id="end_at" value="{{$query['end_at']}}" readonly>

</div>