<div class="panel">

<form method="post" action="{{url()}}" id="myform" name="myform">
<table class="table table-form">

<tr>
    <td width="10%" align="right">品牌</td>
    <td><input type="text" id="name" name="name" value="{{$row->name}}" class="form-control input-inline input-sm" /></td>
</tr>

<tr>
    <td align="right">型号</td>
    <td><input type="text" id="model" name="model" value="{{$row->model}}" class="form-control input-inline input-sm" /></td>
</tr>

<tr>
    <td align="right">识别码</td>
    <td><input type="text" id="number" name="number" value="{{$row->number}}" class="form-control input-inline input-sm" /></td>
</tr>

<tr>
    <td align="right">使用年限</td>
    <td><input type="text" id="age_limit" name="age_limit" value="{{$row->age_limit}}" class="form-control input-inline input-sm" /></td>
</tr>

<tr>
    <td align="right">采购日期</td>
    <td><input type="text" id="buy_date" name="buy_date" value="{{$row->buy_date}}" data-toggle="date" class="form-control input-inline input-sm" /></td>
</tr>

<tr>
    <td align="right">首次使用日期</td>
    <td><input type="text" id="use_date" name="use_date" value="{{$row->use_date}}" data-toggle="date" class="form-control input-inline input-sm" /></td>
</tr>

@if($access['index'] == 4)
<tr>
    <td align="right">资产类别</td>
    <td>
        <select id='asset_id' name='asset_id' class="form-control input-inline input-sm">
        @if($assets)
            @foreach($assets as $asset)
                <option value='{{$asset->id}}' @if($row->asset_id == $asset->id) selected="selected" @endif >{{$asset->name}}</option>
            @endforeach
        @endif
        </select>
    </td>
</tr>
@endif

<tr>
    <td align="right">状态</td>
    <td>
        <select id='status' name='status' class="form-control input-inline input-sm">
        @foreach($status as $_key => $_status)
            <option value='{{$_status['id']}}' @if($row->status == $_status['id']) selected="selected" @endif>{{$_status['name']}}</option>
        @endforeach
        </select>
    </td>
</tr>

<tr>
    <td align="right">详细说明</td>
    <td>
        <textarea id="description" name="description" class="form-control input-sm">{{$row->description}}</textarea>
    </td>
</tr>

<tr>
    <td align="right"></td>
    <td>
        <input type="hidden" name="id" value="{{$row['id']}}" />
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </td>
</tr>

</table>

</form>

</div>