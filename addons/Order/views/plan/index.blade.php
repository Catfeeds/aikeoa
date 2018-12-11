<?php

$haeds = ['产品名称','当前库存(件)','客户订单汇总(件)','需求计划汇总(件)','已打款待发(件)','未打款待发(件)'];

$ids = [];
if (count($dealers['dealer'])) {
    foreach ($dealers['dealer'] as $k => $v) {
        $haeds[] = $v;
    }
}

$j = 0;
foreach ($haeds as $i => $haed) {
    $ids[] = $i;

    $column = [
        'name'     => 'order_'.$i,
        'index'    => 'order_'.$i,
        'label'    => $haed,
        'width'    => 140,
        'cellattr' => 'editTwo',
        'sortable' => false,
        'i'        => $j,
    ];

    if ($i == 0) {
        $column['width']  = 220;
        $column['frozen'] = true;
    }

    if ($i > 5) {
        $column['editable'] = true;
        $column['editable'] = true;
    }

    $columns[] = $column;
    $j++;
}

$rows[0] = ['订单号','','','','',''];

$i = 0;
if (count($dealers['info'])) {
    foreach ($dealers['info'] as $k => $v) {
        $i++;
        $rows[0][] = '(<strong class="color:green">'.$v['flow_step_id'].'</strong>)'.$v['number'].'['.$i.']';
    }
}

$rows[1] = ['计划发货时间','','','','',''];

if (count($dealers['plan'])) {
    foreach ($dealers['plan'] as $k => $v) {
        $rows[1][] = $v > 0 ? date('Y-m-d', $v) : '';
    }
}

$rows[2] = ['统计数据',array_sum($inventory),$all['a'],$all['d'],$all['b'],$all['c']];

if (count($dealers['amount'])) {
    foreach ($dealers['amount'] as $k => $v) {
        $rows[2][] = $v;
    }
}

$i = 3;
if (count($products)) {
    foreach ($products as $k => $v) {
        $rows[$i][] = $v['name'].' '.$v['spec'];
        $rows[$i][] = $inventory[$k];
        $rows[$i][] = $moneyall[$k];
        $rows[$i][] = $plans[$k];
        $rows[$i][] = $money[$k];
        $rows[$i][] = $single[$k];

        if (count($res)) {
            $j = 6;
            foreach ($res as $key => $value) {
                if ($value['code'][$k] > 0) {
                    $rows[$i][] = $value['code'][$k];
                } else {
                    $rows[$i][] = '';
                }
                $j++;
            }
        }

        $rows[$i][] = $money[$k];

        $i++;
    }
}

$_rows = [];

foreach ($rows as $i => $row) {
    foreach ($row as $j => $_row) {
        $oid = $ids[$j];
        $_rows[$i]['order_'.$oid] = $_row;
    }
}

$rows = $_rows;

unset($_rows);

?>

<script>
var $table = null;

$.extend($.jgrid, {
    cellattr: {
        editTwo: function (rowId, value, rawObject, cm, rdata) {

            if(rowId == 0 || rowId == 1) {
                return ' style="text-align:center;" class="not-editable-cell"';
            }

            // 只能编辑第二行
            if(rowId == 2) {
                return ' style="text-align:center;"';
            }
            
            if(rowId == 3) {
                if(cm.i) {
                    return ' style="text-align:right;font-weight:bold;" class="not-editable-cell"';
                } else {
                    return ' style="text-align:center;" class="not-editable-cell"';
                }
            } else {
                if(cm.i) {
                    return ' style="text-align:right;" class="not-editable-cell"';
                } else {
                    return ' style="text-align:left;" class="not-editable-cell"';
                }
            }
        }
    }
});

$(function() {

    $table = $('#plan-grid').jqGrid({
        title: '生产计划',
        multiselect: false,
        datatype: 'local',
        loadonce: true,
        rowNum: 1000,
        rownumWidth: 35,
        cellsubmit: 'remote',
        cellurl: '',
        data: <?php echo json_encode($rows, JSON_UNESCAPED_UNICODE); ?>,
        colModel: <?php echo json_encode($columns, JSON_UNESCAPED_UNICODE); ?>,
        height: getPanelHeight(),
        // 进入编辑前调用
        beforeEditCell: function(rowid, cellname, value, iRow, iCol) {
            var me = this;
            // 编辑前插入class
            $("#" + rowid).find('td').eq(iCol).addClass('edit-cell-item');
            $table.setColProp(cellname, {
                editoptions: {
                    dataInit: function (element) {
                        datePicker({el: element, dateFmt: 'yyyy-MM-dd', onpicked: function() {
                            $(me).jqGrid('saveCell', iRow, iCol);
                        }});
                    }
                }
            });
        },
        // 进入编辑后调用
        afterEditCell: function(rowid, cellname, value, iRow, iCol) {
        },
        beforeSubmitCell: function(rowid, name, val, iRow, iCol) {
            var a = name.split('_');
            $table.setGridParam({cellurl:'{{url()}}?order_id=' + a[1] + '&value=' + val});
        },
        afterSubmitCell: function(res, rowid, cellname, value, iRow, iCol) {
            if(res.status == 200) {
                $.toastr('success', '生产计划时间修改成功。', '提醒');
            }
            return [true];
        },
        // 保存服务器时调用
        afterRestoreCell: function(rowid, value, iRow, iCol) {
            // 编辑cell后保存时删除class
            $("#" + rowid).find('td').eq(iCol).removeClass('edit-cell-item');
        },
        // 保存在本地的时候调用
        afterSaveCell: function(rowid, cellname, value, iRow, iCol) {
            // 编辑cell后保存时删除class
            $("#" + rowid).find('td').eq(iCol).removeClass('edit-cell-item');
        }
    });
    $table.jqGrid('setFrozenColumns');
    $table.jqGrid("setGridParam",{cellEdit : true});
});

function getPanelHeight() {
    var list = $('.panel').position();
    return top.iframeHeight - list.top - 108;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    // 框架改变大小时设置Panel高度
    $table.jqGrid('setPanelHeight', getPanelHeight());
    // resize jqgrid大小
    $table.jqGrid('resizeGrid');
}

</script>

<div class="panel">
    <div class="wrapper">
        <form id="myform" class="form-inline" name="myform" action="{{url()}}" method="get">
            
            <select id="warehouse_id" name="warehouse_id" class='form-control input-sm' data-toggle="redirect" rel="{{$query}}">
                <option value="0">产品仓库</option>
                @if($warehouses)
                @foreach($warehouses as $k => $v)
                    <option value="{{$v['id']}}" @if($selects['select']['warehouse_id'] == $v['id']) selected @endif>{{$v['layer_space']}}{{$v['title']}}</option>
                @endforeach 
                @endif
            </select>

            <select id='category_id' name='category_id' class='form-control input-sm' data-toggle="redirect" rel="{{$query}}">
                <option value="0">产品类别</option>
                 @if($categorys) @foreach($categorys as $k => $v)
                    <option value="{{$v['id']}}" @if($selects['select']['category_id'] == $v['id']) selected @endif>{{$v['layer_space']}}{{$v['name']}}</option>
                 @endforeach @endif
            </select>
            <input name="tpl" type="hidden" value="{{$selects['select']['tpl']}}">
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
    </div>
    <div class="list-jqgrid">
        <table id="plan-grid" class="table-condensed"></table>
    </div>
    
</div>