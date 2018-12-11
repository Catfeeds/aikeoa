<div class="panel">

    <form method="post" class="form-horizontal" action="{{url()}}" id="myform" name="myform">

        <table class="table table-form">

            <tr>
                <td align="right" width="10%">名称</td>
                <td align="left">
                    <input type="text" id="title" name="name" class="form-control input-sm" value="{{$row['name']}}">
                </td>
            </tr>

            <tr>
                <td align="right">排序</td>
                <td align="left">
                    <input type="text" id="sort" name="sort" class="form-control input-sm" value="{{$row['sort']}}">
                </td>
            </tr>

            <tr>
                <td align="right"></td>
                <td align="left">
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </td>
            </tr>
        </table>
    </form>
</div>
