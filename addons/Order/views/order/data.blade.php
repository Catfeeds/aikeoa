<script type="text/javascript">
var editor = {{json_encode($flow['fields'])}};
var delivery_time = "{{(int)$order['delivery_time']}}";
/*
var t = null;
var columns = [{
    label: '编号',
    name: 'id',
    hidden: true
},{
    label: '订单类型',
    index: 'type',
    name: 'type',
    dataType: 'string',
    align: 'center',
    width: 90
},{
    label: '产品类别',
    index: 'category_name',
    name: 'category_name',
    dataType: 'string',
    width: 100
},{
    label: '产品名称',
    index: 'name',
    name: 'name',
    dataType: 'string',
    width: 160
},{
    label: '产品规格',
    index: 'spec',
    name: 'spec',
    dataType: 'string',
    width: 90
},{
    label: '产品条码',
    index: 'barcode',
    name: 'barcode',
    dataType: 'string',
    width: 120
},{
    label: '产品单位',
    index: 'unit',
    name: 'unit',
    dataType: 'string',
    align: 'center',
    width: 90
},{
    label: '订单基点',
    index: 'level_amount',
    name: 'level_amount',
    dataType: 'string',
    align: 'right',
    width: 90
},{
    label : '产品单价',
    index : 'price',
    name: 'price',
    dataType: 'string',
    align: 'right',
    width: 90
},{
    label: '订单数量',
    index: 'amount',
    name: 'amount',
    dataType: 'float',
    formatter: 'number',
    align: 'right',
    width: 90,
    editable: true,
    summaryType: 'sum'
},{
    label: '历史月量1.5',
    index: 'history_number',
    name: 'history_number',
    dataType: 'int',
    align: 'right',
    width: 110,
    cellattr: function(rowId, val, row, cm, rdata) {
        // 历史月销小于1.5
        if ((row.history_number * 3) < row.amount) {
            return " style='color:red;font-weight:bold'";
        }
    },
    summaryType: 'sum'
},{
    label: '订单金额',
    index: 'money',
    name: 'money',
    dataType: 'float',
    align: 'right',
    formatter: 'number',
    width: 90,
    summaryType: 'sum'
},{
    label: '运费金额',
    index: 'freight_money',
    name: 'freight_money',
    dataType: 'float',
    formatter: 'number',
    align: 'right',
    width: 90,
    summaryType: 'sum'
},{
    label: '订单重量(吨)',
    index: 'weight',
    name: 'weight',
    dataType: 'float',
    formatter: 'number',
    align:'right',
    width: 105,
    summaryType: 'sum'
},{
    label: '实发数量',
    index: 'fact_amount',
    name: 'fact_amount',
    dataType: 'float',
    formatter: 'number',
    align: 'right',
    width: 90,
    cellattr: function(rowId, val, row) {
        // 实发数量为0红色显示
        if (delivery_time > 0 && val == 0) {
            return " style='color:red;font-weight:bold'";
        }
    },
    summaryType: 'sum'
},{
    label: '实发金额',
    index: 'fact_money',
    name: 'fact_money',
    dataType: 'float',
    formatter: 'number',
    align: 'right',
    width: 90,
    summaryType: 'sum'
},{
    label: '实发重量(吨)',
    index: 'fact_weight',
    name: 'fact_weight',
    formatter: 'number',
    dataType: 'float',
    align: 'right',
    width: 105,
    summaryType: 'sum'
},{
    label: '差异数量',
    index: 'diff_amount',
    name: 'diff_amount',
    formatter: 'number',
    dataType: 'float',
    align: 'right',
    width: 90,
    summaryType: 'sum'
},{
    label: '支持金额',
    index: 'remark_money',
    name: 'remark_money',
    formatter: 'number',
    dataType: 'float',
    align: 'right',
    width: 90,
    summaryType: 'sum'
},{
    label: '客户库存',
    index: 'inventory',
    name: 'inventory',
    dataType: 'int',
    align: 'right',
    width: 90,
    summaryType: 'sum'
},{
    label: '生产批号',
    index: 'batch_number',
    name: 'batch_number',
    dataType: 'string',
    width: 90
},{
    label: '备注',
    index : 'content',
    name : 'content',
    dataType: 'string',
    width: 85
},{
    label: '合同',
    index: 'contract',
    name: 'contract',
    dataType: 'string',
    align: 'center',
    width: 60
}];

$(function() {
    
    $.each(columns, function(index, item) {
        // 编辑器
        $.each(editor.edit, function(field) {
            if(item.name == field) {
                item['editable'] = true;
            }
        });
        // 隐藏
        $.each(editor.hidden, function(field) {
            if(item.name == field) {
                item['hidden'] = true;
            }
        });
        columns[index] = item;
    });

    var footerCalculate = function(rowid) {
        var sets = {};
        $.each(columns, function(index, item) {
            if(item.summaryType) {
                sets[item.name] = t.getCol(item.name, false, item.summaryType);
            }
        });
        t.footerData('set', sets);
    }

    t = $('#grid-table').jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'GET',
        url: DATA_URL(),
        colModel: columns,
        cellEdit: true,
        cellsubmit: 'clientArray',
        cellurl: '',
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        footerrow: true,
        loadonce: true,
        height: 300,
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
            footerCalculate.call(this);
        },
        // 进入编辑前调用
        beforeEditCell: function(rowid, cellname, value, iRow, iCol) {
            // 编辑前插入class
            $(this.rows[iRow]).find('td').eq(iCol).addClass('edit-cell-item');
        },
        // 进入编辑后调用
        afterEditCell: function(rowid, cellname, value, iRow, iCol) {
        },
        // 保存服务器时调用
        afterRestoreCell: function(rowid, value, iRow, iCol) {
            // 编辑cell后保存时删除class
            $(this.rows[iRow]).find('td').eq(iCol).removeClass('edit-cell-item');
        },
        // 保存在本地的时候调用
        afterSaveCell: function(rowid, cellname, value, iRow, iCol) {
            // 计算页脚数据
            footerCalculate.call(this, rowid);
            // 编辑cell后保存时删除class
            $(this.rows[iRow]).find('td').eq(iCol).removeClass('edit-cell-item');
        }
    });
});

function getPanelHeight() {
    var list = $('#jqgrid-editor-container').position();
    return top.iframeHeight - list.top - 150;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    // 框架改变大小时设置Panel高度
    t.jqGrid('setPanelHeight', getPanelHeight());
    // resize jqgrid大小
    t.jqGrid('resizeGrid');
}
*/

