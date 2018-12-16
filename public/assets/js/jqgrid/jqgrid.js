$.extend($.jgrid.styleUI.Bootstrap, {
    common: {
        disabled: "ui-disabled",
        highlight : "success",
        hover : "active",
        cornerall: "", 
        cornertop: "",
        cornerbottom : "",
        hidden : "",
        icon_base : "fa",
        overlay: "ui-overlay",
        active : "active",
        error : "bg-danger",
        button : "btn btn-default",
        content : ""
    },
    base: {
        entrieBox : "",
        viewBox : "",
        headerTable : "table table-bordered",
        headerBox : "",
        rowTable : "table table-bordered",
        rowBox : "",
        footerTable : "table table-bordered",
        footerBox : "",
        headerDiv : "",
        gridtitleBox : "",
        customtoolbarBox : "",
        //overlayBox: "ui-overlay",
        loadingBox : "row",
        rownumBox :  "active",
        scrollBox : "",
        multiBox : "checkbox",
        pagerBox : "",
        pagerTable : "table",
        toppagerBox : "",
        pgInput : "form-control",
        pgSelectBox : "form-control",
        pgButtonBox : "",
        icon_first : "fa-angle-double-left",
        icon_prev : "fa-angle-left",
        icon_next: "fa-angle-right",
        icon_end: "fa-angle-double-right",
        icon_asc : "fa-caret-up",
        icon_desc : "fa-caret-down",
        icon_caption_open : "icon-circle-arrow-up",
        icon_caption_close : "icon-circle-arrow-down"
    },
    modal: {
        modal : "modal-content",
        header : "modal-header",
        title : "modal-title",
        content :"modal-body",
        resizable : "ui-resizable-handle ui-resizable-se",
        icon_close : "icon-remove-circle",
        icon_resizable : "icon-import"
    },
    celledit: {
        inputClass : 'form-control'
    }, 
    inlinedit: {
        inputClass : 'form-control',
        icon_edit_nav : "fa-edit",
        icon_add_nav : "icon-plus",
        icon_save_nav : "icon-save",
        icon_cancel_nav : "icon-remove-circle"
    },
    formedit: {
        inputClass : "form-control",
        icon_prev : "icon-step-backward",
        icon_next : "icon-step-forward",
        icon_save : "icon-save",
        icon_close : "icon-remove-circle",
        icon_del : "icon-trash",
        icon_cancel : "icon-remove-circle"
    },
    navigator: {
        icon_edit_nav : "fa-edit",
        icon_add_nav : "icon-plus",
        icon_del_nav : "icon-trash",
        icon_search_nav : "icon-search",
        icon_refresh_nav : "icon-refresh",
        icon_view_nav : "icon-info-sign",
        icon_newbutton_nav : "icon-new-window"
    },
    grouping: {
        icon_plus : 'icon-triangle-right',
        icon_minus : 'icon-triangle-bottom'
    },
    filter: {
        table_widget : 'table table-condensed',
        srSelect : 'form-control',
        srInput : 'form-control',
        menu_widget : '',
        icon_search : 'icon-search',
        icon_reset : 'icon-refresh',
        icon_query :'icon-comment'
    },
    subgrid: {
        icon_plus : 'icon-triangle-right',
        icon_minus : 'icon-triangle-bottom',
        icon_open : 'icon-indent-left'
    },
    treegrid: {
        icon_plus : 'fa-folder',
        icon_minus : 'fa-folder-open',
        icon_leaf : 'fa-folder-o'
    },
    fmatter: {
        icon_edit : "fa-edit",
        icon_add : "fa-plus",
        icon_save : "fa-save",
        icon_cancel : "fa-remove-circle",
        icon_del : "fa-trash"
    },
    colmenu: {
        menu_widget : '',
        input_checkbox : "",
        filter_select: "form-control",
        filter_input : "form-control",
        icon_menu : "icon-menu-hamburger",
        icon_sort_asc : "icon-sort-by-alphabet",
        icon_sort_desc : "icon-sort-by-alphabet-alt",
        icon_columns : "icon-list-alt",
        icon_filter : "icon-filter",
        icon_group : "icon-align-left",
        icon_freeze : "icon-object-align-horizontal",
        icon_move: "icon-move"
    }
});

$.extend($.jgrid.defaults, {
    datatype: 'json',
    viewrecords: true,
    shrinkToFit: false,
    /* 自定义部分 */
    editCombo: {},
    editComboCache: {},
    panelHeight: 0,
    rawData: [],
    cellEvent: function(cm, cellval, opts, rwdat, _act) {
        var links = [];
        $.each(cm.events, function(i, row) {
            links.push('<a data-toggle="event" class="option" data-action="'+ row.action +'" data-id="'+ rwdat.id +'" href="javascript:;">'+ row.name +'</a>');
        });
        return links.join(' ');
    },
    loadComplete: function(res) {
        $(this).jqGrid('initPagination', res);
    },
    onInitGrid: function() {
        var me = this;
        // 监听操作
        $(this).on('click', '[data-toggle="option"]', function(e) {
            e.stopPropagation();
            var option = $(this).hasClass('fa-plus') === true ? 'plus' : 'delete';
            $.fn.fmatter.rowoptions.call(this, option);
        });

        // 监听操作
        $(this).on('click', '[data-toggle="actionlink"]', function(e) {
            e.stopPropagation();
            actionLink($(this).data());
        });

    },
    gridComplete: function() {
        $(this).jqGrid('setColsWidth');
    },
    styleUI: 'Bootstrap',
    autowidth: true,
    rowNum: 25,
    changes: {inserted:[], deleted:[], updated:[]},
    rowList: [25, 50, 100, 200, 500, 1000],
    sortable: false,
    sortorder: 'desc',
    recordpos: 'right',
    pagerpos: 'center',
    responsive: true,
    rownumbers: true, 
    rownumWidth: 25,
    multiselect: true,
    prmNames: {
        page: "page",
        rows: "limit",
        sort: "sort",
        order: "order",
        totalrows: "total",
    },
    localReader: {
        id: '_id'
    },
    jsonReader: {
        root: "data",
        page: "current_page",
        total: "last_page",
        records: "total",
        repeatitems: false,
        id: "id"
    }
});

