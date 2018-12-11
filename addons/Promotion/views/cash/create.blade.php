<script>
(function ($) {
    var models = JSON.parse('{{json_encode($models)}}');
    var footerCalculate = function() {
        var quantity = $(this).getCol('quantity', false, 'sum');
        $(this).footerData('set',{product_name:'合计:', quantity: quantity});
    }

    var editCombo = {};
    editCombo.type_id = JSON.parse('{{json_encode($order_type)}}');
 
    var t = $('#promotion-cash-table').jqGrid({
        caption: '',
        datatype: 'local',
        colModel: models,
        editCombo: editCombo,
        cellEdit: true,
        cellsubmit: 'clientArray',
        cellurl: '',
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        footerrow: true,
        height: 260,
        gridComplete: function() {
            footerCalculate.call(this);
        },
        // 进入编辑前调用
        beforeEditCell: function(rowid, cellname, value, iRow, iCol) {

            // 编辑前插入class
            $("#" + rowid).find('td').eq(iCol).addClass('edit-cell-item');

            if(cellname == 'product_name') {
                t.setColProp(cellname, {
                    editoptions: {
                        dataInit: $.jgrid.celledit.dialog({
                            srcField: 'product_id',
                            mapField: {product_id: 'id', product_name: 'text', price: 'price'},
                            suggest: {
                                url: 'product/product/dialog_jqgrid',
                                params: {customer_id:'{{$promotion["customer_id"]}}', order:'asc', limit:1000}
                            },
                            dialog: {
                                title: '产品管理',
                                url: 'product/product/dialog_jqgrid',
                                params: {customer_id:'{{$promotion["customer_id"]}}'}
                            }
                        })
                    }
                });
            }

            if(cellname == 'type_id') {
                t.setColProp(cellname, {
                    editoptions: {
                        dataInit: $.jgrid.celledit.dropdown({
                            valueField: 'id',
                            textField: 'text'
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
    for(var i=1; i <= 15; i++) {
        t.jqGrid('addRowData', i, {});
    }
})(jQuery);

</script>

<form class="form-horizontal" method="post" action="{{url()}}" id="promotion-cash-form" name="promotion-cash-form">
    <div class="table-responsive">
        <table class="table table-form m-b-none">
            <tr>
                <td width="15%" align="right">所属促销</td>
                <td align="left">
                    {{Dialog::user('promotion','promotion_id', $promotion['id'], 0, 0)}}
                </td>
                <td align="right">单据编号</td>
                <td align="left">
                    <input type="text" id="sn" name="sn" class="form-control input-sm" value="{{$sn}}">
                </td>
            </tr>
            <tr>
                <td align="right">兑现日期</td>
                <td align="left">
                    <input class="form-control input-sm" data-toggle="date" type="text" name="date" id="date" placeholder="兑现日期" value="{{date('Y-m-d')}}">
                </td>
                <td align="right">制单人</td>
                <td align="left"><input type="text" id="user" name="user" class="form-control input-sm" value="{{auth()->user()->nickname}}" readonly="readonly"></td>
            </tr>

            <tr>
                <td align="right">兑现备注</td>
                <td align="left" colspan="3">
                    <input class="form-control" name="remark" id="remark" />
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <div id="jqgrid-editor-container">
                        <table id="promotion-cash-table"></table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

</form>
