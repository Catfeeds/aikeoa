<div class="panel">
<form method="post" action="{{url()}}" id="myform" name="myform">
<table class="table table-form">
<tr>
    <td align="right" width="10%">名称</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="title" value="{{$row['title']}}" />
    </td>
</tr>

<tr>
    <td align="right">排序</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="sort" value="{{$row['sort']}}" />
    </td>
</tr>

<tr>
    <td align="right">描述</td>
    <td align="left">
    <textarea class="form-control input-sm" rows="3" type="text" name="remark" id="remark">{{$row['remark']}}</textarea>
    </td>
</tr>

<tr>
    <td align="right"></td>
    <td align="left">
        <input type="hidden" name="id" value="{{$row['id']}}">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        
    </td>
</tr>

</table>

</form>
</div>