<style>
.list-jqgrid .ui-jqgrid .ui-jqgrid-sdiv {
    border-top: 1px solid #ddd !important;
}
</style>

<div class="panel">
    <div class="wrapper-sm">
        <form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">
            @include('searchForm')
        </form>
    </div>

    <div class="list-jqgrid">
        <table id="jqgrid"></table>
    </div>
</div>

<script>
var t = null;
var params = {};

(function($) {
    var models = JSON.parse('{{json_encode($models)}}');
    for (let i = 0; i < models.length; i++) {
        var model = models[i];
        if(model.name == 'data_5') {
            model.label = model.label + ' <i class="hinted fa fa-question-circle" title="计算: 客户订单成品汇总 - 成品目前库存"></i>';
        }
        if(model.name == 'data_51') {
            model.label = model.label + ' <i class="hinted fa fa-question-circle" title="计算: 原料目前库存 - 目前生产订单的包装需求"></i>';
        }
        models[i] = model;
    }

    var searchData = '{{json_encode($search["forms"])}}';
    var searchForm = $('#search-form');
    searchForm.searchForm({
        data: JSON.parse(searchData),
        init: function(e) {
            var self = this;
            e.product_category = function(i) {
                var rows = [{id: '', name: ' - '}];
                 $.post(app.url('product/category/dialog', {type: 2}), function(res) {
                    $.map(res.rows, function(row) {
                        rows.push({id: row.id, name: row.layer_space + row.name});
                    });
                    self._select(rows, i);
                });
            }
        }
    });
    searchForm.find('#search-submit').on('click', function() {
        var query = searchForm.serializeArray();
        $.map(query, function(row) {
            if(row.nam == 'search_0_0') {
                query[row.name] = row.value;
            }
        });
        t.jqGrid('setGridParam', {
            postData: query,
            page: 1
        }).trigger('reloadGrid');
        return false;
    });

    var footerCalculate = function() {
        var quantity = $(this).getCol('quantity', false, 'sum');
        $(this).footerData('set',{category_name:'合计:', quantity: quantity});
    }

    t = $("#jqgrid");

    t.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('order/plan/purchase'),
        colModel: models,
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
            // me.jqGrid('initPagination', res);
        }
    });

    t.jqGrid('setGroupHeaders', {
        useColSpanStyle: true, //没有表头的列是否与表头列位置的空单元格合并
        groupHeaders: [{
            startColumnName: 'data_7',
            numberOfColumns: 3,
            titleText: '三日生产计划需求'
        },{
            startColumnName: 'data_3',
            numberOfColumns: 2,
            titleText: '计算参数'
        }]
    });

})(jQuery);

function formQuery()
{
    var query_form = $('#query-form');
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
    var list = $('.list-jqgrid').position();
    return top.iframeHeight - list.top - 106;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    // 框架改变大小时设置Panel高度
    t.jqGrid('setPanelHeight', getPanelHeight());
    // resize jqgrid大小
    t.jqGrid('resizeGrid');
}

</script>