$.extend($.fn.fmatter, {
    checkbox: function(cellvalue, options, rowdata) {
        return "<input type='checkbox' class='cbox checkbox' id='abc-222' name='abc-222' />";
    }
});

$.extend($.fn.fmatter, {
    options: function(cellvalue, options, rowdata) {
        return "<div class='options'><span data-toggle='option' class='fa fa-plus' title='新增行'></span><span data-toggle='option' class='fa fa-times' title='删除行'></span></div>";
    }
});

$.extend($.fn.fmatter, {
    created_by: function(value, options, row) {
        return row.created_by == '' ? '' : "<a data-toggle='event' data-action='created_by' data-id='"+row.created_by+"' class='option'>"+ value +"</a>";
    }
});

$.extend($.fn.fmatter, {
    rowoptions: function(value) {
        var $me = $(this).closest("table.ui-jqgrid-btable");
        var $tr = $(this).closest("tr.jqgrow");
        var me  = $me[0];
        var p   = me.p;
        var rowid = $tr.attr('id');

        if(p.selrow) {
            $me.jqGrid("restoreCell", p.iRow, p.iCol);
            $('#' + p.selrow).removeClass('selected-row active success').attr({"aria-selected":"false", "tabindex" : "-1"});
            $('#' + p.selrow).find('td').eq(p.iCol).removeClass('edit-cell edit-cell-item success');
        }

        if(value == 'plus') {
            $me.jqGrid('addRowData', p.data.length + 1, {}, 'after', rowid);
        }

        if(value == 'delete') {
            if(p.data.length == 1) {
                return;
            }
            $.messager.confirm('操作确认', '确定要删除吗？', function() {
                p.changes.deleted.push($me.jqGrid('getRowData', rowid));
                $me.jqGrid('delRowData', rowid);
            });
        }
        $me.triggerHandler("jqGridAfterGridComplete");
    }
});

$.extend($.fn.fmatter, {
    actionLink: function(cellvalue, options, rowdata) {
        var rows = options.colModel.options;
        var links = [];
        $.each(rows, function(i, row) {
            links.push('<a data-toggle="event" class="option" data-action="'+ row.action +'" data-id="'+ rowdata.id +'" href="javascript:;">'+ row.name +'</a>');
        });
        return links.join(' ');
    }
});

$.extend($.fn.fmatter, {
    status: function(cellvalue, options, rowdata) {
        return cellvalue == 1 ? '<span data-id="' + cellvalue +'" class="label label-success">已读</span>' : '<span data-id="' + cellvalue +'" class="label label-danger">未读</span>';
    }
});

$.extend($.fn.fmatter.status, {
    unformat: function(cellvalue, options, element) {
        var status = $(element).find('span').data('id');
        return status;
    }
});

$.extend($.fn.fmatter, {
    datetime: function(cellvalue, options, rowdata) {
        if(cellvalue) {
            return format_datetime(cellvalue);
        }
        return '';
    }
});

$.extend($.fn.fmatter.datetime, {
    unformat: function(cellvalue, options, element) {
        return cellvalue;
    }
});

$.extend($.fn.fmatter, {
    dropdown: function(value, options, rowdata, operation) {

        if(value == undefined) {
            return '';
        }

        var p = this.p;
        var rowid = options.rowId;
        var name = options.colModel.name;
        var rows = this.p.editCombo[name] || [];

        var combo = $('.combo-' + name);
        var selected = combo.find('.option-selected');
        if(selected.length) {
            value = selected.data('value');
        }

        var item = $.grep(rows, function(row) {
            return row['id'] == value;
        });
        var text = item[0] ? item[0].text : '';

        p.editComboCache[rowid + '_' + name] = '' + value;

        if(operation == 'add') {
            return text;
        }

        if(operation == 'edit') {
            return text;
        }

    }
});

$.extend($.fn.fmatter.dropdown, {
    unformat: function(cellvalue, options, element) {
        return cellvalue;
    }
});

