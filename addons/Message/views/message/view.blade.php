<div class="panel">

    <div class="panel-heading b-b b-light">
        <h3 class="m-t-xs m-b-xs">
            {{$res['title']}}
        </h3>
        <small class="text-muted">
            收件人: {{get_user($res['to_user_id'],'nickname')}}
            &nbsp;&nbsp;发布时间: @datetime($res['add_time'])
            &nbsp;&nbsp;希望回复时间: @datetime($res['hope_reply_time'])
        </small>
    </div>

    <div class="panel-body">
        {{$res['content']}}
        @include('attachment/view')
    </div>

@if($res['reply_time'] == 0)

    @if($reply == 1)
    <div class="padder">
        <form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
            <table class="table b-a">
                <tr>
                    <th align="left">回复消息</th>
                </tr>
                <tr>
                    <td align="left">
                        <?php $attachList['queue'] = $attachList['reply']; ?>
                        @include('attachment/add')
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <label class="checkbox-inline"><input name="sms" type="checkbox" value="true" checked> 短信提醒</label>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        {{ueditor('content')}}
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <input type="hidden" id="id" name="id" value="{{$res['id']}}">
                        @if($reply == 1 && $res['reply_time'] == 0)
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                        @endif
                    </td>
                </tr>
            </table>
        </form>
    </div>
    @endif

@else

    <div class="panel-heading b-b b-light">
        <h4 class="m-t-xs m-b-xs">回复信件</h4>
        <small class="text-muted">
            发件人: {{get_user($res['to_user_id'],'nickname')}}
            &nbsp;&nbsp;回复时间: @datetime($res['reply_time'])
        </small>
    </div>

    <div class="panel-body">
        {{$res['reply_text']}}

        {{'';$attachList['view'] = $attachList['reply']}}
        @include('attachment/view')
    </div>

@endif

</div>

<div class="panel">
    <div class="panel-body">
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    </div>
</div>