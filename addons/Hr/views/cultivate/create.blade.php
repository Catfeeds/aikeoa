<div class="panel">
    <form class="form-horizontal form-controller" method="post" action="{{url()}}" id="myform" name="myform">

        <div class="form-group no-border">
            <label class="col-sm-2 control-label">培训机构</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="name" name="name" value="{{old('name', $row->name)}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">培训项目</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="organization" name="organization" value="{{old('organization', $row->organization)}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">培训日期</label>
            <div class="col-sm-10 control-text">
                <input data-toggle="date" class="form-control input-sm" type="text" id="start_date" name="start_date" value="{{old('start_date', $row->start_date)}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">培训费用</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="cost" name="cost" value="{{old('cost', $row->cost)}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">培训周期</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="cycle" name="cycle" value="{{old('cycle', $row->cycle)}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">流程编号</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="process_id" name="process_id" value="{{old('process_id', $row->process_id)}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">详细描述</label>
            <div class="col-sm-10 control-text">
                <textarea class="form-control input-sm" name="description" id="description">{{old('description', $row->description)}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 control-btn"></div>
            <div class="col-sm-10 control-label">
                <input type="hidden" name="id" value="{{$row->id}}">
                <input type="hidden" name="hr_id" value="{{$row->hr_id}}">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check-circle"></i> 提交</button>
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            </div>
        </div>
    </form>
</div>