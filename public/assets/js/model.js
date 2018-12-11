(function (window) {

    var model = {
        turn: function (table) {

            var key = $('#' + table + '_form').find('#master_key').val();
            var url = app.url('model/form/turn', { key: key });
            $('#form-turn').__dialog({
                title: '单据审批',
                url: url,
                buttons: [{
                    text: "提交",
                    'class': "btn-success",
                    click: function () {

                        var gets = {};
                        var query = $('#myturn,#' + table + '_form').serialize();

                        // 循环子表
                        var tables = jqgridFormList[table];
                        if (tables.length) {
                            for (var i = 0; i < tables.length; i++) {
                                var t = tables[i];
                                var dataset = t.jqGrid('getRowsData');
                                var p = t[0].p;
                                var deleteds = p.deleteds;
                                if (dataset.v === true) {
                                    if (dataset.data.length == 0) {
                                        $.toastr('error', p.tableTitle + '不能为空。');
                                        return;
                                    } else {
                                        gets[p.table] = { rows: dataset.data, deleteds: deleteds };
                                    }
                                } else {
                                    return;
                                }
                            }
                        }

                        $.post(app.url('model/form/turn'), query + '&' + $.param(gets), function (res) {
                            if (res.status) {

                                if (res.data) {
                                    // 返回页面刷新
                                    self.location.href = res.data;
                                }

                            } else {
                                $.toastr('error', res.data);
                            }
                        }, 'json');
                    }
                }, {
                    text: "取消",
                    'class': "btn-default",
                    click: function () {
                        $(this).dialog("close");
                    }
                }]
            });

        }, freeturn: function (table) {

            var key = $('#' + table + '_form').find('#master_key').val();
            var url = app.url('model/form/freeturn', { key: key });
            $('#form-turn').__dialog({
                title: '单据审批',
                url: url,
                buttons: [{
                    text: "提交",
                    'class': "btn-success",
                    click: function () {

                        var gets = {};
                        var query = $('#myturn,#' + table + '_form').serialize();

                        // 循环子表
                        var tables = jqgridFormList[table];
                        if (tables.length) {
                            for (var i = 0; i < tables.length; i++) {
                                var t = tables[i];
                                var dataset = t.jqGrid('getRowsData');
                                var p = t[0].p;
                                var deleteds = p.deleteds;
                                if (dataset.v === true) {
                                    if (dataset.data.length == 0) {
                                        $.toastr('error', p.tableTitle + '不能为空。');
                                        return;
                                    } else {
                                        gets[p.table] = { rows: dataset.data, deleteds: deleteds };
                                    }
                                } else {
                                    return;
                                }
                            }
                        }

                        $.post(app.url('model/form/freeturn'), query + '&' + $.param(gets), function (res) {
                            if (res.status) {

                                if (res.data) {
                                    // 返回页面刷新
                                    self.location.href = res.data;
                                }

                            } else {
                                $.toastr('error', res.data);
                            }
                        }, 'json');
                    }
                }, {
                    text: "取消",
                    'class': "btn-default",
                    click: function () {
                        $(this).dialog("close");
                    }
                }]
            });

        }, draft: function (table) {

            var gets = {};
            var query = $('#myturn,#' + table + '_form').serialize();

            // 循环子表
            var tables = jqgridFormList[table];
            if (tables.length) {
                for (var i = 0; i < tables.length; i++) {
                    var t = tables[i];
                    var dataset = t.jqGrid('getRowsData');
                    var p = t[0].p;
                    var deleteds = p.deleteds;
                    if (dataset.v === true) {
                        if (dataset.data.length == 0) {
                            $.toastr('error', p.tableTitle + '不能为空。');
                            return;
                        } else {
                            gets[p.table] = { rows: dataset.data, deleteds: deleteds };
                        }
                    } else {
                        return;
                    }
                }
            }

            $.post(app.url('model/form/draft'), query + '&' + $.param(gets), function (res) {
                if (res.status) {
                    self.location.href = res.data;
                } else {
                    $.toastr('error', res.data);
                }
            }, 'json');

        }, remove: function (table) {

            var me = $('#'+ table + '_form');
            var rows = me.find('input[name="id[]"]:checked');
            if (rows.length == 0) {
                $.toastr('error', '最少选择一行记录。');
                return;
            }

            var formData = me.serialize();
            $.messager.confirm('操作确认', '确定要删除吗？', function () {

                $.post(app.url('model/form/delete', {table: table}), formData, function (res) {
                    if (res.status) {
                        location.reload();
                    } else {
                        $.toastr('error', res.data);
                    }
                }, 'json');

            });

        }, store: function (table) {

            var gets = {};
            var query = $('#myturn,#' + table + '_form').serialize();

            // 循环子表
            var tables = jqgridFormList[table] || [];
            if (tables.length) {
                for (var i = 0; i < tables.length; i++) {
                    var t = tables[i];
                    var dataset = t.jqGrid('getRowsData');
                    var p = t[0].p;
                    var deleteds = p.deleteds;
                    if (dataset.v === true) {
                        if (dataset.data.length == 0) {
                            $.toastr('error', p.tableTitle + '不能为空。');
                            return;
                        } else {
                            gets[p.table] = { rows: dataset.data, deleteds: deleteds };
                        }
                    } else {
                        return;
                    }
                }
            }

            $.post(app.url('model/form/store'), query + '&' + $.param(gets), function (res) {
                if (res.status) {

                    if (res.data) {
                        // 返回页面刷新
                        self.location.href = res.data;
                    }

                } else {
                    $.toastr('error', res.data);
                }
            }, 'json');

        },
        turnlog: function (key) {
            var url = app.url('model/form/log', { key: key });
            $('#form-turn').__dialog({
                title: '审批记录',
                url: url,
                buttons: [{
                    text: "取消",
                    'class': "btn-default",
                    click: function () {
                        $(this).dialog("close");
                    }
                }]
            });
        },

        quickForm: function(table, title, url, size) {
            
            size = size || 'md';

            $('#quick-form').__dialog({
                title: title,
                url: url,
                dialogClass: 'modal-' + size,
                destroy : true,

                buttons: [{
                    text: "提交",
                    'class': "btn-info",
                    click: function(e) {

                        var gets = $('#'+table+'_form').serialize();
                        var rows = {};

                        // 循环子表
                        var tables = jqgridFormList[table];

                        if(tables.length) {

                            for(var i=0; i < tables.length; i++) {
                                var t = tables[i];
                                var p = t[0].p;
                                var deleteds = p.deleteds;

                                // 有选择按钮
                                if(p.multiselect == true) {

                                    var dataset = t.jqGrid('getSelections');

                                    if(dataset.length == 0) {
                                        $.toastr('error', p.tableTitle + '不能为空。');
                                        return;
                                    } else {
                                        rows[p.table] = {rows: dataset, deleteds: deleteds};
                                    }

                                } else {

                                    var dataset = t.jqGrid('getDatas');
                                    if(dataset.v === true) {
                                        if(dataset.data.length == 0) {
                                            $.toastr('error', p.tableTitle + '不能为空。');
                                            return;
                                        } else {
                                            rows[p.table] = {rows: dataset.data, deleteds: deleteds};
                                        }
                                    } else {
                                        return;
                                    }
                                }
                            }
                        }

                        gets = gets +'&'+ $.param(rows);

                        var btn = $(e.target);
                        btn.prop('disabled', true);
                        btn.text('提交中');

                        $.post(app.url('model/form/store'), gets, function(res) {

                            btn.prop('disabled', false);
                            btn.text('提交');

                            if(res.status) {
                                location.reload();
                            } else {
                                $.toastr('error', res.data);
                            }

                        },'json');

                    }
                },{
                    text: "取消",
                    'class': "btn-default",
                    click: function(e) {
                        $(this).dialog("close");
                    }
                }]
            });
        }
    }

    window.model = model;

})(window);

