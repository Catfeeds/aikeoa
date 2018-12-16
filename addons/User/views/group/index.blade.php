{{$haeder["js"]}}
<div class="panel b-a" id="{{$haeder['table']}}-controller">
    @include('tabs2') 
    @include('haeders')
    <div class="list-jqgrid">
        <table id="{{$haeder['table']}}-grid"></table>
        <div id="{{$haeder['table']}}-grid-page"></div>
    </div>
</div>
<script>
(function($) {

    var table = '{{$haeder["table"]}}';
    var config = window[table];
    var action = config.action;
    var search = config.search;

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
            action.edit(row);
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