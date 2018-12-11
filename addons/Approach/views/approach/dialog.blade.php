<div id="promotion-dialog-toolbar">

    <form id="dialog-search-form" name="mysearch" class="form-inline" method="get">
        @include('searchForm')
    </form>
</div>

<div class="padder">
    <table id="promotion-dialog">
        <thead>
        <tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="number" data-sortable="true" data-align="left">促销编号</th>
            <th data-field="nickname" data-width="200" data-sortable="true" data-align="left">客户</th>
            <th data-field="step_number" data-width="100" data-sortable="true" data-align="center">步骤</th>
            <th data-field="id" data-width="60" data-sortable="true" data-align="center">编号</th>
        </tr>
        </thead>
    </table>
</div>

<script>
(function($) {
    var $table = $('#promotion-dialog');
    var params = {{json_encode($get)}};
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
        selected[row[sid]] = row.number;
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
        toolbar: '#promotion-dialog-toolbar',
        showColumns: true,
        clickToSelect: true,
        method: 'post',
        url: '{{url()}}',
        height: 380,
        sidePagination: 'server',
        pagination: true,
        onLoadSuccess: function(res) {

            getSelected();

            $.each(selected, function(j) {
                for (var i = 0; i < res.rows.length; i++) {
                    if(res.rows[i].id == j) {
                        $table.bootstrapTable('check', i);
                   }
                }
            });
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
    var search = $('#dialog-search-form').searchForm({
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
            url:app.url('promotion/promotion/dialog', data)
        });
        return false;
    });
})(jQuery);

</script>