$.jgrid.celledit = {}
$.jgrid.celledit.dialog = function(config) {

    function suggestData(callback) {
        $.post(app.url(config.suggest.url, config.suggest.params), function(res) {
            callback(res);
        });
    }

    return function(elem, options) {
        
        var me = this,
            $me = $(me),
            p     = me.p,
            $elem = $(elem),
            name  = options.name,
            rowid = options.rowId,
            pid   = $.jgrid.jqID(p.id);

        // 编辑器打开后全选文本
        $elem.select();

        // 关闭自带自动完成
        $elem.attr('autocomplete', 'off');

        // 获取已经选择的直
        var selected = $me.jqGrid('getCell', rowid, config.srcField);

        // 获取映射字段
        var mapField = config.mapField || {};
        var textField = config.textField || {};

        // 获取下拉数据
        p.editCombo[name] = p.editCombo[name] || [];

        var suggest = config.suggest || {};

        var dialog = config.dialog;
        dialog.params['jqgrid'] = pid;

        // 设置缓存状态
        var cache = suggest['cache'] == undefined ? true : suggest['cache'];

        if(suggest['url']) {

            if(p.editCombo[name].length == 0 || cache == false) {

                $.post(app.url(suggest.url, suggest.params), function(res) {
                    p.editCombo[name] = res.data;
                    open();
                });

            } else {
                open();
            }
        } else {
            open();
        }
        
        function open() {
            // 初始化编辑器
            $elem.jqGridEditingDropdown({
                jqgrid: pid,
                arrow: 'fa-search',
                name: options.name,
                dialog: {
                    title: dialog.title,
                    url: app.url(dialog.url, dialog.params),
                    textField: textField,
                    mapField: mapField,
                },
                data: {
                    items: p.editCombo[name],
                    selected: selected
                },
                select: function(item) {
                    if(item) {

                        // 添加已编辑
                        $me.find('#' + rowid).addClass('edited');

						// 循环映射字段
                        $.each(mapField, function(k, field) {
                            var input = $('#' + rowid + '_' + k);
                            if (input) {
                                $('#' + rowid + '_' + k).val(item[field]);
                            } else {
                                $me.jqGrid('setCell', rowid, k, item[field]);
                            }
                        });
                        // 关闭编辑框
                        $me.jqGrid('saveCell', p.iRow, p.iCol);
                    }
                }
            });
        }

    }
}

$.jgrid.celledit.dropdown = function(config) {

    return function(elem, options) {
        
        var me = this,
            $me   = $(me),
            p     = me.p,
            $elem = $(elem),
            rowid = options.rowId,
            name  = options.name;
            //rows  = p.editCombo[name] || [];

        // 编辑器打开后全选文本
        $elem.select();

        // 关闭自带自动完成
        $elem.attr('autocomplete', 'off');

        var selectedKey = rowid + '_' + name;
        var selected = p.editComboCache[selectedKey];

        // 获取映射字段
        var mapField = config.mapField || {};

        // 获取下拉数据
        p.editCombo[name] = p.editCombo[name] || [];

        var suggest = config.suggest || {};

        // 设置缓存状态
        var cache = suggest['cache'] == undefined ? true : suggest['cache'];

        if(suggest['url']) {
            if(p.editCombo[name].length == 0 || cache == false) {
                $.post(app.url(suggest.url, suggest.params), function(res) {
                    p.editCombo[name] = res.data;
                    open();
                });
            } else {
                open();
            }
        } else {
            open();
        }

        function open() {

            // 初始化编辑器
            $elem.jqGridEditingDropdown({
                jqgrid: $.jgrid.jqID(p.id),
                arrow: 'fa-caret-down',
                name: options.name,
                data: {
                    items: p.editCombo[name],
                    selected: selected
                },
                select: function(item) {
                    if(item) {
                        // 添加已编辑
                        $me.find('#' + rowid).addClass('edited');

                        $('#' + rowid + '_' + name).val(item[config.textField]);
    
                        // 循环映射字段
                        $.each(mapField, function(k, field) {
                            $me.jqGrid('setCell', rowid, k, item[field]);
                        });

                        p.editComboCache[selectedKey] = item[config.valueField];
 
                        // 关闭编辑器
                        $me.jqGrid("saveCell", p.iRow, p.iCol);
                    }
                }
            });
        }
    }
}

