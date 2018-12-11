<form class="form-horizontal" name="myturn" id="myturn" method="post">

    <table class="table table-form">

        <tr>
            <td align="right">办理类型</td>
            <td align="left">
                <label class="i-checks i-checks-sm"><input type="radio" class="next-step-type" name="next_step_type" value="next"><i></i>审批</label>
                @if($row['id'] > 1)
                &nbsp;
                &nbsp;
                <label class="i-checks i-checks-sm"><input type="radio" class="next-step-type" name="next_step_type" value="back"><i></i>退回</label>
                @endif

                &nbsp;
                &nbsp;
                <label class="i-checks i-checks-sm"><input type="radio" class="next-step-type" name="next_step_type" value="end"><i></i>结束</label>
            </td>
        </tr>

        <tbody id="next-step-user"></tbody>
        
        <tr>
            <td align="right">审批备注</td>
            <td align="left">
                <textarea class="form-control" rows="3" id="description" name="description"></textarea>
            </td>
        </tr>

        <tr>
            <td align="right">提醒类型</td>
            <td align="left">
                <label class="i-checks i-checks-sm"><input type="checkbox" name="notify_sms" id="notify_sms" value=""><i></i>短信</label>
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

    if(this.value == 'end') {
        $('#next-step-user').empty('无');
    } else {
        var myform = $('#{{$table}}_form, #myturn').serialize();
        $.post('{{url("freestep")}}', myform, function(res) {

            $('#next-step-user').html(res.tpl);
            $('#notify_text').val(res.notify_text);
            $('#next-notify-users').html(res.notify_users);

        }, 'json');
    }
});

</script>