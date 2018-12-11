<form method="post" action="{{url()}}" id="myform" name="myform">
<table class="list">
<tr>
    <th width="15%" align="right">主题</th>
    <td><input type="text" id="title" name="title" size="40" value="{{$row['title']}}" class="input-text" /></td>
</tr>

<tr>
    <th align="right">客户</th>
    <td>
        <input type="text" id="customer_name" size="30" value="{{$customer[$row['customer_id']]['title']}}" class="readonly" readonly>
        <input type="hidden" id="customer_id" name="customer_id" value="{{$row['customer_id']}}" />
        <a class="orgadd" href="javascript:selectClient('customer_id', 'customer_name');">添加</a>
        <a class="orgclear" href="javascript:selectClear('customer_id', 'customer_name');">清空</a>
    </td>
</tr>

<tr>
    <th align="right">类别</th>
    <td>
        <select id='type' name='type'>
             @if($categorys) @foreach($categorys as $k => $v)
                <option value='{{$v['id']}}' @if($row['type']==$v['id'])  selected="true" @endif >{{$v['name']}}</option>
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