$.jgrid.extend({

    exportGrid: function(filename) {

        var me  = $(this);
         // Get All IDs
        var ids = me.getDataIDs();

        var colModel = me.jqGrid('getGridParam','colModel');

        // labels
        var colNames = new Array();

        var rows  = [];
        var row   = [];
        var thead = [];
        var tbody = [];
        var model = {};

        var i = 0;
        for (var j = 0; j < colModel.length; j++) {
            model = colModel[j];
            if(model.hidden == true || model.name == 'op' || model.name == 'rn' || model.name == 'cb' || model.label == '' || model.label == '&nbsp;') {
                continue;
            }
            colNames[i++] = model.name;
            thead.push({name: model.label, width: model.width, align: model.align});
        }
        for (i = 0; i < ids.length; i++) {

            data = me.jqGrid('getRowData', ids[i], null, false);
            row  = [];
            for ( var j = 0; j < colNames.length; j++) {
                row.push(data[colNames[j]]);
            }
            tbody.push(row);
        }

        var data = JSON.stringify({thead:thead, tbody:tbody, filename:filename});
        var form = '<form id="excel_export_form" name="excel_export_form" action="'+app.url('index/api/jqexport')+'" method="post">';
        form = form + '<input type="hidden" name="data" value="'+encodeURIComponent(data)+'"></form>';

        // 动态插入导出div
        var div = document.createElement('div');
        div.id = 'excel_export';
        div.innerHTML = form;

        document.body.appendChild(div);
        document.getElementById('excel_export_form').submit();
        document.getElementById('excel_export').remove();
    },
    initPagination: function(res) {

        this.each(function() {

            var me = this;

            if(me.p.datatype == 'json') {
                me.p.rawData = res.data;
            }
            $(me.p.pager).bootpag({
                total: me.p.lastpage,
                page: me.p.page,
                records: me.p.records,
                rowNum: me.p.rowNum,
                rowList: me.p.rowList,
                maxVisible: 5
            }).on('page', function(event, num) {
                $(me).trigger('reloadGrid', [{page: num}]);
            }).on('rowList', function(event, num) {
                me.p.rowNum = num;
                $(me).trigger('reloadGrid', [{page: 1}]);
            });
        });
    },
    getEditComboValue: function(rowid, nm) {
        ret = false;
		this.each(function() {
			var $t = this;
			if(!$t.grid) {return;}
			ret = $t.p.editComboCache[rowid + '_' + nm];
		});
        return ret;
	},
    getRowData: function(rowid, usedata, unformat) {
		var res = {}, resall, getall=false, len, j=0;
        var un = unformat == undefined ? true : false;
		this.each(function(){
			var $t = this,nm,ind;
			if(rowid == null) {
				getall = true;
				resall = [];
				len = $t.rows.length-1;
			} else {
				ind = $($t).jqGrid('getGridRowById', rowid);
				if(!ind) { return res; }
				len = 1;
			}
			if( !(usedata && usedata === true && $t.p.data.length > 0)  ) {
				usedata = false;
			}
			while(j<len){
				if(getall) { 
					ind = $t.rows[j+1];  // ignore first not visible row
				}
				if( $(ind).hasClass('jqgrow') ) {
					if(usedata) {
						res = $t.p.data[$t.p._index[ind.id]]; 
					} else {
						$('td[role="gridcell"]',ind).each( function(i) {
							nm = $t.p.colModel[i].name;
							if ( nm !== 'cb' && nm !== 'subgrid' && nm !== 'rn') {
								if($t.p.treeGrid===true && nm === $t.p.ExpandColumn) {
									res[nm] = $.jgrid.htmlDecode($("span:first",this).html());
								} else {
									try {
                                        // 导出时不解码
                                        if(un == true) {
                                            res[nm] = $.unformat.call($t,this,{rowId:ind.id, colModel:$t.p.colModel[i]},i);
                                        } else {
                                            res[nm] = $(this).text();
                                        }
										
									} catch (e){
										res[nm] = $.jgrid.htmlDecode($(this).html());
									}
								}
							}
						});
					}
					if(getall) { resall.push(res); res={}; }
				}
				j++;
			}
		});
		return resall || res;
	},
    getRowsData: function() {

        var ret = [], cv = [];
        
		this.each(function() {
			var $t = this, nm;

            // 保存前关闭打开的编辑器
            $(this).jqGrid("saveCell", $t.p.iRow, $t.p.iCol);

			if (!$t.grid || $t.p.cellEdit !== true ) { return; }

			$($t.rows).each(function(j) {

                // 只显示标示了已编辑的行
                if ($(this).hasClass('edited')) {

                    var res = {};

                    $('td', this).each(function(i) {

                        var cm = $t.p.colModel[i],
                            nm = cm.name;

                        if (nm !== 'op' && nm !== 'rn' && nm !== 'cb' && nm !== 'subgrid') {
                            try {
                                
                                res[nm] = $.unformat.call($t,this,{rowId:$t.rows[j].id,colModel:$t.p.colModel[i]},i);
                                
                                if(cm.formatter == 'dropdown') {
                                    res[nm] = $t.p.editComboCache[$t.rows[j].id + '_' + nm];
                                }

                            } catch (e) {
                                res[nm] = $.jgrid.htmlDecode($(this).html());
                            }
                            
                            // 校验数据信息
                            var v = $.jgrid.checkValues.call($t, res[nm], -1, cm.rules, cm.label);
                            if(v[0] === false) {
                                cv = v;
                                // cv[1] = '第'+ j+'行 - ' + cv[1];
                                cv[3] = j;
                                cv[4] = i;
                                // 验证失败跳出循环
                                return false;
                            }
                        }
                    });

                    // 验证失败跳出循环
                    if(cv.length) {
                        return false;
                    }

                    ret.push(res);
                }

			});
		});

        // 显示错误信息
        if(cv.length) {
            $.toastr('error', cv[1]);
            // 数据校验失败打开失败的字段编辑器
            $(this).jqGrid("editCell", cv[3], cv[4], true);
            return {data: [], v: false};
        }

		return {data: ret, v: true};
	},
    // 验证不通过的行跳过行数据
    getDatas: function() {
		var ret = [], cv = [];
		this.each(function() {
			var $t = this, nm;

            // 保存前关闭打开的编辑器
            $(this).jqGrid("saveCell", $t.p.iRow, $t.p.iCol);

			if (!$t.grid || $t.p.cellEdit !== true ) { return; }

			$($t.rows).each(function(j) {

                // 只显示标示了已编辑的行
                if($(this).hasClass('jqgrow')) {

                    var res = {};

                    $('td', this).each(function(i) {

                        var cm = $t.p.colModel[i],
                            nm = cm.name;

                        if (nm !== 'op' && nm !== 'rn' && nm !== 'cb' && nm !== 'subgrid') {
                            try {
                                
                                res[nm] = $.unformat.call($t,this,{rowId:$t.rows[j].id,colModel:$t.p.colModel[i]},i);
                                
                                if(cm.formatter == 'dropdown') {
                                    res[nm] = $t.p.editComboCache[$t.rows[j].id + '_' + nm];
                                }

                            } catch (e) {
                                res[nm] = $.jgrid.htmlDecode($(this).html());
                            }
                            
                            // 校验数据信息
                            var v = $.jgrid.checkValues.call($t, res[nm], -1, cm.rules, cm.label);

                            cv = [];

                            if(v[0] === false) {
                                cv = v;
                                cv[3] = j;
                                cv[4] = i;
                                // 验证失败跳出循环
                                return false;
                            }

                        }
                    });

                    // 验证失败跳出循环
                    if(cv.length) {
                        return true;
                    }
                    
                    ret.push(res);

                }
			});
		});

		return {data: ret, v: true};
    },
    getSelections: function() {

        var $me = this,
            me  = this[0],
            p   = me.p,
            id  = p.jsonReader.id,
            ret = [];

        $me.jqGrid('saveCell', p.iRow, p.iCol);

        var slt = $me.jqGrid('getGridParam', 'selarrrow');

        // 云数据
        if(p.datatype == 'json') {
            $.each(slt, function(k, v) {
                $.each(p.rawData, function(i, row) {
                    if(row[id] == v) {
                        ret.push(row);
                    }
                });
            });
        }

        // 本地数据
        if(p.datatype == 'local') {
            $.each(slt, function(k, v) {
                var row = $me.jqGrid('getRowData', v);
                ret.push(row);
            });
        }

        return ret;
　　},
    getColsTotalWidth: function(model) {
        var colsTotalWidth = 0;
        var j = 0;
        for (var i = 0; model[i]; i++) {
            if(model[i].hidden == true || model[i].minWidth) {
                continue;
            }
            j++;
            colsTotalWidth += parseInt(model[i].width);
        }
        // 大于一个栏目加上滚动条宽度
        if(j > 0) {
            colsTotalWidth = colsTotalWidth + this[0].p.scrollOffset;
        }
        return colsTotalWidth;
    },
    setColsWidth: function() {
        return this.each(function() {
            var me = $(this);
            var model = me.getGridParam('colModel');

            // 计算字段总宽度
            var colsTotalWidth = me.jqGrid('getColsTotalWidth', model);

            // 获取一个栏目宽度尺寸，这里在bt3的对话框弹出会多出2px
            var columnWidth = this.p.width - colsTotalWidth - 0;
            
            // 是否调整
            var resizing = false;

            for (var i = 0; model[i]; i++) {
                if(model[i].minWidth) {
                    resizing = true;
                    columnWidth = columnWidth < model[i].minWidth ? model[i].minWidth : columnWidth;
                    this.grid.resizing = {idx: i};
                    this.grid.headers[i].newWidth = columnWidth;
                }
            }
            if(resizing) {
                // 调整column宽度
                this.grid.dragEnd();
            }
            
        });
    },
    setPanelHeight: function(height) {
        return this.each(function() {
            var $t = this;
            $t.p.panelHeight = height;
        });
    },
    resizeGrid: function (timeout) {
	},
    setFrozenColumns: function() {
		return this.each(function() {
			if ( !this.grid ) {return;}
			var $t = this, cm = $t.p.colModel,i=0, len = cm.length, maxfrozen = -1, frozen= false,
			hd= $($t).jqGrid('getStyleUI',$t.p.styleUI+".base",'headerDiv', true, 'ui-jqgrid-hdiv'),
			hover = $($t).jqGrid('getStyleUI',$t.p.styleUI+".common",'hover', true);
			// TODO treeGrid and grouping  Support
			if($t.p.subGrid === true || $t.p.treeGrid === true || $t.p.cellEdit === true || $t.p.sortable || $t.p.scroll )
			{
				return;
			}
			if($t.p.rownumbers) { i++; }
			if($t.p.multiselect) { i++; }
			
			// get the max index of frozen col
			while(i<len)
			{
				// from left, no breaking frozen
				if(cm[i].frozen === true)
				{
					frozen = true;
					maxfrozen = i;
				} else {
					break;
				}
				i++;
			}
			if( maxfrozen>=0 && frozen) {
				var top = $t.p.caption ? $($t.grid.cDiv).outerHeight() : 0,
				hth = $(".ui-jqgrid-htable","#gview_"+$.jgrid.jqID($t.p.id)).height();
				//headers
				if($t.p.toppager) {
					top = top + $($t.grid.topDiv).outerHeight();
				}
				if($t.p.toolbar[0] === true) {
					if($t.p.toolbar[1] !== "bottom") {
						top = top + $($t.grid.uDiv).outerHeight();
					}
				}
				$t.grid.fhDiv = $('<div style="position:absolute;' + ($t.p.direction === "rtl" ? 'right:0;' : 'left:0;') + 'top:'+(top)+'px;height:'+(hth + 1) + 'px;" class="frozen-div ' + hd +'"></div>');
				$t.grid.fbDiv = $('<div style="position:absolute;' + ($t.p.direction === "rtl" ? 'right:0;' : 'left:0;') + 'top:'+(parseInt(top,10)+parseInt(hth,10) + 1)+'px;overflow-y:hidden" class="frozen-bdiv ui-jqgrid-bdiv"></div>');
				$("#gview_"+$.jgrid.jqID($t.p.id)).append($t.grid.fhDiv);
				var htbl = $(".ui-jqgrid-htable","#gview_"+$.jgrid.jqID($t.p.id)).clone(true);
				// groupheader support - only if useColSpanstyle is false
				if($t.p.groupHeader) {
					$("tr.jqg-first-row-header, tr.jqg-third-row-header", htbl).each(function(){
						$("th:gt("+maxfrozen+")",this).remove();
					});
					var swapfroz = -1, fdel = -1, cs, rs;
					$("tr.jqg-second-row-header th", htbl).each(function(){
						cs= parseInt($(this).attr("colspan"),10);
						rs= parseInt($(this).attr("rowspan"),10);
						if(rs) {
							swapfroz++;
							fdel++;
						}
						if(cs) {
							swapfroz = swapfroz+cs;
							fdel++;
						}
						if(swapfroz === maxfrozen) {
							fdel = maxfrozen;
							return false;
						}
					});
					if(swapfroz !== maxfrozen) {
						fdel = maxfrozen;
					}
					$("tr.jqg-second-row-header", htbl).each(function(){
						$("th:gt("+fdel+")",this).remove();
					});
				} else {
					$("tr",htbl).each(function(){
						$("th:gt("+maxfrozen+")",this).remove();
					});
				}
				$(htbl).width(1);
				if(!$.jgrid.msie()) { $(htbl).css("height","100%"); }
				// resizing stuff
				$($t.grid.fhDiv).append(htbl)
				.mousemove(function (e) {
					if($t.grid.resizing){ $t.grid.dragMove(e);return false; }
				});
				if($t.p.footerrow) {
					var hbd = $(".ui-jqgrid-bdiv","#gview_"+$.jgrid.jqID($t.p.id)).height();

					$t.grid.fsDiv = $('<div style="position:absolute;left:0px;top:'+(parseInt(top,10)+parseInt(hth,10) + parseInt(hbd,10)+1)+'px;" class="frozen-sdiv ui-jqgrid-sdiv"></div>');
					$("#gview_"+$.jgrid.jqID($t.p.id)).append($t.grid.fsDiv);
					var ftbl = $(".ui-jqgrid-ftable","#gview_"+$.jgrid.jqID($t.p.id)).clone(true);
					$("tr",ftbl).each(function(){
						$("td:gt("+maxfrozen+")",this).remove();
					});
					$(ftbl).width(1);
					$($t.grid.fsDiv).append(ftbl);
				}
				$($t).on('jqGridResizeStop.setFrozenColumns', function (e, w, index) {
					var rhth = $(".ui-jqgrid-htable",$t.grid.fhDiv);
					$("th:eq("+index+")",rhth).width( w ); 
					var btd = $(".ui-jqgrid-btable",$t.grid.fbDiv);
					$("tr:first td:eq("+index+")",btd).width( w );
					if($t.p.footerrow) {
						var ftd = $(".ui-jqgrid-ftable",$t.grid.fsDiv);
						$("tr:first td:eq("+index+")",ftd).width( w );
					}
				});
				
				// data stuff
				//TODO support for setRowData
				$("#gview_"+$.jgrid.jqID($t.p.id)).append($t.grid.fbDiv);
				
				$($t.grid.fbDiv).on('mousewheel DOMMouseScroll', function (e) {
					var st = $($t.grid.bDiv).scrollTop();
					if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
						//up
						$($t.grid.bDiv).scrollTop( st - 25 );
					} else {
						//down
						$($t.grid.bDiv).scrollTop( st + 25 );
					}
					e.preventDefault();
				});
				
				if($t.p.hoverrows === true) {
					$("#"+$.jgrid.jqID($t.p.id)).off('mouseover mouseout');
				}
				$($t).on('jqGridAfterGridComplete.setFrozenColumns', function() {
					$("#"+$.jgrid.jqID($t.p.id)+"_frozen").remove();
					$($t.grid.fbDiv).height($($t.grid.bDiv).height()-18);
					// find max height
					var mh = [];
					$("#"+$.jgrid.jqID($t.p.id) + " tr[role=row].jqgrow").each(function(){
						mh.push($("td:visible:first", this).height());
					});

					var btbl = $("#"+$.jgrid.jqID($t.p.id)).clone(true);
					$("tr[role=row]",btbl).each(function(){
						$("td[role=gridcell]:gt("+maxfrozen+")",this).remove();
					});

					$(btbl).width(1).attr("id",$t.p.id+"_frozen");
					$($t.grid.fbDiv).append(btbl);
					// set the height
					$("tr[role=row].jqgrow",btbl).each(function(i, n){
						$("td:not(.jqgrid-rownum):visible:first", this).height( mh[i] );
					});

					if($t.p.hoverrows === true) {
						$("tr.jqgrow", btbl).hover(
							function(){ $(this).addClass( hover ); $("#"+$.jgrid.jqID(this.id), "#"+$.jgrid.jqID($t.p.id)).addClass( hover ); },
							function(){ $(this).removeClass( hover ); $("#"+$.jgrid.jqID(this.id), "#"+$.jgrid.jqID($t.p.id)).removeClass( hover ); }
						);
						$("tr.jqgrow", "#"+$.jgrid.jqID($t.p.id)).hover(
							function(){ $(this).addClass( hover ); $("#"+$.jgrid.jqID(this.id), "#"+$.jgrid.jqID($t.p.id)+"_frozen").addClass( hover );},
							function(){ $(this).removeClass( hover ); $("#"+$.jgrid.jqID(this.id), "#"+$.jgrid.jqID($t.p.id)+"_frozen").removeClass( hover ); }
						);
					}
					btbl=null;
				});
				if(!$t.grid.hDiv.loading) {
					$($t).triggerHandler("jqGridAfterGridComplete");
				}
				$t.p.frozenColumns = true;
			}
		});
	},
    setGroupHeaders: function ( o ) {
		o = $.extend({
			useColSpanStyle :  false,
			groupHeaders: []
		},o  || {});
		return this.each(function(){
			var ts = this,
			i, cmi, skip = 0, $tr, $colHeader, th, $th, thStyle,
			iCol,
			cghi,
			//startColumnName,
			numberOfColumns,
			titleText,
			cVisibleColumns,
			className,
			colModel = ts.p.colModel,
			cml = colModel.length,
			ths = ts.grid.headers,
			$htable = $("table.ui-jqgrid-htable", ts.grid.hDiv),
			$trLabels = $htable.children("thead").children("tr.ui-jqgrid-labels:last").addClass("jqg-second-row-header"),
			$thead = $htable.children("thead"),
			$theadInTable,
			$firstHeaderRow = $htable.find(".jqg-first-row-header"),
			//classes = $.jgrid.styleUI[($t.p.styleUI || 'jQueryUI')]['grouping'],
			base = $.jgrid.styleUI[(ts.p.styleUI || 'jQueryUI')].base;
			if(!ts.p.groupHeader) {
				ts.p.groupHeader = [];
			}
			ts.p.groupHeader.push(o);
			if($firstHeaderRow[0] === undefined) {
				$firstHeaderRow = $('<tr>', {role: "row", "aria-hidden": "true"}).addClass("jqg-first-row-header").css("height", "auto");
			} else {
				$firstHeaderRow.empty();
			}
			var $firstRow,
			inColumnHeader = function (text, columnHeaders) {
				var length = columnHeaders.length, i;
				for (i = 0; i < length; i++) {
					if (columnHeaders[i].startColumnName === text) {
						return i;
					}
				}
				return -1;
			};

			$(ts).prepend($thead);
			$tr = $('<tr>', {role: "row"}).addClass("ui-jqgrid-labels jqg-third-row-header");
			for (i = 0; i < cml; i++) {
				th = ths[i].el;
				$th = $(th);
				cmi = colModel[i];
				// build the next cell for the first header row
				thStyle = { height: '0px', width: ths[i].width + 'px', display: (cmi.hidden ? 'none' : '')};
				$("<th>", {role: 'gridcell'}).css(thStyle).addClass("ui-first-th-"+ts.p.direction).appendTo($firstHeaderRow);

				th.style.width = ""; // remove unneeded style
				iCol = inColumnHeader(cmi.name, o.groupHeaders);
				if (iCol >= 0) {
					cghi = o.groupHeaders[iCol];
					numberOfColumns = cghi.numberOfColumns;
					titleText = cghi.titleText;
					className = cghi.className || "";
					// caclulate the number of visible columns from the next numberOfColumns columns
					for (cVisibleColumns = 0, iCol = 0; iCol < numberOfColumns && (i + iCol < cml); iCol++) {
						if (!colModel[i + iCol].hidden) {
							cVisibleColumns++;
						}
					}

					// The next numberOfColumns headers will be moved in the next row
					// in the current row will be placed the new column header with the titleText.
					// The text will be over the cVisibleColumns columns
					$colHeader = $('<th>').attr({role: "columnheader"})
						.addClass(base.headerBox+ " ui-th-column-header ui-th-"+ts.p.direction+" "+className)
						//.css({'height':'22px', 'border-top': '0 none'})
						.html(titleText);
					if(cVisibleColumns > 0) {
						$colHeader.attr("colspan", String(cVisibleColumns));
					}
					if (ts.p.headertitles) {
						$colHeader.attr("title", $colHeader.text());
					}
					// hide if not a visible cols
					if( cVisibleColumns === 0) {
						$colHeader.hide();
					}

					$th.before($colHeader); // insert new column header before the current
					$tr.append(th);         // move the current header in the next row

					// set the coumter of headers which will be moved in the next row
					skip = numberOfColumns - 1;
				} else {
					if (skip === 0) {
						if (o.useColSpanStyle) {
							// expand the header height to two rows
							$th.attr("rowspan", "2");
						} else {
							$('<th>', {role: "columnheader"})
								.addClass(base.headerBox+" ui-th-column-header ui-th-"+ts.p.direction)
								.css({"display": cmi.hidden ? 'none' : ''})
								.insertBefore($th);
							$tr.append(th);
						}
					} else {
						// move the header to the next row
						//$th.css({"padding-top": "2px", height: "19px"});
						$tr.append(th);
						skip--;
					}
				}
			}
			$theadInTable = $(ts).children("thead");
			$theadInTable.prepend($firstHeaderRow);
			$tr.insertAfter($trLabels);
			$htable.append($theadInTable);

			if (o.useColSpanStyle) {
				// Increase the height of resizing span of visible headers
				$htable.find("span.ui-jqgrid-resize").each(function () {
					var $parent = $(this).parent();
					if ($parent.is(":visible")) {
						this.style.cssText = 'height: ' + $parent.height() + 'px !important; cursor: col-resize;';
					}
				});

				// Set position of the sortable div (the main lable)
				// with the column header text to the middle of the cell.
				// One should not do this for hidden headers.
				$htable.find("div.ui-jqgrid-sortable").each(function () {
					var $ts = $(this), $parent = $ts.parent();
					if ($parent.is(":visible") && $parent.is(":has(span.ui-jqgrid-resize)")) {
						// minus 4px from the margins of the resize markers
                        // 这里原本是，$parent.height()，但是发现有问题
                        var t = $ts.outerHeight();
                        var p = $parent.height();
                        if(p > t) {
                            p = p - 8;
                        }
                        $ts.css('top', (p - t) / 2  - 0 +  'px');
					}
				});
			}

			$firstRow = $theadInTable.find("tr.jqg-first-row-header");
			$(ts).on('jqGridResizeStop.setGroupHeaders', function (e, nw, idx) {
				$firstRow.find('th').eq(idx)[0].style.width = nw + "px";
			});
		});				
	},
    setGridHeight: function(nh) {
		return this.each(function() {
			var $t = this;
			if(!$t.grid) {return;}
			var bDiv = $($t.grid.bDiv);
			bDiv.css({height: nh+(isNaN(nh)?"":"px")});
			if($t.p.frozenColumns === true) {
				//follow the original set height to use 16, better scrollbar width detection
				$('#'+$.jgrid.jqID($t.p.id)+"_frozen").parent().height(bDiv.height() - 18);
			}
			$t.p.height = nh;
			if ($t.p.scroll) { $t.grid.populateVisible(); }
		});
	}
});


