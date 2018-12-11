<form method="post" action="{{url()}}" id="myform" name="myform">
<div class="panel">
<table class="table">

<tr>
    <td align="right" width="10%">变更方式 <span class="red">*</span></td>
    <td>
        <select class="form-control input-inline input-sm" id="type" name="type">
            <option value="1">初次使用</option>
            <option value="2">变更使用</option>
            <option value="3">交回管理</option>
        </select>
        <span class="help-inline"> 交回管理者则不选择使用人</span>
    </td>
</tr>

<tr>
    <td align="right">开始日期 <span class="red">*</span></td>
    <td><input type="text" id="start_date" name="start_date" value="{{$row->start_date}}" data-toggle="date" class="form-control input-inline input-sm" /></td>
</tr>

<tr>
    <td align="right">使用人 <span class="red">*</span></td>
    <td>
        {{Dialog::user('user','user_id',$row['user_id'], 0, 0)}}
        <span class="help-inline"></span>
    </td>
</tr>

<tr>
    <td align="right">描述说明</td>
    <td>
        <textarea id="description" name="description" class="form-control input-sm">{{$row->description}}</textarea>
    </td>
</tr>

<tr>
    <td align="right"></td>
    <td>
        <input type="hidden" name="id" value="{{$row->id}}" />
        <input type="hidden" name="data_id" value="{{$data_id}}" />
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </td>
</tr>

</table>

</div>
</form>
