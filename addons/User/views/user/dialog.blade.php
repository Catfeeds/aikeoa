<!--
<div class="padder">
    <div class="m-t-sm m-b-sm">
        <form id="user-dialog-search-form" name="user_dialog_search_form" class="form-inline" method="get">
            
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-default active">
                    <input type="radio" name="group_id" value="1">用户
                </label>
                <label class="btn btn-sm btn-default">
                    <input type="radio" name="group_id" value="2">客户
                </label>
                <label class="btn btn-sm btn-default">
                    <input type="radio" name="group_id" value="4">供应商
                </label>
            </div>
            @include('searchForm')
        </form>
    </div>

    <table id="user-dialog">
    </table>
    <div id="user-dialog-page"></div>
</div>

<script>
(function($) {
    var selectBox = {};
    var params = {{json_encode($query)}};
    var sid    = params.prefix == 1 ? 'sid' : 'id';
    var $table = $("#user-dialog");

    var model = [
        {name: "nickname", index: 'user.nickname', label: '名字', minWidth: 220, align: 'left'},
        {name: "username", index: 'user.username', label: '账号', width: 180, align: 'left'},
        {name: "status", index: 'user.status', label: '状态', width: 80, align: 'center'},
        {name: "id", index: 'user.id', label: 'ID', width: 60, align: 'center'}
    ];

    $table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'POST',
        url: app.url('user/user/dialog'),
        colModel: model,
        rowNum: 25,
        multiboxonly: params.multi == 0 ? true : false,
        multiselect: true,
        viewrecords: true,
        rownumbers: false,
        height: 340,
        footerrow: false,
        postData: params,
        pager: '#user-dialog-page',
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
            // 设置默认选中
            setSelecteds(res);
        },
        // 双击选中
        ondblClickRow: function(id) {
            if(params.multi == 1) {
                $table.jqGrid('setSelection', id);
            }
            getSelecteds();
        },
    });

    function setSelecteds(res) {
        var ids = $('#'+params.id).val();
        console.log(ids);
        ids = ids.split(',');
        $.each(ids, function(k, v) {
            if(v) {
                $table.jqGrid('setSelection', v);
            }
        });
    }

    function getSelecteds() {
        var rows = $table.jqGrid('getSelections');
        if(params.multi == 0) {
            if(rows.length > 1) {
                $.toastr('error', '只能选择一项。', '错误');
                return false;
            }
        }

        var id = [], text = [];
        for (var i = 0; i < rows.length; i++) {
            id.push(rows[i][sid]);
            text.push(rows[i].text);
        }

        // 回写数据
        $('#'+params.id).val(id.join(','));
        $('#'+params.id+'_text').text(text.join(','));

        // 关闭窗口
        $('#modal-dialog-user').dialog("close");

        return true;
    }

    window.selectBox = {getSelecteds: getSelecteds};

    var data = {{json_encode($search['forms'])}};
    var search = $('#user-dialog-search-form').searchForm({
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
-->

<div class="padder">

    <div class="m-t-sm m-b-sm">
        <form id="search-form" name="mysearch" class="form-inline" method="get">
            
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-default active">
                    <input type="radio" name="group_id" value="1">用户
                </label>
                <label class="btn btn-sm btn-default">
                    <input type="radio" name="group_id" value="2">客户
                </label>
                <label class="btn btn-sm btn-default">
                    <input type="radio" name="group_id" value="4">供应商
                </label>
            </div>
            
            @include('searchForm')

        </form>
    </div>

    <table id="user-dialog">
        <thead>
        <tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="nickname" data-sortable="true" data-align="left">名字</th>
            <th data-field="username" data-width="200" data-sortable="true" data-align="left">账号</th>
            <th data-field="status" data-formatter="statusFormatter" data-width="100" data-sortable="true" data-align="center">状态</th>
            <th data-field="id" data-width="60" data-sortable="true" data-align="center">ID</th>
        </tr>
        </thead>
    </table>
</div>

<script>

function statusFormatter(value) {
    if(value == 0) {
        return '<span style="color:red">停用</span>';
    }
    if(value == 1) {
        return '正常';
    }
}

(function($) {

    var $table = $('#user-dialog');
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
        })

        $('#'+params.id).val(id.join(','));
        $('#'+params.name).val(text.join(','));
        $('#'+params.id+'_text').text(text.join(','));
    }

    function setRow(row)
    {
        if(params.multi == 0) {
            selected = {};
        }
        selected[row[sid]] = row.nickname;
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
        clickToSelect: true,
        method: 'post',
        url: '{{url()}}',
        height: 380,
        sidePagination: 'server',
        pagination: true,
        onLoadSuccess: function(res) {

            getSelected();

            $.each(selected, function(j) {
                for (var i = 0; i < res.data.length; i++) {
                    if(res.data[i][sid] == j) {
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
    var search = $('#search-form').searchForm({
        data: data,
        init: function(e) {
            var self = this;
        }
    });

    search.find('#search-submit').on('click', function() {
        var params = search.serializeArray();
        $.map(params, function(row) {
            data[row.name] = row.value;
        });
        $table.bootstrapTable('refresh', {
            url:app.url('user/user/dialog', data)
        });
        return false;
    });
})(jQuery);
</script>