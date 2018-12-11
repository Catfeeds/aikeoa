var jqgridFormList = {};

function jqgridForm(master, table, options) {

    // 合计数据
    var counts = options.counts;

    // 行计数据
    var rowCounts = options.rowCounts;
    var rowRules = {};
    for(var i = 0; i < rowCounts.length; i++) {
        var rules  = rowCounts[i].rule.match(/\[(.+?)\]/g);
        for(var j = 0; j < rules.length; j++) {
            var rule = rules[j].replace(/\[|\]/g, '');
            rowRules[rule] = rowCounts[i];
        }
    }

    // 合计方法
    var footerTotal = function() {
        
        if(counts.length) {
            for(var i = 0; i < counts.length; i++) {
                var count = counts[i];
                var data  = {};
                data[count.field] = $(this).getCol(count.field, false, count.type);
                $(this).footerData('set', data);
            }
        }

        // 调用外部方法，例如计算大写金额等
        var custom_footer = window['custom_'+ table + '_footer'];
        if(typeof custom_footer === 'function') {
            custom_footer.call(this);
        }
        
    }

    // 计算行事件
    var rowTotal = function(cellname, rowid) {

        var row = rowRules[cellname];
        if(row == undefined) {
            return;
        }

        var data = t.jqGrid('getRowData', rowid);
        var rule = row.rule;
        $.each(data, function(k, v) {
            rule = rule.replace('['+k+']', parseFloat(v));
        });
        var value = isNaN(eval(rule)) ? 0 : Math.round(parseFloat(eval(rule))*10000)/10000;

        var data = {};
        data[row.field] = value;
        t.jqGrid('setRowData', rowid, data);
    }

    // 调用自定义编辑器
    var customEditoption = function(editoption) {
        return window['custom_'+ editoption.cellname](editoption);
    }

    var t = $('#grid_' + table).jqGrid({
        table: table,
        tableTitle: options.title,
        caption: '',
        datatype: 'local',
        colModel: options.columns,
        cellEdit: true,
        maxRowId: 0,
        rowNum: 10000,
        editCombo: options.editCombo || {},
        cellsubmit: 'clientArray',
        data: options.data,
        rawData: options.data,
        cellurl: '',
        multiselect: options.multiselect,
        viewrecords: true,
        rownumbers: true,
        footerrow: true,
        height: 305,
        height:'auto',
        rowattr: function(row) {
            // 附加tr样式
            if (row.id > 0) {
                return {'class': 'edited'};
            }
        },
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
            footerTotal.call(this);
        },
        // 进入编辑前调用
        beforeEditCell: function(rowid, cellname, value, iRow, iCol) {

            if(options.editoptions[cellname] == undefined) {

            } else {

                var editoption = options.editoptions[cellname];
                
                if(editoption.type == 'custom') {
                    editoption.cellname = cellname;
                    editoption.rowData  = t.jqGrid('getRowData', rowid);
                    t.setColProp(cellname, customEditoption(editoption));

                } else {

                    if(editoption.form_type == 'dataset') {
                        t.setColProp(cellname, {
                            editoptions: {
                                dataInit: $.jgrid.celledit.dialog({
                                    srcField: editoption.srcField,
                                    mapField: editoption.mapField,
                                    suggest: {
                                        url: editoption.url,
                                        params: {order:'asc'}
                                    },
                                    dialog: {
                                        title: editoption.title,
                                        url: editoption.url,
                                        params: {order:'asc'}
                                    }
                                })
                            }
                        });
                    }

                    if(editoption.form_type == 'date') {
                        t.setColProp(cellname, {
                            editoptions: {
                                dataInit: function(element) {
                                    datePicker({el: element, dateFmt: 'yyyy-MM-dd'});
                                }
                            }
                        });
                    }

                    if(editoption.form_type == 'option') {
                        t.setColProp(cellname, {
                            editoptions: {
                                dataInit: $.jgrid.celledit.dropdown({
                                    valueField: 'id',
                                    textField: 'text'
                                })
                            }
                        });
                    }
                }
            }
        },
        // 进入编辑后调用
        afterEditCell: function(rowid, cellname, value, iRow, iCol) {
        },
        // 保存服务器时调用
        afterRestoreCell: function(rowid, value, iRow, iCol) {
        },
        // 保存在本地的时候调用
        afterSaveCell: function(rowid, cellname, value, iRow, iCol) {
            // 行合计
            rowTotal.call(this, cellname, rowid);
            // 计算页脚数据
            footerTotal.call(this);
        }
    });

    if(options.data.length == 0) {
        if(options.autoOption) {
            // 初始化行数据
            for(var i = 1; i <= 10; i++) {
                t.jqGrid('addRowData', i, {});
                t[0].p.maxRowId = t[0].p.reccount * 10;
            }
        }
    }
    jqgridFormList[master].push(t);
}