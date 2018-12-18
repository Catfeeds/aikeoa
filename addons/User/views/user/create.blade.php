<div class="form-panel">
    <div class="bg-light lter b-b wrapper-sm form-panel-header">
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-dark" id="{{$header['table']}}-form-close"><i class="fa fa-times"></i> 关闭</button>
        </div>
        <button type="button" class="btn btn-sm btn-success" id="{{$header['table']}}-form-submit"><i class="fa fa-check"></i> 提交</button>
    </div>
    <div class="form-panel-body">
        <div class="panel">
            <form class="form-horizontal form-controller" method="post" action="{{url()}}" id="user-form-edit" name="user_form_edit">
                {{$header['tpl']}}
            </form>
        </div>
    </div>
</div>

<script>
var table = '{{$header["table"]}}';
function getAuthSecret(userId) {
    $.messager.confirm('安全密钥', '确定要更新安全密钥。', function() {
        $.post('{{url("user/profile/secret")}}',{id:userId}, function(res) {
            $("#secret").html(res.data);
            $('#user_auth_secret_value').val(res.data);
        },'json');
    });
}

$(function() {
    var text = $('#user_auth_secret').text();
    $('#user_auth_secret').html('<input type="hidden" id="user_auth_secret_value" name="user[auth_secret]" value="'+ text +'"><code id="secret">' + text + '</code><a class="btn btn-info btn-xs" onclick="getAuthSecret();" href="javascript:;">更新</a>');

    ajaxSubmit(table, function(res) {
        if (res.status) {
            layerFrameClose();
            parent.$('#' + table + '-grid').trigger('reloadGrid');
            parent.$.toastr('success', res.data);
        } else {
            $.toastr('error', res.data);
        }
    });
});
</script>