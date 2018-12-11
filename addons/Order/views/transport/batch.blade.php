<div class="panel">
    <div class="wrapper">

        <form class="form-inline" method="post" id="query-form" name="query-form" onsubmit="formQuery();return false;">
            产品: {{Dialog::user('product', 'product_id', '', 0, 0, 260)}}
            &nbsp;&nbsp;生产批号: <input class="form-control input-inline input-sm" type="text" name="batch" id="batch" placeholder="输入生产批号">
            <a href="javascript:formQuery();" class="btn btn-sm btn-info"><i class="icon icon-search"></i> 查询</a>
        </form>

    </div>

    <div class="list-jqgrid">
        <table id="account-single"></table>
    </div>
</div>

<script>
var $table = null;
var params = {};

(function($) {

    $table = $("#account-single");
    var model = [
        {name: "invoice_company", index: 'invoice_company', label: '客户单位', width: 220, align: 'left'},
        {name: "number", index: 'number', label: '订单号', width: 100, align: 'center'},
        {name: "batch_number", index: 'batch_number', label: '生产批号', width: 80, align: 'center'},
        {name: "name", index: 'name', label: '产品名称', width: 280, align: 'left'},
        {name: "fact_amount", index: 'fact_amount', label: '发货数量', width: 80, align: 'right'}
    ];

    $table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('order/transport/batch'),
        colModel: model,
        rowNum: 1000,
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        height: getPanelHeight(),
        footerrow: false,
        postData: params,
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
        },
        loadComplete: function(res) {
            var me = $(this);
            me.jqGrid('initPagination', res);
        }
    });

})(jQuery);


function formQuery()
{
    var query_form = $('#query-form');
    var query = query_form.serializeArray();
    for (var i = 0; i < query.length; i++) {
        params[query[i].name] = query[i].value;
    }

    $table.jqGrid('setGridParam', {
        postData: params,
        page: 1
    }).trigger('reloadGrid');
}

function getPanelHeight() {
    var list = $('.list-jqgrid').position();
    return top.iframeHeight - list.top - 45;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    // 框架改变大小时设置Panel高度
    $table.jqGrid('setPanelHeight', getPanelHeight());
    // resize jqgrid大小
    $table.jqGrid('resizeGrid');
}

</script>