(function($) {
    function jqgridAction(table, name) {
        this.name = name;
        this.table = table;
        this.routes = {};
        this.dialogType = 'dialog';

        this.show = function(data) {
            var me = this;
            var url = app.url(this.routes.show, {id: data.id});

            if (me.dialogType == 'dialog') {
                viewDialog({
                    title: me.name,
                    dialogClass: 'modal-lg',
                    url: url,
                    close: function(res) {
                        $(this).dialog("close");
                    }
                });
            } else {
                var index = layer.open({
                    title: '<i class="fa fa-columns"></i> ' + me.name +'</a>',
                    type: 2,
                    move: false,
                    area: ['100%', '100%'],
                    content: url,
                });
            
            }
        }

        this.delete = function() {
            var me = this;
            var grid  = window[me.table].grid;
            var selections = grid.jqGrid('getSelections');

            var ids = [];
            $.each(selections, function(i, selection) {
                ids.push(selection.id);
            });

            if(ids.length == 0) {
                var selRow = grid.jqGrid('getGridParam', 'selrow');
                if (selRow) {
                    ids.push(selRow);
                }
            }

            if(ids.length > 0) {
                var content = ids.length + '个' + me.name + '将被删除？';
                top.$.messager.confirm('删除' + me.name, content, function() {

                    var loading = layer.msg('数据提交中...', {
                        icon: 16, shade: 0.1
                    });

                    $.post(app.url(me.routes.delete), {id: ids}, function(res) {

                        layer.close(loading);

                        if(res.status) {
                            $.toastr('success', res.data);
                            grid.trigger('reloadGrid');
                        } else {
                            $.toastr('error', res.data);
                        }
                    },'json');
                });
            } else {
                $.toastr('error', '最少选择一行记录。');
            }
        }
        this.created_by = function(data) {
            var me = this;
            var grid = window[this.table].grid;
            formDialog({
                title: '私信',
                url: app.url('user/message/create', {user_id: data.id}),
                id: 'user_message-form-edit',
                dialogClass:'modal-md',
                success: function(res) {
                    $.toastr('success', res.data);
                    grid.trigger('reloadGrid');
                    console.log(grid);
                    $(this).dialog("close");
                },
                error: function(res) {
                    $.toastr('error', res.data);
                }
            });
        }

        this.create = function() {
            var me = this;
            var grid  = window[this.table].grid;
            if (me.dialogType == 'dialog') {
                formDialog({
                    title: '新建' + this.name,
                    url: app.url(this.routes.create),
                    id: this.table + '-form-edit',
                    table: this.table,
                    dialogClass: 'modal-lg',
                    success: function(res) {
                        $.toastr('success', res.data);
                        grid.trigger('reloadGrid');
                        $(this).dialog("close");
                    },
                    error: function(res) {
                        $.toastr('error', res.data);
                    }
                });
            } else {
                var index = layer.open({
                    title: '<i class="fa fa-file-text-o"></i> 新建' + me.name,
                    type: 2,
                    move: false,
                    area: ['100%', '100%'],
                    content: app.url(this.routes.create),
                    end: function() {
                        // $.toastr('success', '恭喜您，新建成功。');
                        grid.trigger('reloadGrid');
                    }
                });
            }
        }

        this.edit = function(data) {
            var me = this;
            var grid = window[table].grid;

            if (me.dialogType == 'dialog') {
                formDialog({
                    title: '编辑' + me.name,
                    url: app.url(me.routes.edit, {id: data.id}),
                    id: me.table + '-form-edit',
                    table: me.table,
                    dialogClass: 'modal-lg',
                    success: function(res) {
                        $.toastr('success', res.data);
                        grid.trigger('reloadGrid');
                        $(this).dialog("close");
                    },
                    error: function(res) {
                        $.toastr('error', res.data);
                    }
                });
            } else {
                var index = layer.open({
                    title: '<i class="fa fa-file-text-o"></i> 编辑' + me.name,
                    type: 2,
                    move: false,
                    area: ['100%', '100%'],
                    content: app.url(this.routes.edit, {id: data.id}),
                    end: function() {
                        // $.toastr('success', '恭喜您，编辑成功。');
                        grid.trigger('reloadGrid');
                    }
                });
            }
        }

        // 导出
        this.export = function(data) {
            var config = window[this.table];
            var grid   = config.grid;
            var search = config.search;

            var queryType = search.queryType || 'simple';

            var id = this.table + '-search-form';
            if(queryType == 'advanced') {
                id += '-advanced';
            }
            var form = $("#" + id);
            form.prop('method', 'post');
            form.append('<input type="text" name="export" value="1" />');
            form.submit();
            form.find("input[name='export']").remove();
        }

        this.filter = function() {
            var config = window[this.table];
            var grid   = config.grid;
            var search = config.search;
            var params = search.advanced.query;
            // 过滤数据
            $(search.advanced.el).dialog({
                title: '高级搜索',
                modalClass: 'no-padder',
                buttons: [{
                    text: "确定",
                    'class': "btn-info",
                    click: function() {
                        var query = search.advanced.el.serializeArray();
                        search.queryType = 'advanced';
                        $.map(query, function(row) {
                            params[row.name] = row.value;
                        });
                        grid.jqGrid('setGridParam', {
                            postData: params,
                            page: 1
                        }).trigger('reloadGrid');
                        $(this).dialog("close");
                        return false;
                    }
                },{
                    text: "取消",
                    'class': "btn-default",
                    click: function() {
                        $(this).dialog("close");
                    }
                }]
            });
        }
    }
    window.jqgridAction = jqgridAction;
})(jQuery);