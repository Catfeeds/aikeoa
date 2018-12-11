<div class="panel">
    <form method="post" action="{{url()}}" id="myform" name="myform">
        <table class="table table-form">

            @if($row['layer'] > 1)
            <tr>
                <td align="right">上级 <span class="red">*</span></td>
                <td align="left">
                    <select class="form-control input-sm" name="parent_id" id="parent_id">
                        <option value=""> - </option>
                        @if($rows)
                        @foreach($rows as $k => $v)
                            <option value="{{$v['id']}}" @if($row['parent_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                        @endforeach 
                        @endif
                    </select>
                </td>
            </tr>
            @endif

            <tr>
                <td align="right" width="10%">名称 <span class="red">*</span></td>
                <td align="left">
                    <input type="text" class="form-control input-sm" name="name" value="{{$row['name']}}">
                </td>
            </tr>

            @if($row['layer'] == 3)

            <tr>
                <td align="right">审批人</td>
                <td>
                    {{Dialog::user('user','owner_user_id', old('owner_user_id', $row['owner_user_id']), 0, 0)}}
                </td>
            </tr>

            <tr>
                <td align="right">查阅人</td>
                <td>
                    {{Dialog::user('user','owner_assist', old('owner_assist', $row['owner_assist']), 1, 0)}}
                </td>
            </tr>

            @endif

            <tr>
                <td align="right">备注</td>
                <td align="left">
                <textarea class="form-control input-sm" rows="3" cols="20" type="text" name="description" id="description">{{$row['description']}}</textarea>
                </td>
            </tr>

            <tr>
                <td align="right"></td>
                <td align="left">
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <input type="hidden" name="layer" value="{{$row['layer']}}">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                </td>
            </tr>
        </table>
    </form>
</div>