<div id="product-toolbar">
    <form id="search-form" name="mysearch" class="form-inline" method="get">
        @include('searchForm')
    </form>
</div>

<div class="padder">
    <table id="dialog-product">
        <thead>
        <tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="name" data-sortable="true" data-align="left">名称</th>
            <th data-field="spec" data-width="200" data-sortable="true" data-align="left">规格</th>
            <th data-field="id" data-width="60" data-sortable="true" data-align="center">编号</th>
        </tr>
        </thead>
    </table>
</div>

<script>
(function($) {
    var $table = $('#dialog-product');
    $.optionItem = null;
    
    $table.bootstrapTable({
        iconSize:'sm',
        sidePagination: 'server',
        toolbar: "#product-toolbar",
        showColumns: true,
        singleSelect: true,
        clickToSelect: true,
        height: 380,
        pagination: true,
        url: '{{url("product_dialog", $search["query"])}}',
        onLoadSuccess: function(data) {
            for (var i = 0; i < data.rows.length; i++) {
                //if(data.rows[i].id == $.optionItem.product_id) {
                    //$table.bootstrapTable('check', i);
                //}
            }
        },
        onCheck: function(row) {
            var mapping = $.optionItem.mapping;
            $.each(mapping, function(k, v) {
                $.optionItem[k] = row[v];
            });
            $.optionItem.onSelected.call($table, row);
        },
        onUncheck: function(row) {
            var mapping = $.optionItem.mapping;
            $.each(mapping, function(k, v) {
                $.optionItem[k] = '';
            });
            $.optionItem.onSelected.call($table, row);
        }
    });

    var data = {{json_encode($search['forms'])}};
    var search = $('#search-form').searchForm({
        data: data,
        init:function(e) {
            var self = this;
            e.category = function(i) {

                var data = {{json_encode($categorys)}};

                //$.get(app.url('product/category/dialog', {data_type:'json'}),function(res) {
                    var option = '';
                    $.map(data, function(row) {
                        if(row.selected) {
                            option += '<option value="'+row.id+'">'+row.layer_space + row.name+'</option>';
                        }
                    });
                    self._select(option, i);
                //});
            }
        }
    });

    search.find('#search-submit').on('click', function() {
        var params = search.serializeArray();
        $.map(params, function(row) {
            data[row.name] = row.value;
        });
        $table.bootstrapTable('refresh', {
            url:app.url('product/product/dialog', data),
        });
        return false;
    });
})(jQuery);

</script>
