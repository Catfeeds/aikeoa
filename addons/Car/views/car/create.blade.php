@if(Input::old())
    {{'';$row = Input::old()}}
@endif

<div class="panel">
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
            
            <div class="form-group">
                <label class="col-sm-2 control-label">车牌号</label>
                <div class="col-sm-10">
                    <input class="form-control input-sm" type="text" id="car_number" name="car_number" value="{{$row['car_number']}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">车型</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control input-sm" name="car_type" value="{{$row['car_type']}}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">车架号</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control input-sm" name="car_frame_number" value="{{$row['car_frame_number']}}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">行驶证号</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control input-sm" name="car_driving_license" value="{{$row['car_driving_license']}}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">购买时间</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control input-sm" data-toggle="date" name="car_buy_date" value="{{$row['car_buy_date']}}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">使用人</label>
                <div class="col-sm-10">
                    {{Dialog::user('user','car_user_id',$row['car_user_id'], 1, 0)}}
                </div>
            </div>    

            <div class="form-group">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea class="form-control input-sm" name="remark" id="remark">{{$row['remark']}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </div>
            </div>
        </form>
    </div>
</div>