<form class="form-horizontal form-controller" method="post" action="{{url()}}" id="myform" name="myform">

<div class="panel">

        <div class="form-group no-border">
            <label class="col-sm-2 control-label" for="name">
                <span class="red">*</span> 模型名称</label>
                <div class="col-sm-10 control-text">
                    <input type="text" id="name" name="name" class="form-control input-sm" value="{{$row->name}}" onblur="app.pinyin('name','table');">
                </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="table">
                <span class="red">*</span> 模型表名</label>
                <div class="col-sm-10 control-text">
                    <input type="text" id="table" name="table" class="form-control input-sm" value="{{$row->table}}" @if($row->id > 0) readonly @endif>
                </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="parent_id">
                <span class="red">*</span> 父节模型</label>
                <div class="col-sm-10 control-text">
                        <select class="form-control input-sm" name="parent_id" id="parent_id">
                            <option value="0"> - </option>
                            @foreach($models as $model)
                                <option value="{{$model->id}}" @if($model->id == $row->parent_id) selected @endif>{{$model->name}}</option>
                            @endforeach
                        </select>
                </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="type">
            <span class="red">*</span> 模型类型</label>
            <div class="col-sm-10 control-text">
                    <select class="form-control input-sm" name="type" id="type">
                        <option value="0" @if($row->type == 0) selected @endif> - </option>
                        <option value="1" @if($row->type == 1) selected @endif>多行子表</option>
                    </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="relation">
            <span class="red">*</span> 关联外键</label>
            <div class="col-sm-10 control-text">
                <input type="text" id="relation" name="relation" value="{{$row->relation}}" class="form-control input-sm">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="sort">
            <span class="red">*</span> 数据排序</label>
            <div class="col-sm-10 control-text">
                <input type="text" id="sort" name="sort" value="{{$row->sort}}" class="form-control input-sm">
            </div>
        </div>
        @if($row->parent_id == 0)
        <div class="form-group">
            <label class="col-sm-2 control-label">流程类型</label>
            <div class="col-sm-10 control-text">
                <label class="radio-inline"><input type="radio" @if($row['is_sort'] == 1) checked @endif value="1" name="is_sort"> 是 </label>
                <label class="radio-inline"><input type="radio" @if($row['is_sort'] == 0) checked @endif value="0" name="is_sort"> 否 </label>
            </div>
        </div>

        <div class="form-group">
                <label class="col-sm-2 control-label">流程类型</label>
                <div class="col-sm-10 control-text">
                    <label class="radio-inline"><input type="radio" @if($row['is_flow'] == 1) checked @endif value="1" name="is_flow"> 固定 </label>
                    <label class="radio-inline"><input type="radio" @if($row['is_flow'] == 2) checked @endif value="2" name="is_flow"> 自由 </label>
                    <label class="radio-inline"><input type="radio" @if($row['is_flow'] == 0) checked @endif value="0" name="is_flow"> 无 </label>
                </div>
            </div>
        @endif

        <div class="form-group">
            <div class="col-sm-2 control-btn"></div>
            <div class="col-sm-10 control-text">
                <input type="hidden" name="id" value="{{$row->id}}">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 保存</button>
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            </div>
        </div>
    </div>

</form>

<script>
ajaxSubmit('#myform');
</script>