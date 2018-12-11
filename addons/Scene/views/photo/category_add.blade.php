<table class="list">
<form method="post" action="{{url()}}" id="myform" name="myform">

<tr>
    <th align="right">名称 <span class="red">*</span></th>
    <td align="left">
        <input type="text" class="input-text" name="title" value="{{$row['title']}}" />
    </td>
</tr>

<tr>
    <th align="right" width="80">类别状态</th>
    <td>
        <label class="checkbox-inline"><input type="radio" name="state" value="1"  @if($row['id']>0&&$row['state']==1) checked="true" @endif > 启用</label>
        &nbsp;
        <label class="checkbox-inline"><input type="radio" name="state" value="0"  @if($row['id']>0&&$row['state']==0) checked="true" @endif > 停用</label>
    </td>
</tr>

<tr>
    <th align="right">排序</th>
    <td align="left">
    <input type="text" class="input-text" name="sort" value="{{$row['sort']}}" />
    </td>
</tr>

<tr>
    <th align="right">备注</th>
    <td align="left">
    <textarea class="input-text" rows="3" cols="20" type="text" name="remark" id="remark" />{{$row['remark']}}</textarea>
    </td>
</tr>

</table>

<input type="hidden" name="id" value="{{$row['id']}}" />
<button type="button" onclick="history.back();" class="btn btn-default">返回</button>
<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
