<div class="panel">

    <form method="post" class="form-horizontal" action="{{url('store')}}" id="myform" name="myform">
        
        <table class="table table-form">
            <tr>
                <td align="right" width="10%">名称</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="name" value="{{$row['name']}}">
                </td>
            </tr>

            <tr>
                <td align="right">appKey</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="appkey" value="{{$row['appkey']}}">
                </td>
            </tr>

            <tr>
                <td align="right">secretKey</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="secret" value="{{$row['secret']}}">
                </td>
            </tr>

            <tr>
                <td align="right">驱动</td>
                <td align="left">
                    <select class="form-control input-sm" name="driver" id="driver">
                        <option value="alidayu" @if($row['driver'] == 'alidayu') selected @endif>阿里大鱼</option>
                        <option value="yunpian" @if($row['driver'] == 'yunpian') selected @endif>云片</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">状态</td>
                <td align="left">
                    <select class="form-control input-sm" name="status" id="status">
                        <option value="1" @if($row['status'] == '1') selected @endif>启用</option>
                        <option value="0" @if($row['status'] == '0') selected @endif>停用</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">排序</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="sort" value="{{$row['sort']}}">
                </td>
            </tr>

            <tr>
                <td align="left" colspan="2">
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </td>
            </tr>

        </table>
    </form>
</div>