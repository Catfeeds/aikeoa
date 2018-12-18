<div class="form-panel">
    <div class="bg-light lter b-b wrapper-sm form-panel-header">
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-dark" id="{{$header['table']}}-form-close"><i class="fa fa-times"></i> 关闭</button>
        </div>
        <div class="m-t-xs font-thin text-base"><i class="fa fa-file-text-o"></i> 详情</div>
    </div>
    <div class="form-panel-body">
        <div class="panel">
            <div class="show-controller">
                {{$header['tpl']}}
            </div>
        </div>
    </div>
</div>

<script>
var table = '{{$header["table"]}}';
var secret_qrcode = '{{$header["row"]["secret_qrcode"]}}';
var secret_text   = '{{$header["row"]["auth_secret"]}}';

function getAuthSecret() {
    $.messager.alert('二次验证二维码','<div align="center"><img src="' + secret_qrcode + '"><div><code>' + secret_text + '</code></div></div>');
}

$(function() {
    var text = $('#user_auth_secret').text();
    if (secret_text) {
        $('#user_auth_secret').html('<strong>' + secret_text + '</strong><a href="javascript:;" onclick="getAuthSecret();"> <i class="icon icon-qrcode"></i></a>');
    }
    $('#' + table + '-form-close').on('click', function() {
        layerFrameClose();
    });
});

</script>