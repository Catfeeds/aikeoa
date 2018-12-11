@if(Input::get('client') == 'app')

<div class="panel">
    <table class="table" width="100%">
    <tr>
        <td align="left">

            @if(Request::action() != 'show')

                @if($step['edit'])
                <a class="btn btn-info" href="javascript:;" onclick="turn('{{$step['key']}}');">
                    <i class="icon icon-ok-sign"></i> @if($step['number'] == 1) 提交 @else 审核 @endif
                </a>
                @endif

                <a class="btn btn-dark" href="javascript:;" onclick="draft('{{$step['key']}}');">
                    <i class="icon icon-coffee-cup"></i> 保存草稿
                </a>

            @endif

            <a class="btn btn-default" href="javascript:;" onclick="app.turnlog('{{$step['key']}}');">
                <i class="icon icon-tick"></i> 审核记录
            </a>

            <a class="btn btn-default" href="javascript:viewBox('show','客户进店记录','{{url('approach/approach/history', ['customer_id' => $form->data['customer_id']])}}', 'lg');"><i class="fa fa-file-text-o"></i> 进店历史</a>
            
        </td>
    </tr>
    </table>
</div>

<script type="text/javascript">

var donf   = null;
var doapp  = null;
var dopage = null;

window.onDeviceOneLoaded = function() {
    donf   = sm("do_Notification");
    doapp  = sm("do_App");
    dopage = sm("do_Page");
}

function turn(key) {

    var url = app.url('model/process/turn', {key:key});
    $('#process-turn').__dialog({
        title: '单据审批',
        url: url,
        buttons: [{
            text: "提交",
            'class': "btn-success",
            click: function() {
                var formData = $('#myform,#myturn').serialize();
                $.post(app.url('model/process/turn'), formData, function(res) {
                    if(res.status) {

                        donf.toast('审批成功。');
                        doapp.closePage('reload');

                    } else {
                        donf.alert(res.data);
                    }
                },'json');
            }
        },{
            text: "取消",
            'class': "btn-default",
            click: function() {
                $(this).dialog("close");
            }
        }]
    });
}
function draft(key) {
    var formData = $('#myform').serialize();
    $.post(app.url('model/process/draft', {key: key}), formData, function(res) {
        if(res.status) {
            donf.toast('保存成功。');
        } else {
            donf.toast('保存失败。');
        }
    },'json');
}
</script>

@else

<div class="panel">
    <table class="table" width="100%">
    <tr>
        <td align="left">

            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        
            @if(Request::action() != 'show')

                @if($step['edit'])
                <a class="btn btn-{{$step['color']}}" href="javascript:;" onclick="app.turn('{{$step['key']}}');">
                    <i class="icon icon-ok-sign"></i> @if($step['number'] == 1) 提交 @else 审核 @endif
                </a>
                @endif

                <a class="btn btn-dark" href="javascript:;" onclick="app.draft('{{$step['key']}}');">
                    <i class="icon icon-coffee-cup"></i> 保存草稿
                </a>

            @endif

            <a class="btn btn-default" href="javascript:;" onclick="app.turnlog('{{$step['key']}}');">
                <i class="icon icon-tick"></i> 审核记录
            </a>

            <a class="btn btn-default" href="javascript:viewBox('show','客户进店记录','{{url('approach/approach/history', ['customer_id' => $form->data['customer_id']])}}', 'lg');"><i class="fa fa-file-text-o"></i> 进店历史</a>

            <a class="btn btn-default" target="_blank" href="{{url('print',['id'=>$form->data['id']])}}"><i class="icon icon-print"></i> 打印 </a>
            
        </td>
    </tr>
    </table>
</div>

@endif