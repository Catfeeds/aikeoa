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
    action.dialogType = 'layer';

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