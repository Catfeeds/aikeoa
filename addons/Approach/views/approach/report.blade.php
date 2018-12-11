<div class="panel">

    <div class="wrapper">

        <form id="queryform" class="form-inline" name="mysearch" action="{{url()}}" method="get">
        @include('searchForm')
        </form>
        <script type="text/javascript">
        $(function() {
            $('#queryform').searchForm({
                data: {{json_encode($search['forms'])}},
                init: function(e) {
                    var me = this;
                    e.post = function(i) {
                        me._select({{search_select($types)}}, i);
                    }
                }
            });
        });
        </script>

    </div>

    <div id="jqgrid-editor-container" class="wrapper-sm">
        <table id="grid-table"></table>
    </div>

    <div class="panel-footer">
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    </div>

</div>

<script>

var t = null;
var params = {{json_encode($query)}};

(function($) {

    t = $("#grid-table");
    var model = [
        {name: "circle_name", index: 'circle_name', label: '客户圈', width: 180, align: 'center'},
        {name: "a", index: 'a', formatter:'integer', formatoptions: {decimalPlaces:2}, label: '申请金额', width: 220, align: 'right'},
        {name: "b", index: 'b', formatter:'integer', formatoptions: {decimalPlaces:2}, label: '批复金额', width: 220, align: 'right'},
    ];

    var footerCalculate = function() {
        var a = $(this).getCol('a', false, 'sum');
        var b = $(this).getCol('b', false, 'sum');
        $(this).footerData('set',{a: a, b: b});
    }

    t.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('approach/approach/report'),
        colModel: model,
        cellEdit: true,
        cellurl: '',
        cellsubmit: 'clientArray',
        rowNum: 1000,
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        height: getPanelHeight(),
        footerrow: true,
        postData: params,
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
            footerCalculate.call(this);
        },
        loadComplete: function(res) {
            var me = $(this);
        }
    });

})(jQuery);

function formQuery()
{
    var query_form = $('#queryform');
    var query = query_form.serializeArray();
    for (var i = 0; i < query.length; i++) {
        params[query[i].name] = query[i].value;
    }

    t.jqGrid('setGridParam', {
        postData: params,
        page: 1
    }).trigger('reloadGrid');
}

function getPanelHeight() {
    var list = $('#jqgrid-editor-container').position();
    return top.iframeHeight - list.top - 145;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    // 框架改变大小时设置Panel高度
    t.jqGrid('setPanelHeight', getPanelHeight());
    // resize jqgrid大小
    t.jqGrid('resizeGrid');
}

</script>