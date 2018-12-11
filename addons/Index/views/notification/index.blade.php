<div class="panel">

    @include('tabs1')

    <div class="wrapper">

        <form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

            <div class="pull-right">
                <a class="btn btn-sm btn-default" href="javascript:exportLink();"> 导出</a>
                <a class="btn btn-sm btn-danger" href="javascript:deleteLink('delete');"><i class="icon icon-trash"></i> 删除</a>
            </div>

            @if($search['query']['status'] == 1)
                <a class="btn btn-sm btn-info" href="javascript:buttonLink('status', '确定要标记未读吗', 1);"><i class="icon icon-remove"></i> 标记未读</a>
            @else
            <a class="btn btn-sm btn-info" href="javascript:buttonLink('status', '确定要标记未读吗', 0);"><i class="icon icon-ok"></i> 标记已读</a>
            @endif

            @include('searchForm1')

        </form>
    </div>

    <div class="list-jqgrid">
        <table id="jqgrid" class=""></table>
        <div id="jqgrid-page"></div>
    </div>
        
</div>

<script>
template.helper('isNumber', function(content) {
    return !isNaN(content);
});

var searchData = {{json_encode($search)}};
var html = template('search-form-tpl', searchData);
$('#search-form').append(html);

var $table = null;
var params = {{json_encode($search['query'])}};

(function($) {
    
    var data = {{json_encode($search['forms'])}};

    var search = $('#search-form').searchForm({
        data: data,
        init: function(e) {
            var self = this;
        }
    });

    search.find('#search-submit').on('click', function() {

        var query = search.serializeArray();
        $.map(query, function(row) {
            params[row.name] = row.value;
        });

        $table.jqGrid('setGridParam', {
            postData: params,
            page: 1
        }).trigger('reloadGrid');

        return false;
    });

    $table = $("#jqgrid");

    var model = {{json_encode($columns)}};

    $table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'GET',
        url: '{{url()}}',
        colModel: model,
        rowNum: 25,
        autowidth: true,
        multiselect: true,
        viewrecords: true,
        rownumbers: false,
        width: '100%',
        height: getPanelHeight(),
        footerrow: false,
        postData: params,
        pager: '#jqgrid-page',
        ondblClickRow: function(rowid) {
            var row = $(this).getRowData(rowid);
            actionLink({action:'view', id: row.id});
        },
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
        }
    });

})(jQuery);

function exportLink(row) {
    $table.jqGrid('exportGrid');
}

function actionLink(row) {
    if(row.action == 'view') {
        viewBox('show','查看', app.url('index/notification/show', {id: row.id}));
    }
}

function deleteLink(action, title) {

    var content = title || '确定要删除吗？';

    var selections = $table.jqGrid('getSelections');
    var query = [];
    $.each(selections, function(i, selection) {
        query.push(selection.id);
    });

    if(query.length) {
        $.messager.confirm('操作确认', content, function() {
            $.post(app.url('index/notification/' + action), {id: query}, function(res) {
                if(res.status) {
                    $.toastr('success', '删除成功。', '提示');
                    $table.jqGrid('setGridParam', {
                        postData: params,
                        page: 1
                    }).trigger('reloadGrid');
                }
            });
        });

    } else {
        $.toastr('error', '最少选择一行记录。', '错误');
    }
}

function tabs(id) {

    var tab = '{{$tabs["name"]}}';
    params[tab] = id;

    $('.tabs-box').find('li').removeClass('active');
    $('#tab-' + id).addClass('active');

    $table.jqGrid('setGridParam', {
        postData: params,
        page: 1
    }).trigger('reloadGrid');
}

function getPanelHeight() {
    var list = $('.list-jqgrid').position();
    return top.iframeHeight - list.top - 95;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    $table.jqGrid('setGridHeight', getPanelHeight());
}
</script>