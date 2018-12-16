<script>

var t = null;
var validate = {{json_encode($validate)}};

$(function() {

    var models = {{json_encode($models)}};

    var footerCalculate = function() {
        var quantity = $(this).getCol('quantity', false, 'sum');
        $(this).footerData('set',{product_name:'合计:', quantity: quantity});
    }

    t = $('#grid-table').jqGrid({
        caption: '',
        datatype: 'local',
        colModel: models,
        cellEdit: true,
        cellsubmit: 'clientArray',
        cellurl: '',
        multiselect: false,
        viewrecords: true,
        rownumbers: true,
        footerrow: true,
        height: 452,
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
                            mapField: {product_id: 'id', stock_total: 'stock_total', product_name: 'text'},
                            suggest: {
                                url: 'product/product/dialog_jqgrid',
                                params: {stock:'a', owner_id:'{{auth()->id()}}', type: 1, order:'asc', limit:1000}
                            },
                            dialog: {
                                title: '产品管理',
                                url: 'product/product/dialog_jqgrid',
                                params: {stock:'a', owner_id:'{{auth()->id()}}', type: 1}
                            }
                        })
                    }
                });
            }

            /*
            if(cellname == 'description') {
                table.setColProp(cellname, {
                    editoptions: {
                        dataInit: $.jgrid.celledit.dropdown({
                            valueField: 'id',
                            textField: 'text'
                        })
                    }
                });
            }
            */
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

    /*
    table.keydown(function(e) {
      switch (e.which) {
        case 40: // down
          var $grid = $(this),
            $td = $(e.target).closest("tr.jqgrow>td"),
            $tr = $td.closest("tr.jqgrow"),//$td.parent()
            rowid = $tr.attr("id"),
            $trNext = $tr.next("tr.jqgrow"),
            p = $grid.jqGrid("getGridParam"),
            cm = $td.length > 0 ? p.colModel[$td[0].cellIndex] : null;
          var cmName = cm !== null && cm.editable ? cm.name : 'PackCartonNo';
          var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
          if (selectedRowId == null || rowid !== selectedRowId) { return; }

          // this is the DOM of table and $tr[0] is DOM of tr
          if ($trNext.length < 1) { return; }

		  var rowidNext = $trNext.attr("id");
          $grid.jqGrid('saveRow', rowid, {
          	aftersavefunc: function () {
              $(this).jqGrid("setSelection", rowidNext, false)
                .jqGrid("editRow", rowidNext, {
                  keys: true,
                  focusField: cmName
                });
            }
          });

          e.preventDefault();
          break;

        default:
          return;
      }
    });
    */

    // 初始化行数据
    for(var i=1; i <= 15; i++) {
 	    t.jqGrid('addRowData', i, {});
    }
});

/* 
 * 保存数据
*/
function saveData() {

    var forms = ['number', 'date', 'remark', 'user', 'type_id', 'type'];

    var data = {};

    var passed = true;

    $.each(forms, function(i, key) {

        var value = $('#' + key).val() || '';
        data[key] = value;

        // 简单验证表单
        if(validate.rules[key] == 'required') {
            if(value == '') {
                $.toastr('error', validate.attrs[key] + ': 不能为空。');
                passed = false;
                return false;
            }
        }

    });

    if(passed) {
        var products = t.jqGrid('getRowsData');
        if(products.v === true) {
            if(products.data.length === 0) {
                $.toastr('error', '产品列表不能为空。');
            } else {
                data['products'] = products.data;
                $.post('{{url("store")}}', data, function(res) {
                    location.href = '{{url_referer("index")}}';
                });
            }
        }
    }
}

</script>

<div class="panel">

    <div class="table-responsive">
    <table class="table table-form m-b-none">

        <tr>
            <td align="right" width="10%">单据编号</td>
            <td align="left" width="40%">
                <input type="text" id="number" value="{{$stock['number']}}" class="form-control input-sm input-inline">
            </td>
            <td align="right" width="10%">单据时间</td>
            <td align="left" width="40%">
                <input type="text" data-toggle="date" value="{{date('Y-m-d')}}" id="date" class="form-control input-sm input-inline">
            </td>
        </tr>

        <tr>
            <td align="right">库存类型</td>
            <td align="left">
                <select id='type_id' name='type_id' class="form-control input-sm input-inline">
                    <option value=""> - </option>
                    @if($types) 
                    @foreach($types as $k => $v)
                        <option value="{{$v['id']}}" @if($selects['select']['type'] == $k) selected @endif>{{$v['title']}}</option>
                    @endforeach 
                    @endif
                </select>
            </td>
            <td align="right">制单人</td>
            <td align="left"><input type="text" id="user" class="form-control input-sm input-inline" value="{{auth()->user()->nickname}}"></td>
        </tr>

        <tr>
            <td colspan="4">
                <div class="form-jqgrid" id="jqgrid-editor-container">
                    <table id="grid-table"></table>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="4">
                <textarea class="form-control" rows="2" type="text" id="remark" placeholder="暂无备注">{{$row['remark']}}</textarea>
            </td>
        </tr>

    </table>
    </div>

    <div class="panel-footer">
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        <button type="button" onclick="saveData();" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </div>

</div>