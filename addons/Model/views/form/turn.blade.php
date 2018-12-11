<form class="form-horizontal" name="myturn" id="myturn" method="post">

    <table class="table table-form">

        <tr>
            <td align="right">办理类型</td>
            <td align="left">
                <label class="i-checks i-checks-sm"><input type="radio" class="next-step-type" name="next_step_type" value="next"><i></i>审批</label>
                @if($step->back == 1 && $step->sn > 1)
                &nbsp;
                &nbsp;
                <label class="i-checks i-checks-sm"><input type="radio" class="next-step-type" name="next_step_type" value="back"><i></i>退回</label>
                @endif
                <!--
                &nbsp;
                &nbsp;
                <label class="i-checks i-checks-sm"><input type="radio" class="next-step-type" name="next_step_type" value="reject"><i></i>拒绝</label>
                -->
            </td>
        </tr>

        <tr>
            <td align="right" width="20%">选择进程</td>
            <td align="left"  width="80%">
                <div id="next-step"></div>
            </td>
        </tr>

        <tbody id="next-step-user"></tbody>

        <!--
        <tr>
            <td align="right">抄送人</td>
            <td align="left">
                <div id="next-notify-users"></div>
            </td>
        </tr>
        -->
        
        <tr>
            <td align="right">审批备注</td>
            <td align="left">
                <textarea class="form-control" rows="3" id="description" name="description"></textarea>
            </td>
        </tr>

        <tr>
            <td align="right">提醒类型</td>
            <td align="left">
                <label class="i-checks i-checks-sm"><input type="checkbox" name="notify_sms" id="notify_sms" value="1"><i></i>短信</label>
            </td>
        </tr>

        <tr>
            <td align="right">提醒内容</td>
            <td align="left">
                <input type="text" class="form-control input-sm" name="notify_text" id="notify_text" value="" readonly="readonly">
            </td>
        </tr>

    </table>
</form>

<script type="text/javascript">

$('.next-step-type').on('click', function() {

    $('#next-step-user').empty();

    if(this.value == 'reject') {
        $('#next-step').html('无');
    } else {
        var myform = $('#{{$table}}_form, #myturn').serialize();
        $.post('{{url("step")}}', myform, function(res) {

            $('#next-step').html(res.tpl);

            // $('#notify_sms').val(res.notify_sms);
            $('#notify_sms').prop('checked', res.notify_sms);
            $('#notify_text').val(res.notify_text);
            //$('#next-notify-users').html(res.notify_users);

            if(res.sn.length) {
                $.each(res.sn, function(k, v) {
                    get_step_user(v, res.concurrent);
                });
            }
        }, 'json');
    }
});

$('#myturn').on('click', '.next-step-id', function() {

    var me = $(this);
    var step_id = me.val();
    var concurrent = me.attr('concurrent');

    if(me.prop('checked') == false) {
        var exist = $('#next-step-user-' + id);
        if(exist.length) {
            exist.remove();
        }
        return;
    }
    get_step_user(step_id, concurrent);
});

function get_step_user(step_id, concurrent)
{
    var myform = $('#{{$table}}_form,#myturn').serialize();

    myform = myform + '&step_id=' + step_id;

    $.get('{{url("user")}}', myform, function(res) {

        var id = 'next-step-user-' + step_id;

        // 不并发直接清空
        if(concurrent == 0) {
            $('#next-step-user').empty();
        } else {
            var exist = $(id);
            if(exist.length) {
                exist.remove();
            }
        }

        // 存在主办人
        if(res.status) {
            $('#next-step-user').append('<tr id="' + id + '">' + res.data.user + '</tr>');
            $('#next-step-user').append('<tr id="next-step-cc">' + res.data.cc + '</tr>');
        }
    });
}
</script>