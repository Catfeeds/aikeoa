<div class="panel">
    <form method="post" action="{{url()}}" id="myform" name="myform">
        <table class="table table-form">
            <tr>
                <td align="right" width="10%">上级类别 <span class="red">*</span></td>
                <td align="left">
                    <select class="form-control input-sm" name="parent_id" id="parent_id">
                        <option value=""> - </option>
                        @if($type)
                        @foreach($type as $k => $v)
                            <option value="{{$v['id']}}" @if($row['parent_id'] == $v['id']) selected @endif>{{$v['layer_space']}}{{$v['title']}}</option>
                        @endforeach 
                        @endif
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">名称 <span class="red">*</span></td>
                <td align="left">
                    <input type="text" class="form-control input-sm" name="title" value="{{$row['title']}}">
                </td>
            </tr>

            <tr>
                <td align="right">计算金额</td>
                <td>
                    <label class="radio-inline"><input type="radio" name="type" value="1" @if($row['id'] > 0 && $row['type'] == 1) checked @endif>是</label>
                    <label class="radio-inline"><input type="radio" name="type" value="0" @if($row['id'] > 0 && $row['type'] == 0) checked @endif>否</label>
                </td>
            </tr>

            <tr>
                <td align="right">类别状态</td>
                <td>
                    <label class="radio-inline"><input type="radio" name="state" id="state1" value="1" @if($row['id'] > 0 && $row['state'] == 1) checked @endif>启用</label>
                    <label class="radio-inline"><input type="radio" name="state" id="state0" value="0" @if($row['id'] > 0 && $row['state'] == 0) checked @endif>停用</label>
                </td>
            </tr>

            <tr>
                <td align="right">排序</td>
                <td align="left">
                <input type="text" class="form-control input-sm" name="sort" value="{{$row['sort']}}">
                </td>
            </tr>

            <tr>
                <td align="right">备注</td>
                <td align="left">
                <textarea class="form-control input-sm" rows="3" cols="20" type="text" name="remark" id="remark">{{$row['remark']}}</textarea>
                </td>
            </tr>

            <tr>
                <td align="right"></td>
                <td align="left">
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <input type="hidden" id="past_parent_id" name="past_parent_id" value="{{$row['parent_id']}}">
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </td>
            </tr>
        </table>
    </form>
</div>