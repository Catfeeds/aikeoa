<div class="panel">

<table class="table">
    <tr>
        <td align="left">

            <form id="myquery" name="myquery" action="{{url()}}" method="get">
            
                <select class="form-control input-inline input-sm" id="warehouse_id" name="warehouse_id" data-toggle="redirect" rel="{{url(null, $query)}}">
                    <option value="0">全部仓库</option>
                    @if($warehouses)
                    @foreach($warehouses as $k => $v)
                        <option value="{{$v['id']}}" @if($query['warehouse_id'] == $v['id']) selected @endif >{{$v['layer_space']}}{{$v['title']}}</option>
                    @endforeach 
                    @endif
                </select>

                <select class="form-control input-inline input-sm" id='category_id' name='category_id' data-toggle="redirect" rel="{{url(null, $query)}}">
                    <option value="0">产品类别</option>
                    @if($categorys) @foreach($categorys as $k => $v)
                        <option value="{{$v['id']}}" @if($query['category_id']==$v['id']) selected="true" @endif >{{$v['layer_space']}}{{$v['name']}}</option>
                    @endforeach @endif
                </select>

                @if(isset($selects['select']['sdate']))
                &nbsp;日期:
                <input type="text" name="sdate" class="form-control input-inline input-sm" data-toggle="date" size="13" id="sdate" value="{{$selects['select']['sdate']}}" readonly>
                -
                <input type="text" name="edate" class="form-control input-inline input-sm" data-toggle="date" size="13" id="edate" value="{{$selects['select']['edate']}}" readonly>
                @endif

                <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
        </td>
    </tr>
</table>

<div class="list-jqgrid">
    <table id="jqgrid"></table>
    <div id="jqgrid-page"></div>
</div>

</div>

<script>
var $table = null;

(function($) {

    var params = {{json_encode($query)}};
    $table = $("#jqgrid");

    var model = [
        {name: "id", hidden: true, index: 'id', label: 'ID', width: 60, align: 'center'},
        {name: "total", width: 60, hidden: true, align: 'left'},
        {name: "name", index: 'name', label: '产品名称', width: 260, align: 'left'},
        {name: "quantity", index: 'quantity', label: '需求总差', width: 100, align: 'right'},
    ];

    var footerCalculate = function() {

        var quantity = $(this).getCol('total', false, 'sum');
        $(this).footerData('set', {quantity: (quantity < 0 ? '<span class="red">'+quantity+'</span>': quantity)});
    }

    $table.jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'GET',
        url: '{{url()}}',
        colModel: model,
        rowNum: 1000,
        autowidth: true,
        multiselect: false,
        viewrecords: true,
        rownumbers: false,
        height: getPanelHeight(),
        footerrow: true,
        postData: params,
        loadonce: true,
        //pager: '#jqgrid-page',
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
            footerCalculate.call(this);
        }
    });

})(jQuery);

function getPanelHeight() {
    var list = $('.panel').position();
    return top.iframeHeight - list.top - 122;
}

// 框架页面改变大小时会调用此方法
function iframeResize() {
    // 框架改变大小时设置Panel高度
    $table.jqGrid('setPanelHeight', getPanelHeight());
    // resize jqgrid大小
    $table.jqGrid('resizeGrid');
}

</script>