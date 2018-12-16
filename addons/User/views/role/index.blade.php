{{$haeder["js"]}}
<div class="panel b-a" id="{{$haeder['table']}}-controller">
    @include('tabs2') 
    @include('haeders')
    <div class="list-jqgrid aike-jqgrid-tree">
        <table id="{{$haeder['table']}}-grid"></table>
    </div>
</div>
<script>
(function($) {

    $.extend($.fn.fmatter, {
        customer: function(cellvalue, options, rowdata) {
            return "<a data-toggle='event' data-action='show' data-id='"+rowdata.id+"' class='option'><i class='icon icon-user'></i> "+ cellvalue +"</a>";
        }
    });
    
    var table = '{{$haeder["table"]}}';
    var config = window[table];
    var action = config.action;
    var search = config.search;

    action.config = function(data) {
        var me = this;
        var url = app.url('user/role/config', {role_id: data.id});
        var index = layer.open({
            title: '<i class="fa fa-columns"></i> ' + me.name +'</a>',
            type: 2,
            move: false,
            content: url,
        });
        layer.full(index);
    }

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
        ExpandColumn : 'text',
        ExpandColClick: true,
        treeGrid: true,
        treedatatype:"json",
        treeGridModel:"adjacency",
        treeReader: {
            parent_id_field:"parent_id",
            level_field:"layer_level",
            leaf_field:"isLeaf",
            expanded_field:"expanded",
            loaded:"loaded"
        },
        ondblClickRow: function(rowIndex) {
            var row = $(this).getRowData(rowIndex);
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
        return top.iframeHeight - list.top - 48;
    }
    $(window).on('resize', function() {
        config.grid.jqGrid('setGridHeight', getPanelHeight());
    });
})(jQuery);

</script>
@include('footers')