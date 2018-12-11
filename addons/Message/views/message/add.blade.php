<div class="panel">

<form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table table-form">
            <tr>
                <td width="10%" align="right">主题</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" id="title" name="title" value="{{$res['title']}}">
                </td>
            </tr>
            <tr>
                <td align="right">要求回复时间</td>
                <td align="left">
                    <input type="text" id="hope_reply_time" name="hope_reply_time" value="@datetime($res['hope_reply_time'])" data-toggle="datetime" class="form-control input-sm" readonly>
                </td>
            </tr>
            <tr>
                <td align="right">收件人</td>
                <td align="left">
                    {{Dialog::user('user','to_user_id', $res['to_user_id'], 0, 0)}}
                </td>
            </tr>
            <tr>
                <td align="right">附件列表</td>
                <td align="left">
                    @include('attachment/add')
                </td>
            </tr>
            <tr>
                <td align="right">通知提醒</td>
                <td align="left">
                    <label class="checkbox-inline"><input name="sms" type="checkbox" value="true" checked> 短信</label>
                </td>
            </tr>
            <tr>
                <td align="right">详细内容</td>
                <td align="left">
                    {{ueditor('content', $res['content'])}}
                </td>
            </tr>
            <tr>
                <td align="right"></td>
                <td align="left">
                    <input type="hidden" name="id" value="{{$res['id']}}" />
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </td>
            </tr>
        </table>
    </div>
    </form>
</div>