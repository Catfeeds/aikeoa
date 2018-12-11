<div class="panel">

<table class="table table-form">
<form method="post" action="{{url()}}" id="myform" name="myform">

<tr>
    <td align="right" width="10%">上级类别 <span class="text-red">*</span></td>
    <td align="left">
        <select class="form-control input-inline input-sm" name="parent_id" id="parent_id">
            <option value=""> - </option>
            @if($categorys)
            @foreach($categorys as $k => $v)
                <option value="{{$v['id']}}" @if($category->parent_id == $v['id']) selected @endif>{{$v['layer_space']}}{{$v['name']}}</option>
            @endforeach 
            @endif
        </select>
    </td>
</tr>

<tr>
	<td align="right">类别代码 <span class="red">*</span></td>
	<td align="left">
	<input type="text" class="form-control input-inline input-sm" name="code" value="{{old('code', $category->code)}}">
	</td>
</tr>

<tr>
    <td align="right">名称 <span class="red">*</span></td>
    <td align="left">
    <input type="text" class="form-control input-inline input-sm" name="name" value="{{$category->name}}" />
    </td>
</tr>

<tr>
    <td align="right">排序</td>
    <td align="left">
    <input type="text" class="form-control input-inline input-sm" name="sort" value="{{$category->sort}}" />
    </td>
</tr>

<tr>
    <td align="right">状态</td>
    <td align="left">
        <select class="form-control input-inline input-sm" id="status" name="status">
            <option value="1" @if($category->status == '1') selected="selected" @endif>启用</option>
            <option value="0" @if($category->status == '0') selected="selected" @endif>停用</option>
        </select>
    </td>
</tr>


<tr>
    <td align="right">描述</td>
    <td align="left">
        <textarea class="form-control input-sm" type="text" name="title" id="title">{{$category->title}}</textarea>
    </td>
</tr>

<tr>
    <td align="right"></td>
    <td align="left">
        <input type="hidden" name="id" value="{{$category->id}}" />
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    </td>
</tr>

</table>

</div>