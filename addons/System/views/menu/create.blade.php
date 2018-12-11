<script src="{{$asset_url}}/vendor/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js"></script>
<link href="{{$asset_url}}/vendor/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet">

<script>
$(function() {
    $('.icp-auto').iconpicker();
});
</script>

<div class="panel">

    <form method="post" class="form-horizontal" action="{{url()}}" id="myform" name="myform">
        
        <table class="table table-form">

            <tr>
                <td align="right" width="100">上级</td>
                <td align="left">
                    <select class="form-control input-inline input-sm" name="parent_id" id="parent_id">
                        <option value=""> - </option>
                        @foreach($parents as $parent)
                        <option value="{{$parent['id']}}" @if($row['parent_id'] == $parent['id']) selected @endif>{{$parent['layer_space']}}{{$parent['name']}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">名称</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" id="name" name="name" value="{{$row['name']}}">
                </td>
            </tr>

            <tr>
                <td align="right">URL</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" id="url" name="url" value="{{$row['url']}}">
                </td>
            </tr>

            <tr>
                <td align="right">图标</td>
                <td align="left">
                    <div class="input-group">
                        <span class="input-group-addon"></span>
                        <input data-placement="bottomLeft" type="text" class="form-control icp icp-auto input-sm" name="icon" value="{{$row['icon']}}">
                    </div>
                </td>
            </tr>

            <tr>
                <td align="right">颜色</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" id="color" name="color" value="{{$row['color']}}">
                </td>
            </tr>

            <tr>
                <td align="right">验证</td>
                <td align="left">
                    <select class="form-control input-inline input-sm" name="access" id="access">
                        <option value="1" @if($row['access'] == 1) selected @endif>是</option>
                        <option value="0" @if($row['access'] == 0) selected @endif>否</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">备注</td>
                <td align="left">
                    <textarea class="form-control" name="description" id="description">{{$row['description']}}</textarea>
                </td>
            </tr>

            <tr>
                <td align="right"></td>
                <td>
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                </td>
            </tr>

        </table>
    </form>
</div>