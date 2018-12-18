{{$header["js"]}}
<div class="panel b-a" id="{{$header['table']}}-controller">
    @include('tabs2')
    @include('headers')
    <div class="list-jqgrid">
        <table id="{{$header['table']}}-grid"></table>
        <div id="{{$header['table']}}-grid-page"></div>
    </div>
</div>
<script>
(function($) {

    var table = '{{$header["table"]}}';
    var config = window[table];
    var action = config.action;
    var search = config.search;

    var statusAction = function(type) {
        var grid = config.grid;
        var selections = grid.jqGrid('getSelections');
        var ids = [];
        $.each(selections, function(i, selection) {
            ids.push(selection.id);
        });
        if(ids.length > 0) {
            $.post('{{url("status")}}', {type: type, id: ids}, function(res) {
                if(res.status) {
                    $.toastr('success', res.data);
                    grid.trigger('reloadGrid');
                } else {
                    $.toastr('error', res.data);
                }
            },'json');
        } else {
            $.toastr('error', '最少选择一行记录。');
        }
    }

    // 标记已读
    action.read = function() {
        statusAction('read');
    };
    // 标记未读
    action.unread = function() {
        statusAction('unread');
    };

    // 自定义搜索方法
    search.searchInit = function(e) {
        var self = this;
    }
    config.grid = $('#' + table + '-grid').jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: '{{url()}}',
        colModel: config.cols,
        rowNum: 25,
        autowidth: true,
        multiselect: true,
        viewrecords: true,
        rownumbers: false,
        width: '100%',
        height: getPanelHeight(),
        footerrow: false,
        postData: search.advanced.query,
        pager: '#' + table + '-grid-page',
        ondblClickRow: function(rowid) {
            var row = $(this).getRowData(rowid);
            action.show(row);
        },
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
        }
    }).on('click', '[data-toggle="event"]', function() {
        var data = $(this).data();
        action[data.action](data);
    });
    function getPanelHeight() {
        var list = $('.list-jqgrid').position();
        return top.iframeHeight - list.top - 98;
    }
    $(window).on('resize', function() {
        config.grid.jqGrid('setGridHeight', getPanelHeight());
    });
})(jQuery);
</script>
@include('footers')