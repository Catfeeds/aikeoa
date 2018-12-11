<table id="product-category-box"></table>

<script type="text/javascript">
var productCategoryBox = $('#product-category-box');
var params = {exclude:'{{$gets["exclude"]}}',category_id:0,search_key:'',search_value:''};
$(document).ready(function() {
    productCategoryBox.datagrid({
        border:0,
        pagination:true,
        toolbar:'#product-category-box-toolbar',
        singleSelect:true,
        checkOnSelect:false,
        textField:'text',
        idField:'id',
        url:'{{url()}}',
        queryParams:params,
        columns:[[
            {field:'checkbox',checkbox:true,align:'center'},
            {field:'text',sortable:true,title:'名称',width:200},
            {field:'id',title:'编号',width:30,sortable:true,align:'center'}
        ]],
        fitColumns:true,
        fit:true,
        onLoadSuccess:function(data) {
            var value = document.getElementById('{{$gets["id"]}}_id').value.split(',');
            for (var i = 0; i < value.length; i++) {
                productCategoryBox.datagrid('selectRecord',value[i]);
            }
        },
        onCheckAll:function() {
            productCategorySelectRows();
        },
        onUncheckAll:function() {
            productCategorySelectRows();
        },
        onCheck:function() {
            productCategorySelectRows();
        },
        onUncheck:function() {
            productCategorySelectRows();
        },
        onClickRow:function() {
            productCategorySelectRows();
        }
    });
});

function productCategorySelectGets() {
    return productCategoryBox.datagrid('getSelections');
}

function productCategorySelectRows() {
    var id = [],text = [];
    var rows = productCategoryBox.datagrid('getSelections');
    for (var i = 0; i < rows.length; i++) {
        id.push(rows[i].id);
        text.push(rows[i].text);
    }
    $('#{{$gets["id"]}}_id').val(id.join(','));
    $('#{{$gets["id"]}}_text').text(text.join(','));
}
</script>
