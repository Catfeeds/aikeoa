<div class="wrapper" style="padding-top:0;">
    <div class="m-t-sm m-b-sm">
        <form id="dialog-circle-search-form" name="mysearch" class="form-inline" method="get">
            @include('searchForm')
        </form>
    </div>
    <table id="customer-circle-dialog">
        <thead>
        <tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="name" data-sortable="true" data-align="left">名称</th>
            <th data-field="id" data-width="60" data-sortable="true" data-align="center">ID</th>
        </tr>
        </thead>
    </table>
</div>

<script>
(function($) {
    var $table = $('#customer-circle-dialog');
    var params = {{json_encode($query)}};
    var sid    = params.prefix == 1 ? 'sid' : 'id';

    var selected = {};

    function getSelected()
    {
        selected = {};

        var id   = $('#'+params.id).val();
        var text = $('#'+params.id+'_text').text();

        if(id == '') {
            return;
        }

        id   = id.split(',');
        text = text.split(',');
        for (var i = 0; i < id.length; i++) {
            selected[id[i]] = text[i];
        }
    }

    function setSelected() {
        var id   = [];
        var text = [];
        $.each(selected, function(k, v) {
            id.push(k);
            text.push(v);
        });

        $('#'+params.id).val(id.join(','));
        $('#'+params.name).val(text.join(','));
        $('#'+params.id+'_text').text(text.join(','));
    }

    function setRow(row)
    {
        if(params.multi == 0) {
            selected = {};
        }
        selected[row[sid]] = row.name;
        setSelected();
    }

    function unsetRow(row)
    {
        $.each(selected, function(id) {
            if(id == row[sid]) {
                delete selected[id];
            }
        });
        setSelected();
    }

    $table.bootstrapTable({
        iconSize: 'sm',
        singleSelect: params.multi == 1 ? 0 : 1,
        //toolbar: '#customer-circle-dialog-toolbar',
        clickToSelect: true,
        method: 'post',
        url: '{{url()}}',
        height: 380,
        //sidePagination: 'server',
        //pagination: true,
        onLoadSuccess: function(data) {

            getSelected();

            $.each(selected, function(j) {
                for (var i = 0; i < data.length; i++) {
                    if(data[i].id == j) {
                        $table.bootstrapTable('check', i);
                   }
                }
            });
        },
        onDblClickRow: function(row) {
            setRow(row);
            $('#modal-dialog-user').dialog('close');
        },
        onCheck: function(row) {
            setRow(row);
        },
        onUncheck: function(row) {
            unsetRow(row);
        },
        onCheckAll: function(rows) {
            for (var i = 0; i < rows.length; i++) {
                setRow(rows[i]);
            }
        },
        onUncheckAll: function(rows) {
            for (var i = 0; i < rows.length; i++) {
                unsetRow(rows[i]);
            }
        }
    });

    var data = {{json_encode($search['forms'])}};
    var search = $('#dialog-circle-search-form').searchForm({
        data: data,
        init:function(e) {
            var self = this;
        }
    });

    search.find('#search-submit').on('click', function() {
        var params = search.serializeArray();
        $.map(params, function(row) {
            data[row.name] = row.value;
        });
        $table.bootstrapTable('refresh', {
            url:app.url('customer/circle/dialog', data)
        });
        return false;
    });
})(jQuery);

</script>