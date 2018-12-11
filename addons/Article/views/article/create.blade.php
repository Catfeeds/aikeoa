<div class="panel">
    <form class="form-horizontal form-controller" method="post" action="{{url()}}" id="myform" name="myform">
        <div class="form-group no-border">
            <label class="col-sm-2 control-label" for="title">
                <span class="red">*</span> 主题</label>
            <div class="col-sm-10 control-text">
                <input type="text" id="title" name="title" class="form-control input-sm" value="{{$row->title}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="receive">
                <span class="red">*</span> 对象</label>
            <div class="col-sm-10 control-text">
                {{Dialog::search($row, 'id=receive_id&name=receive_name&multi=1')}}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="datetime">
                <span class="red">*</span> 时间</label>
            <div class="col-sm-10 control-text">
                <input data-toggle="datetime" class="form-control input-inline input-sm" id="created_at" type="text" value="@datetime($row->created_at, time())"> &nbsp;至&nbsp;
                <input placeholder="留空表示一直有效。" data-toggle="datetime" class="form-control input-inline input-sm" name="expired_at" id="expired_at"
                    type="text" value="@datetime($row->expired_at)">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="category">
                <span class="red">*</span> 类型</label>
            <div class="col-sm-10 control-text">
                <select class="form-control input-inline input-sm" id='category_id' name='category_id'>
                    @foreach(option('article.category') as $category)
                    <option value='{{$category[' id ']}}' @if($row->category_id == $category['id']) selected @endif >{{$category['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="attachment">附件</label>
            <div class="col-sm-10 control-text">
                @include('attachment/create')
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="notify">通知</label>
            <div class="col-sm-10 control-text">
                <label class="checkbox-inline i-checks i-checks-sm">
                    <input name="notify[message]" type="checkbox" value="1" checked>
                    <i></i>消息
                </label>
                <label class="checkbox-inline i-checks i-checks-sm">
                    <input name="notify[mail]" type="checkbox" value="1">
                    <i></i>邮件
                </label>
                <label class="checkbox-inline i-checks i-checks-sm">
                    <input name="notify[sms]" type="checkbox" value="1">
                    <i></i>短信
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="description">描述</label>
            <div class="col-sm-10 control-text">
                <textarea placeholder="100个字符以内。" id="description" name="description" class="form-control input-sm">{{$row->description}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="content"><span class="red">*</span> 正文</label>
            <div class="col-sm-10 control-text">
                {{ueditor('content', $row->content)}}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 control-btn"></div>
            <div class="col-sm-10 control-text">
                <input type="hidden" name="id" value="{{$row->id}}">
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                <button type="submit" class="btn btn-success btn-large">
                    <i class="fa fa-check-circle"></i> 提交
                </button>
            </div>
        </div>

    </form>

</div>

<script>
ajaxSubmit('#myform');
</script>