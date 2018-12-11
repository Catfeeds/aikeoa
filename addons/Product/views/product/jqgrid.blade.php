<div class="wrapper-sm" style="padding-bottom:0;">

    <div id="dialog-product-toolbar">
        <form id="search-form" name="mysearch" class="form-inline" method="get">
            @include('searchForm')
        </form>
    </div>

    <div class="m-t">
        <table id="dialog-product-{{$gets['jqgrid']}}"></table>
        <div id="dialog-product-page"></div>
    </div>
</div>

<script>
(function($) {
    var params = {{json_encode($gets)}};
    var $table = $("#dialog-product-{{$gets['jqgrid']}}");
    var jqgrid = "{{$gets['jqgrid']}}";

    var model = [
        {name: "text", index: 'product.name', label: '名称', minWidth: 260, align: 'left'},
        {name: "category_name", index: 'product_category.name', label: '类别', width: 120, align: 'center'},
        {name: "stock_number", index: 'product.stock_number', label: '存货编码', width: 80, align: 'center'},
        {name: "status", formatter:statusFmatter, index: 'product.status', label: '状态', width: 60, align: 'center'},
        {name: "id", index: 'product.id', label: 'ID', width: 60, align: 'center'}
    ];

    function statusFmatter(cellvalue, options, rowObject) {
        return cellvalue == '1' ? '<span>启用</span>' : '<span class="red">停用</span>';
    }

    $table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('product/product/dialog_jqgrid'),
        colModel: model,
        rowNum: 500,
        multiselect: true,
        viewrecords: true,
        rownumbers: false,
        height: 340,
        footerrow: false,
        postData: params,
        pager: '#dialog-product-page'
    });

    var data = {{json_encode($search['forms'])}};
    var search = $('#search-form').searchForm({
        data: data,
        init:function(e) {
            var self = this;
            e.status = function(i) {
                var option = '<option value="1">启用</option><option value="0">停用</option>';
                self._select(option, i);
            }
            e.category = function(i) {
                $.get(app.url('product/category/dialog', {data_type:'json'}),function(res) {
                    var option = '';
                    $.map(res.rows, function(row) {
                        option += '<option value="'+row.id+'">'+row.layer_space + row.name+'</option>';
                    });
                    self._select(option, i);
                });
            }
            e.supplier = function(i) {
                $.post(app.url('supplier/supplier/dialog', {limit:10000}), function(res) {
                    var option = '';
                    $.map(res.data, function(row) {
                        option += '<option value="'+row.id+'">'+ row.nickname +'</option>';
                    });
                    self._select(option, i);
                });
            }
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
})(jQuery);

</script>