function reloadStore(url) {
    if (url) {
        store.proxy.url = url;
    }
    store.reload();
}
function saveStore() {
    var data = {order_id:{{(int)$order['id']}},updated:[],deleted:[]};
    var updated = store.getUpdatedRecords();
    
    Ext.each(updated, function(record) {
        data.updated.push(record.data);
        record.commit();
    });
    var deleted = store.getRemovedRecords();
    Ext.each(deleted, function(record) {
        data.deleted.push(record.data);
    });

    data.updated = JSON.stringify(data.updated);
    data.deleted = JSON.stringify(data.deleted);

    Ext.Ajax.request({
        url: '{{url("product_edit")}}',
        params: data,
        method:'POST',
        // 默认30秒
        timeout: 2000,
        success: function(response) {
            var res = Ext.decode(response.responseText);
            if (res.status) {
                reloadStore();
                $.toastr('success', '保存编辑成功。');
            } else {
                $.toastr('error', '保存编辑失败。');
                store.rejectChanges();
            }
        }
    });
}

function removeStore() {
    /*
    var id = t.jqGrid('getGridParam', 'selrow');
    product_delete
    $.post('', function(res) {
    });
    */
    var data = grid.getSelectionModel().getSelection();
    Ext.MessageBox.confirm('删除产品', '确定产品删除?', function(btn) {
        if (btn == 'yes') {
            Ext.Array.each(data, function(item) {
                store.remove(item);
            });
            saveStore();
        }
    });
}

var store, grid;

