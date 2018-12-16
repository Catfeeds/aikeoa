<!--
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

    $.optionItem = {
        row: {},
        mapping: {}
    };

    // 映射字段处理
    var mappingField = function(row) {
        var item = {};
        $.each($.optionItem.mapping, function(k, v) {
            item[k] = row[v];
        });
        $.optionItem.row = item;
        return item;
    }
    // 清除映射的字段
    var mappingFieldClear = function(row) {
        var item = {};
        $.each($.optionItem.mapping, function(k, v) {
            item[k] = '';
        });
        $.optionItem.row = item;
        return item;
    }
    
    $table.bootstrapTable({
        iconSize:'sm',
        sidePagination: 'server',
        toolbar: "#product-toolbar",
        showColumns: true,
        singleSelect: false,
        method: 'post',
        clickToSelect: true,
        height: 380,
        pagination: true,
        url: '{{url("product/product/dialog")}}',
        onLoadSuccess: function(data) {
            for (var i = 0; i < data.rows.length; i++) {
                if(data.rows[i].id == $.optionItem.row['product_id']) {
                    $table.bootstrapTable('check', i);
                }
            }
        },
        onCheckAll: function(rows) {
            var items = [];
            $.map(rows, function(row) {
                var item = mappingField(row);
                items.push(item);
            });
            $.optionItem.onSelecteds.call($table, items, rows);
        },
        onCheck: function(row) {
            var item = mappingField(row);
            $.optionItem.onSelected.call($table, item, row);
        },
        onUncheck: function(row) {
            var item = mappingFieldClear(row);
            $.optionItem.onSelected.call($table, item, row);
        }
    });

    var data = {{json_encode($search['forms'])}};
    var search = $('#search-form').searchForm({
        data: data,
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
-->

<div class="wrapper" style="padding-bottom:0;">

    <div id="dialog-product-toolbar">
        <form id="dialog-product-search-form" name="dialog_product_search_form" class="form-inline" method="get">
            @include('searchForm')
        </form>
    </div>

    <div class="m-t">
        <table id="dialog-product" class="table-condensed"></table>
        <div id="dialog-product-page"></div>
    </div>

</div>

<script>
(function($) {
    window.productDialog = {};
    var params = {{json_encode($gets)}};
    var $table = $("#dialog-product");

    var model = [
        {name: "name", index:'name', label: '名称', width: 220, align: 'left'},
        {name: "spec", index:'spec', label: '规格', minWidth: 180, align: 'center'},
        {name: "id", index:'id', label: 'ID', width: 60, align: 'center'}
    ];

    $table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('product/product/dialog'),
        colModel: model,
        rowNum: 25,
        multiboxonly: params.multi == 0 ? true : false,
        multiselect: true,
        viewrecords: true,
        rownumbers: false,
        height: 340,
        footerrow: false,
        postData: params,
        pager: '#dialog-product-page',
        gridComplete: function() {
            // 单选时禁用全选按钮
            if(params.multi == 0) {
                $("#cb_" + this.p.id).prop('disabled', true);
            }
            $(this).jqGrid('setColsWidth');
        },
        loadComplete: function(res) {
            
            var me = $(this);
            me.jqGrid('initPagination', res);

            if($.isFunction(window.productDialog.setSelecteds)) {
                window.productDialog.setSelecteds.call($table);
            } else {
                // 设置默认选中
                window.productDialog.setDefaultSelecteds();
            }

        },
        // 双击选中
        ondblClickRow: function(id) {
            if(params.multi == 1) {
                $table.jqGrid('setSelection', id);
            }
            if($.isFunction(window.productDialog.getSelecteds)) {
                window.productDialog.getSelecteds.call($table);
            } else {
                window.productDialog.getDefaultSelecteds();
            }
        },
    });

    window.productDialog.setDefaultSelecteds = function(res) {
        var ids = $('#'+params.id).val();
        ids = ids.split(',');
        $.each(ids, function(k, v) {
            if(v) {
                $table.jqGrid('setSelection', v);
            }
        });
    }

    window.productDialog.getDefaultSelecteds = function() {
        var rows = $table.jqGrid('getSelections');
        if(params.multi == 0) {
            if(rows.length > 1) {
                $.toastr('error', '只能选择一项。');
                return false;
            }
        }

        var id = [], text = [];
        for (var i = 0; i < rows.length; i++) {
            id.push(rows[i].id);
            text.push(rows[i].text);
        }

        // 会写数据
        $('#'+params.id).val(id.join(','));
        $('#'+params.id+'_text').text(text.join(','));

        // 关闭窗口
        $('#modal-dialog-user').dialog("close");
        return true;
    }

    var data = {{json_encode($search['forms'])}};
    var search = $('#dialog-product-search-form').searchForm({
        data: data,
        init:function(e) {}
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
