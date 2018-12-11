<div class="panel">

<table class="table table-form">
<form method="post" action="{{url()}}" id="myform" name="myform">
<tr>
    <td align="right" width="10%"><span class="red">*</span> 名称</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="name" value="{{$row['name']}}" />
    </td>
</tr>

<tr>
    <td align="right">排序</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="sort" value="{{$row['sort']}}" />
    </td>
</tr>

<tr>
    <td align="right">备注</td>
    <td align="left">
    <textarea class="form-control" name="remark" id="remark">{{$row['remark']}}</textarea>
    </td>
</tr>

<tr>
    <td align="right"></td>
    <td align="left">
        <input type="hidden" name="id" value="{{$row['id']}}" />
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </td>
</tr>

</form>
</table>

</div>
