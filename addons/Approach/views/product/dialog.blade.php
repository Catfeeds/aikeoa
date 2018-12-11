<div id="product-toolbar">
    <form id="search-form" name="mysearch" class="form-inline" method="get">
        @include('searchForm')
    </form>
</div>

<div class="padder">
    <table id="dialog-product" data-formatter="stateFormatter" data-toolbar="#product-toolbar" data-show-columns="true" data-single-select="true" data-click-to-select="true" data-method="post" data-url="{{url('product/product/dialog')}}" data-height="380" data-side-pagination="server" data-pagination="true">
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
        onLoadSuccess: function(data) {

            var mapping = $.optionItem.mapping;
            var id = $.optionItem.id;

            var z = mapping[id];

            var val = $('#' + id).val();

            for (var i = 0; i < data.rows.length; i++) {
                if(data.rows[i][z] == val) {
                    $table.bootstrapTable('check', i);
                }
            }
        },
        onCheck: function(row) {
            var mapping = $.optionItem.mapping;
            $.each(mapping, function(k, v) {
                $('#' + k).val(row[v] || '');
                $('#' + k).text(row[v] || '');
            });
            $.optionItem.onSelected.call($table, row);
        },
        onUncheck: function(row) {
            var mapping = $.optionItem.mapping;
            $.each(mapping, function(k, v) {
                $('#' + k).val('');
                $('#' + k).text('');
            });
            $.optionItem.onSelected.call($table, row);
        }
    });

    var data = {{json_encode($search['forms'])}};
    var search = $('#search-form').searchForm({
        data:data,
        init:function(e) {
            var self = this;
            e.category = function(i) {
                $.get(app.url('product/category/dialog', {data_type:'json'}),function(res) {
                    var option = '';
                    $.map(res.rows, function(row) {
                        option += '<option value="'+row.id+'">'+row.layer_space + row.name+'</option>';
                    });
                    self._select(option, i);
                });
            }
        }
    });

    search.find('#search-submit').on('click', function() {
        var params = search.serializeArray();
        //delete data['field'];
        //delete data['condition'];
        //delete data['search'];
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
