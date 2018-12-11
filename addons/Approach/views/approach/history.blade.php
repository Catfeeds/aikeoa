<style>
.modal-body { overflow:hidden; }
.tab-content {
    background-color: #fff;
    padding-top: 2px;
}
.tab-content .table {
    margin-bottom: 0;
}
.nav-tabs {
    margin-top: 10px;
    padding-left: 10px;
}
</style>

    <!--
    <div class="wrapper">
        <form class="form-inline" method="post" id="query-form" name="query-form">
            对账客户: {{Dialog::user('customer', 'customer', '', 0, 0, 200)}}
            <?php $m = date('Y-m-01'); ?>
            &nbsp;&nbsp;开始日期: <input class="form-control input-inline input-sm" data-toggle="date" type="text" name="start_at" id="start_at" placeholder="开始日期" value="{{$m}}">
            &nbsp;&nbsp;结束日期: <input class="form-control input-inline input-sm" data-toggle="date" type="text" id="end_at" placeholder="开始日期" name="end_at" value="{{date('Y-m-d', strtotime("$m +1 month -1 day"))}}">
            <a href="javascript:formQuery();" class="btn btn-sm btn-info"><i class="icon icon-search"></i> 查询</a>
        </form>
    </div>
    -->

<table id="approach-history-table"></table>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#history_goods" title="goods" aria-controls="history_goods" role="tab" data-toggle="tab">单品</a></li>
    <li role="presentation"><a href="#history_shop" title="shop" aria-controls="history_shop" role="tab" data-toggle="tab">店名</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="history_goods">
        <table id="approach-history-goods"></table>
    </div>
    <div role="tabpanel" class="tab-pane" id="history_shop">
        <table id="approach-history-shop"></table>
    </div>
</div>

<script>
var $approach_history = {};
var params = {{json_encode($query)}};
var history_type = 'goods';
(function($) {

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        var type = $(e.target).attr('title');
        history_type = type;
        $approach_history[type].jqGrid('resizeGrid', 0);
    });

    var editCombo = {};
    editCombo.type = [
        {id:'1', text:'多系统多店'},
        {id:'2', text:'单系统多店'}
    ];
    var footerCalculate = function() {
        var data_38 = $(this).getCol('data_38', false, 'sum');
        var data_48 = $(this).getCol('data_48', false, 'sum');
        $(this).footerData('set',{data_38: data_38, data_48: data_48});
    }

    $approach_history.table = $("#approach-history-table");
    $approach_history.table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        editCombo: editCombo,
        url: app.url('approach/approach/history'),
        colModel: [
            {name: "sn", index: 'sn', label: '进店编号', minWidth: 140, align: 'center'},
            {name: "created_at", index: 'created_at', formatter:'date',formatoptions:{srcformat:'u',newformat:'Y-m-d H:i'}, label: '申请时间', width: 140, align: 'center'},
            {name: "type", formatter:'dropdown', index: 'type', label: '进店类型', width: 120, align: 'center'},
            {name: "data_47", index: 'data_47', label: '兑现方式', width: 90, align: 'center'},
            {name: "data_38", index: 'data_38', label: '批复金额', width: 90, align: 'right'},
            {name: "data_48", index: 'data_48', label: '备案金额', width: 90, align: 'right'},
            {name: "id", index: 'id', label: 'ID', width: 60, align: 'center'},
        ],
        rowNum: 1000,
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        height: 160,
        footerrow: true,
        postData: params,
        gridComplete: function() {
            footerCalculate.call(this);
            $(this).jqGrid('setColsWidth');
        },
        loadComplete: function(res) {
            var me = $(this);
        },
        beforeSelectRow:function(rowid, e) {
            var row = $(this).jqGrid('getRowData', rowid);
            $approach_history[history_type].jqGrid('setGridParam', {
                postData: {approach_id: row.id},
            }).trigger('reloadGrid');
        }
    });

    var editCombo = {};
    editCombo.audit = [
        {id:'0', text:'否'},
        {id:'1', text:'是'}
    ];
    editCombo.status = [
        {id:'0', text:'否'},
        {id:'1', text:'是'}
    ];

    $approach_history.goods = $("#approach-history-goods");
    $approach_history.goods.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        editCombo: editCombo,
        url: app.url('approach/approach/history_goods'),
        colModel: [
            {name: "product_name", index: 'product_name', label: '产品名称', minWidth: 220, align: 'left'},
            {name: "barcode", index: 'barcode', label: '条码', width: 180, align: 'center'},
            {name: "offer", index: 'offer', label: '报价', width: 80, align: 'right'},
            {name: "price", index: 'price', label: '售价', width: 80, align: 'right'},
            {name: "audit", formatter:'dropdown', index: 'audit', label: '审核', width: 70, align: 'center'},
            {name: "status", formatter:'dropdown', index: 'status', label: '核销', width: 70, align: 'center'},
        ],
        rowNum: 1000,
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        height: 220,
        footerrow: false,
        postData: params,
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
        },
        loadComplete: function(res) {
            var me = $(this);
        }
    });

    $approach_history.shop = $("#approach-history-shop");
    $approach_history.shop.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('approach/approach/history_shop'),
        colModel: [
            {name: "name", index: 'name', label: '店名', width: 220, align: 'left'},
            {name: "address", index: 'address', label: '详细地址', minWidth: 280, align: 'left'},
        ],
        rowNum: 1000,
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        height: 220,
        footerrow: false,
        postData: params,
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
        },
        loadComplete: function(res) {
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

    $approach_history.table.jqGrid('setGridParam', {
        postData: params,
        page: 1
    }).trigger('reloadGrid');
}

</script>