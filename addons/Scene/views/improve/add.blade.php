<form method="post" action="{{url()}}" id="myform" name="myform">
<table class="list">
<tr>
    <th width="80" align="right">主题</th>
    <td><input type="text" id="title" name="title" size="40" value="{{$row['title']}}" class="input-text" /></td>
</tr>

<tr>
    <th align="right">发布部门</th>
    <td>
        {{Dialog::user('department','access_department_id',$row['access_department_id'], 1, 0)}}
        <div class="help-inline">阅读人员是发布部门、发布角色、发布人员的并集。</div>
    </td>
</tr>

<tr>
    <th align="right">发布角色</th>
    <td>
        {{Dialog::user('role','access_role_id',$row['access_role_id'], 1, 0)}}
    </td>
</tr>

<tr>
    <th align="right">发布人员</th>
    <td>
        {{Dialog::user('user','access_user_id',$row['access_user_id'], 1, 0)}}
    </td>
</tr>

<tr>
    <th align="right">类别</th>
    <td>
        <select id='category_id' name='category_id'>
             @if($categorys) @foreach($categorys as $k => $v)
                <option value='{{$v['id']}}' @if($row['category_id']==$v['id'])  selected="true" @endif >{{$v['name']}}</option>
             @endforeach @endif
        </select>
    </td>
</tr>

<tr>
    <th align="right">附件管理</th>
    <td align="left">
        @include('attachment/add')
    </td>
</tr>

<tr>
    <td colspan="2">
        {{ueditor('content', $row['content'])}}
    </td>
</tr>

</table>

<input type="hidden" name="id" value="{{$row['id']}}" />
<button type="button" onclick="history.back();" class="btn btn-default">返回</button>
<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>

</form>