Ext.onReady(function() {

    var columns = [
    	new Ext.grid.RowNumberer(),
    {
        text: '编号',
        dataIndex: 'id',
        dataType: 'int',
        hidden: true,
    },{
        text: '订单类型',
        dataIndex: 'type',
        dataType: 'string',
        align: 'center',
        width: 90
    },{
        text: '产品类别',
        dataIndex: 'category_name',
        dataType: 'string',
        width: 100
    },{
        text: '产品名称',
        dataIndex: 'name',
        dataType: 'string',
        width: 160
    }, {
        text: '产品规格',
        dataIndex: 'spec',
        dataType: 'string',
        width: 90
    }, {
        text: '产品条码',
        dataIndex: 'barcode',
        dataType: 'string',
        width: 120
    }, {
        text: '产品单位',
        dataIndex: 'unit',
        dataType: 'string',
        align: 'center',
        width: 90
    }, {
        text : '产品单价',
        dataIndex : 'price',
        dataType: 'string',
        align: 'right',
        width: 90
    },{
        text: '折扣率',
        dataIndex: 'discount_rate',
        dataType: 'int',
        align: 'center',
        width: 80,
    },{
        text: '订单数量',
        dataIndex: 'amount',
        dataType: 'float',
        align: 'right',
        width: 90,
        summaryType: 'sum'
    },{
        text: '订单折前金额',
        dataIndex: 'money',
        dataType: 'float',
        align: 'right',
        width: 120,
        summaryType: 'sum'
    },{
        text: '订单折扣金额',
        dataIndex: 'money_discount',
        dataType: 'float',
        align: 'right',
        width: 120,
        summaryType: 'sum'
    },{
        text: '订单金额',
        dataIndex: 'money_after',
        dataType: 'float',
        align: 'right',
        width: 90,
        summaryType: 'sum'
    },{
        text: '订单重量(吨)',
        dataIndex: 'weight',
        dataType: 'float',
        align:'right',
        width: 110,
        summaryType: 'sum'
    },{
        text: '发货数量',
        dataIndex: 'fact_amount',
        dataType: 'float',
        align: 'right',
        width: 90,
        renderer: function(value, metaData, record) {
            var val = Ext.util.Format.number(value, '0.00');
            // 实发数量为0红色显示
            if (delivery_time > 0 && value == 0) {
                return '<span style="color:red;font-weight:bold;">' +val+ '</span>';
            } else {
                return val;
            }
        },
        summaryType: 'sum'
    },{
        text: '发货折前金额',
        dataIndex: 'fact_money',
        dataType: 'float',
        align: 'right',
        width: 120,
        summaryType: 'sum'
    },{
        text: '发货折扣金额',
        dataIndex: 'fact_money_discount',
        dataType: 'float',
        align: 'right',
        width: 120,
        summaryType: 'sum'
    },{
        text: '发货金额',
        dataIndex: 'fact_money_after',
        dataType: 'float',
        align: 'right',
        width: 90,
        summaryType: 'sum'
    },{
        text: '发货重量(吨)',
        dataIndex: 'fact_weight',
        dataType: 'float',
        align: 'right',
        width: 110,
        summaryType: 'sum'
    },{
        text: '差异数量',
        dataIndex: 'diff_amount',
        dataType: 'float',
        align: 'right',
        width: 90,
        summaryType: 'sum'
    },{
        text: '历史月量1.5',
        dataIndex: 'history_number',
        dataType: 'int',
        align: 'right',
        width: 110,
        renderer: function(value, metaData, record) {
            // 历史月销小于1.5
            if ((record.data.history_number * 3) < record.data.amount) {
                return '<span style="color:red;font-weight:bold;">' +value+ '</span>';
            } else {
                return value;
            }
        },
        summaryType: 'sum'
    },{
        text: '生产批号',
        dataIndex: 'batch_number',
        dataType: 'string',
        width: 90
    },{
        text: '备注',
        dataIndex : 'remark',
        dataType: 'string',
        width: 85
    },{
        text: '促销备注',
        dataIndex : 'promotion_remark',
        dataType: 'string',
        width: 85
    }/*,{
        text: '合同',
        dataIndex: 'contract',
        dataType: 'string',
        align: 'center',
        width: 60
    }*/];

    var editor = {{json_encode($flow['fields'])}};
    var fields = [];
    Ext.Array.each(columns, function(item, index) {

        if(item.dataType == 'int') {
            item['renderer'] = item.renderer ? item.renderer : Ext.util.Format.numberRenderer('0');
        }
        if(item.dataType == 'float') {
            item['renderer'] = item.renderer ? item.renderer : Ext.util.Format.numberRenderer('0.00');
        }

        // 编辑器
        Ext.Array.each(editor.edit, function(field) {

            if(item.dataIndex == field) {
                item['editor'] = {
                    //allowBlank: false
                }
            }
        });

        // 隐藏
        Ext.Array.each(editor.hidden, function(field) {
            if(item.dataIndex == field) {
                item['hidden'] = true;
            }
        });

        // 合计计算格式
        if(item.summaryType) {

            if(item.dataType == 'int') {
                item['summaryRenderer'] = Ext.util.Format.numberRenderer('0')
            }
            if(item.dataType == 'float') {
                item['summaryRenderer'] = Ext.util.Format.numberRenderer('0.00')
            }
        }

        // 生成模型字段
        fields.push({
            name: item.dataIndex,
            type: item.dataType
        });

        columns[index] = item;
    });

    Ext.define('Product', {
        extend: 'Ext.data.Model',
        fields: fields,
    });

    store = new Ext.data.Store({
        model: 'Product',
        proxy: {
            type: 'ajax',
            url: DATA_URL(),
            reader: {
                type : 'json',
                root : 'rows'
            }
        },
        listeners: {},
        autoLoad: false
    });

    var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });

    grid = Ext.create('Ext.grid.Panel', {
        //frame: true,
        height: 320,
        width: '100%',
        border: true,
        style:'background-color:#fff;',
        store : store,
        renderTo: 'dd',
        plugins: [cellEditing],
        loadMask: {
            msg: '正在加载数据，请稍侯...'
        },
        columnLines : true,
        features: [{
            ftype: 'summary',
            dock: 'bottom'
        }],
        columns: {
            defaults: {
                menuDisabled: true,
                //sortable: false,
            }, items: columns
        }
        //tbar: tbar
    });
    store.load();
});
</script>

<div id="dd"></div>

<!--
<div id="jqgrid-editor-container" class="m-t m-b">
    <table id="grid-table"></table>
</div>
-->