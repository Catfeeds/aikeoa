<script>

var t = null;
var rows = {{$data}};

$(function() {

    var models = {{json_encode($models)}};

    var footerCalculate = function() {
        var quantity = $(this).getCol('quantity', false, 'sum');
        $(this).footerData('set',{goods_name:'合计:', quantity: quantity});
    }

    t = $('#grid-table').jqGrid({
        caption: '',
        data: rows,
        datatype: 'local',
        colModel: models,
        cellEdit: true,
        cellsubmit: 'clientArray',
        cellurl: '',
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        footerrow: true,
        height: getPanelHeight(),
        gridComplete: function() {
            footerCalculate.call(this);
        },
        rowattr: function(row) {
            // 附加tr样式
            if (row.id > 0) {
                return {'class': 'edited'};
            }
        },
        // 进入编辑前调用
        beforeEditCell: function(rowid, cellname, value, iRow, iCol) {

            // 编辑前插入class
            $("#" + rowid).find('td').eq(iCol).addClass('edit-cell-item');

            if(cellname == 'goods_name') {
                t.setColProp(cellname, {
                    editoptions: {
                        dataInit: $.jgrid.celledit.dialog({
                            srcField: 'goods_id',
                            mapField: {goods_id: 'id', goods_name: 'text'},
                            suggest: {
                                url: 'supplier/product/dialog_jqgrid',
                                params: {owner_id:'{{auth()->id()}}', order:'asc', limit:1000}
                            },
                            dialog: {
                                title: '商品管理',
                                url: 'supplier/product/dialog_jqgrid',
                                params: {owner_id:'{{auth()->id()}}'}
                            }
                        })
                    }
                });
            }
        },
        // 进入编辑后调用
        afterEditCell: function(rowid, cellname, value, iRow, iCol) {
        },
        // 保存服务器时调用
        afterRestoreCell: function(rowid, value, iRow, iCol) {
            // 编辑cell后保存时删除class
            $("#" + rowid).find('td').eq(iCol).removeClass('edit-cell-item');
        },
        // 保存在本地的时候调用
        afterSaveCell: function(rowid, cellname, value, iRow, iCol) {
            // 计算页脚数据
            footerCalculate.call(this);
            // 编辑cell后保存时删除class
            $("#" + rowid).find('td').eq(iCol).removeClass('edit-cell-item');
        }
    });

    // 初始化行数据
    if(rows.length == 0) {
        for(var i=1; i <= 15; i++) {
 	        t.jqGrid('addRowData', i, {});
        }
    }

});

function getPanelHeight() {
    var list = $('#jqgrid-editor-container').position();
    return top.iframeHeight - list.top - 140;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    t.jqGrid('setGridHeight', getPanelHeight());
}

/* 
 * 保存数据
*/
function saveData() {

    var data = {};

    var product_id = $('#product_id').val();

    if(product_id == '') {
        $.toastr('error', '产品不能为空。');
        return false;
    }

    data.product_id = product_id;

    var rows = t.jqGrid('getRowsData');

    if(rows.v === true) {
        if(rows.data.length === 0) {
            $.toastr('error', '商品列表不能为空。');
        } else {
            data['rows'] = rows.data;
            $.post('{{url("store")}}', data, function(res) {
                $.toastr('success', 'BOM单保存成功。');
            });
        }
    }
}

</script>

<div class="panel m-b-none">

    <div class="wrapper">
        <div id="jqgrid-editor-container">
            <table id="grid-table"></table>
        </div>
    </div>
</div>
<div class="panel-footer">
    <input type="hidden" id="product_id" name="product_id" value="{{$product_id}}">
    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    <button type="button" onclick="saveData();" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
</div>