/**
 * @preserve
 * bootpag - jQuery plugin for dynamic pagination
 *
 * Copyright (c) 2015 botmonster@7items.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://botmonster.com/jquery-bootpag/
 *
 * Version:  1.0.7
 *
 */
(function($, window) {

    $.fn.bootpag = function(options) {

        var $owner = this,
            settings = $.extend({
                total: 0,
                page: 1,
                rowList: [],
                rowNum: 25,
                maxVisible: null,
                leaps: true,
                href: 'javascript:;',
                hrefVariable: '{{number}}',
                next: '&raquo;',
                prev: '&laquo;',
				firstLastUse: false,
                first: '<span aria-hidden="true">&larr;</span>',
                last: '<span aria-hidden="true">&rarr;</span>',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
		        lastClass: 'last',
                firstClass: 'first'
            },
            $owner.data('settings') || {},
            options || {});

        if(settings.total <= 0)
            //return this;

          if(!$.isNumeric(settings.maxVisible) && !settings.maxVisible){
            settings.maxVisible = parseInt(settings.total, 10);
        }

        $owner.data('settings', settings);

        function renderPage($bootpag, page){

            page = parseInt(page, 10);
            var lp,
                maxV = settings.maxVisible == 0 ? 1 : settings.maxVisible,
                step = settings.maxVisible == 1 ? 0 : 1,
                vis = Math.floor((page - 1) / maxV) * maxV,
                $page = $bootpag.find('li[data-lp]');
            settings.page = page = page < 0 ? 0 : page > settings.total ? settings.total : page;
            $page.removeClass(settings.activeClass);
            lp = page - 1 < 1 ? 1 :
                    settings.leaps && page - 1 >= settings.maxVisible ?
                        Math.floor((page - 1) / maxV) * maxV : page - 1;

			if(settings.firstLastUse) {
				$page
					.first()
					.toggleClass(settings.disabledClass, page === 1);
			}

			var lfirst = $page.first();
			if(settings.firstLastUse) {
				lfirst = lfirst.next();
			}

			lfirst
                .toggleClass(settings.disabledClass, page === 1)
                .attr('data-lp', lp)
                .find('a').attr('href', href(lp));

            var step = settings.maxVisible == 1 ? 0 : 1;

            lp = page + 1 > settings.total ? settings.total :
                    settings.leaps && page + 1 < settings.total - settings.maxVisible ?
                        vis + settings.maxVisible + step: page + 1;

			var llast = $page.last();
			if(settings.firstLastUse) {
				llast = llast.prev();
			}

			llast
                .toggleClass(settings.disabledClass, page === settings.total)
                .attr('data-lp', lp)
                .find('a').attr('href', href(lp));

			$page
				.last()
				.toggleClass(settings.disabledClass, page === settings.total);


            var $currPage = $page.filter('[data-lp='+page+']');

			var clist = "." + [settings.nextClass,
							   settings.prevClass,
                               settings.firstClass,
                               settings.lastClass].join(",.");
            if(!$currPage.not(clist).length){
                var d = page <= vis ? -settings.maxVisible : 0;
                $page.not(clist).each(function(index){
                    lp = index + 1 + vis + d;
                    $(this)
                        .attr('data-lp', lp)
                        .toggle(lp <= settings.total)
                        .find('a').html(lp).attr('href', href(lp));
                });
                $currPage = $page.filter('[data-lp='+page+']');
            }
            $currPage.not(clist).addClass(settings.activeClass);
            $owner.data('settings', settings);
        }

        function href(c){
            return settings.href.replace(settings.hrefVariable, c);
        }

        return this.each(function() {

            var $bootpag, lp, me = $(this),
                p = ['<ul class="', settings.wrapClass, ' bootpag">'];
            
            if(settings.rowList.length) {
                p = p.concat('<li><select class="form-control input-inline">');
                $.each(settings.rowList, function(i, list) {
                    var selected = settings.rowNum == list ? ' selected="selected"' : '';
                    p = p.concat(['<option value="',list,'" ',selected,'>',list,'</option>']);
                });
                p = p.concat('</select></li>');
            }
            
            p = p.concat(['<li><span>共',settings.records,'条记录 ',settings.page,'/',settings.total,'页</span></li>']);

            if(settings.firstLastUse){
                p = p.concat(['<li data-lp="1" class="', settings.firstClass,
                       '"><a href="', href(1), '">', settings.first, '</a></li>']);
            }
            if(settings.prev){
                p = p.concat(['<li data-lp="1" class="', settings.prevClass,
                       '"><a href="', href(1), '">', settings.prev, '</a></li>']);
            }
            for(var c = 1; c <= Math.min(settings.total, settings.maxVisible); c++){
                p = p.concat(['<li data-lp="', c, '"><a href="', href(c), '">', c, '</a></li>']);
            }
            if(settings.next){
                lp = settings.leaps && settings.total > settings.maxVisible
                    ? Math.min(settings.maxVisible + 1, settings.total) : 2;
                p = p.concat(['<li data-lp="', lp, '" class="',
                             settings.nextClass, '"><a href="', href(lp),
                             '">', settings.next, '</a></li>']);
            }
            if(settings.firstLastUse){
                p = p.concat(['<li data-lp="', settings.total, '" class="last"><a href="',
                             href(settings.total),'">', settings.last, '</a></li>']);
            }
            p.push('</ul>');
            me.find('ul.bootpag').remove();
            me.append(p.join(''));
            $bootpag = me.find('ul.bootpag');

            me.find('select').change(function() {
                $(this).val($(this).val());
                $owner.trigger('rowList', $(this).val());
            });

            me.find('li[data-lp] a').click(function() {

                var me = $(this).parent();
                if(me.hasClass(settings.disabledClass) || me.hasClass(settings.activeClass)) {
                    return;
                }
                var page = parseInt(me.attr('data-lp'), 10);
                $owner.find('ul.bootpag').each(function() {
                    renderPage($(this), page);
                });

                $owner.trigger('page', page);
            });

            renderPage($bootpag, settings.page);
        });
    }

})(jQuery, window);