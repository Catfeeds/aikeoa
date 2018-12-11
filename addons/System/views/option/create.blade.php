<div class="panel">

    <form method="post" class="form-horizontal" action="{{url()}}" id="myform" name="myform">
        
        <table class="table table-form">
            <tr>
                <td align="right" width="100">名称</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" id="name" name="name" value="{{$row['name']}}">
                </td>
            </tr>

            <tr>
                <td align="right">值</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" id="value" name="value" value="{{$row['value']}}">
                </td>
            </tr>

            <tr>
                <td align="right"></td>
                <td>
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <input type="hidden" name="parent_id" value="{{$parent_id}}">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                </td>
            </tr>

        </table>
    </form>
</div>