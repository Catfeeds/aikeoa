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

            <a class="btn btn-default" href="javascript:viewBox('show','兑现记录','{{url('promotion/cash/promotion', ['promotion_id' => $form->data['id']])}}');"><i class="fa fa-file-text-o"></i> 兑现记录</a>
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

<script type="text/javascript">
function addCost() {
    var url = "{{url('promotion/cash/create', ['promotion_id'=>$form->data['id']])}}";
    formDialog({
        title: '新建兑现',
        url: url,
        id: 'promotion-cash-form',
        formId: 'promotion-cash-form',
        dialogClass: 'modal-lg',
        onBeforeSend: function(query) {
            var me = this;
            var products = $('#promotion-cash-table').jqGrid('getRowsData');
            if(products.v) {
                query.rows = products.data;
                $.post('{{url("promotion/cash/create")}}', query, function(res) {
                    if(res.status) {
                        $.toastr('success', res.data);
                        $(me).dialog('close');
                    } else {
                        $.toastr('error', res.data);
                    }
                });
            }
            return false;
        }
    });
}
</script>

<div class="panel">
    <table class="table" width="100%">
        <tr>
            <td align="left">

                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>

                @if(Request::action() != 'show') @if($step['edit'])
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

                <a class="btn btn-default" href="javascript:viewDialog({title:'兑现记录',url:'{{url('promotion/cash/promotion', ['promotion_id' => $form->data['id']])}}'});">
                    <i class="fa fa-file-text-o"></i> 兑现记录</a>

                @if(authorise('cash.create'))
                <a class="btn btn-info" href="javascript:addCost();">
                    <i class="fa fa-plus"></i> 新建兑现</a>
                @endif

                <a class="btn btn-default" target="_blank" href="{{url('print',['id'=>$form->data['id']])}}">
                    <i class="icon icon-print"></i> 打印 </a>

                @if($access['cost'])
                <a onclick="viewBox('', '客户本年促销费比(%)', '{{url('cost', ['id'=>$form->data['id']])}}', 'lg');" class="btn btn-default btn-rounded">
                    <i class="icon icon-file"></i> 客户本年促销费比(%)</a>
                @endif @if($access['rise'])
                <a onclick="viewBox('', '促销单品在客户的涨跌情况分析(%)', '{{url('rise', ['id'=>$form->data['id']])}}', 'lg');" class="btn btn-default btn-rounded">
                    <i class="icon icon-file"></i> 促销单品在客户的涨跌情况分析(%)</a>
                @endif

            </td>
        </tr>
    </table>
</div>
@endif

