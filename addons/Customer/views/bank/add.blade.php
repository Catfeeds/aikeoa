<div class="panel">

<form method="post" action="{{url()}}" id="myform" name="myform">
    <table class="table table-form">
        <tr>
            <td align="right" width="10%">税票单位</td>
            <td>
                <input type="text" id="tax_name" name="tax_name" value="{{$row['tax_name']}}" class="form-control input-sm">
            </td>
        </tr>
        <tr>
            <td align="right">税号</td>
            <td>
                <input type="text" id="tax_number" name="tax_number" value="{{$row['tax_number']}}" class="form-control input-sm">
            </td>
        </tr>
        <tr>
            <td align="right">状态</td>
            <td>
                <select class="form-control input-sm" name="status" id="status">
                    @if($status)
                    @foreach($status as $k => $v)
                        <option value="{{$k}}" @if($row['status'] == $k) selected @endif>{{$v}}</option>
                    @endforeach 
                    @endif
                </select>
            </td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <input type="hidden" name="id" value="{{$gets['id']}}">
                <input type="hidden" name="customer_id" value="{{$gets['customer_id']}}">
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
            </td>
        </tr>
    </table>
</form>

</div>