<div class="panel">

    @include('query')

    <form method="post" action="{{url()}}" id="myform" name="myform">

    <table class="table table-form b-t m-b-none">
        <tr>
            <td align="right" width="10%">权限名称<span class="red">*</span></td>
            <td width="40%">
                <input type="text" id="name" name="name" value="{{$permission['name']}}" class="form-control input-sm input-inline">
            </td>
            <td align="right" width="10%">权限类型 <span class="red">*</span></td>
            <td width="40%">
                <select multiple="multiple" class="input-select2 form-control input-sm input-inline" style="width:100%;" name="type[]">
                    <option value="create" @if(in_array('create', $permission['type'])) selected @endif>新增</option>
                    <option value="edit" @if(in_array('edit', $permission['type'])) selected @endif>编辑</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">权限范围 <span class="red">*</span></td>
            <td>
                {{Dialog::search($permission, 'id=receive_id&name=receive_name&multi=1')}}
            </td>
            <td align="right"></td>
            <td></td>
        </tr>

    </table>
</div>

<div class="panel">
<table class="table table-form">
<tr>
    <td>名称</td>
    <td>字段</td>
    <td>字段可写</td>
    <td>字段保密</td>
    <td>验证规则</td>
</tr>

@foreach($columns as $table => $column)

<!-- 子表增删改查 -->
@if($column['master'] == 0)
<tr>
    <td>
        <span class="label label-success">{{$column['name']}}</span> 权限
    </td>
    <td>
        {{$table}}@option
    </td>
    <td>
    </td>
    <td>
    </td>
    <td>
        <label><input type="checkbox" @if($permission['data'][$table]['@option']['w'] == 1) checked @endif name="data[{{$table}}][@option][w]" value="1"> 增删</label>
    </td>
</tr>
@endif

@foreach($column['fields'] as $field)
<tr>
    <td>
        @if($column['master'] == 0)
            <span class="label label-primary">{{$column['name']}}</span>
        @endif
        {{$field->name}}
    </td>
    <td>
        {{$table}}.{{$field->field}}
    </td>
    <td>
        <input type="checkbox" @if($permission['data'][$table][$field->field]['w'] == 1) checked @endif class="field-edit" data-key="{{$table}}_{{$field->field}}" id="{{$table}}_{{$field->field}}_edit" name="data[{{$table}}][{{$field->field}}][w]" value="1">
    </td>
    <td>
        <input type="checkbox" @if($permission['data'][$table][$field->field]['s'] == 1) checked @endif class="field-secret" data-key="{{$table}}_{{$field->field}}" id="{{$table}}_{{$field->field}}_secret" name="data[{{$table}}][{{$field->field}}][s]" value="1">
    </td>
    <td>
        @if($field['form_type'] == 'auto')
            <label title="锁定将不允许修改宏控件的值">
                <input type="checkbox" value="1" @if($permission['data'][$table][$field->field]['m'] == 1) checked @endif name="data[{{$table}}][{{$field->field}}][m]"> 锁定
            </label>
        @else
        <select multiple data-placeholder="选择验证规则" class="form-control input-sm input-inline input-select2" style="width:200px;" name="data[{{$table}}][{{$field->field}}][v][]">
            <option value=""></option>
            @foreach($regulars as $key => $regular)
                <option @if(in_array($key, (array)$permission['data'][$table][$field->field]['v'])) selected @endif value="{{$key}}">{{$regular}}</option>
            @endforeach
        </select>
        @endif

    </td>
</tr>

@endforeach
@endforeach

</table>
</div>

<div class="panel">
    <table class="table table-form m-b-none">
        <tr>
            <td>
                <input type="hidden" name="id" value="{{$permission['id']}}">
                <input type="hidden" name="model_id" value="{{$permission['model_id']}}">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 保存</button>
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            </td>
        </tr>
    </table>
</div>

</form>