<div class="panel">

<form method="post" action="{{url()}}" id="myform" name="myform">
<table class="table table-form">
<tr>
    <td width="10%" align="right">类别名称 <span class="red">*</span></td>
    <td>
        <input type="text" id="name" name="name" value="{{$row->name}}" class="form-control input-inline input-sm" />
    </td>
</tr>

<tr>
    <td align="right">类别负责人 <span class="red">*</span></td>
    <td>
        {{Dialog::user('user','user_id',$row['user_id'], 0, 0)}}
        <span class="help-inline"></span>
    </td>
</tr>

<tr>
    <td align="right">类别状态</td>
    <td>
        <select id='status' name='status' class="form-control input-inline input-sm">
            <option value='1' @if($row->status == 1) selected="selected" @endif>正常</option>
            <option value='2' @if($row->status == 2) selected="selected" @endif>禁用</option>
        </select>
    </td>
</tr>

<tr>
    <td align="right">类别描述</td>
    <td>
        <textarea id="description" name="description" rows="5" class="form-control input-sm">{{$row->description}}</textarea>
    </td>
</tr>

<tr>
    <td align="right"></td>
    <td>
        <input type="hidden" name="id" value="{{$row->id}}" />
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </td>
</tr>

</table>

</form>

</div>