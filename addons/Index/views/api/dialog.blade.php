<style>
.close-header { margin: 0 15px 0; }
</style>

<button type="button" data-dismiss="dialog" class="close close-header">&times;</button>

<ul class="nav nav-tabs padder m-t" id="tabContent">
    <li class="active"><a href="#modal-department" data-toggle="tab">部门</a></li>
    <li><a href="#modal-role" data-toggle="tab">角色</a></li>
    <li><a href="#modal-user" data-toggle="tab">用户</a></li>
    
    <!--
    <li><a href="#modal-customer" data-toggle="tab">客户</a></li>
    <li><a href="#modal-customer-contact" data-toggle="tab">客户联系人</a></li>
    <li><a href="#modal-supplier" data-toggle="tab">供应商</a></li>
    <li><a href="#modal-supplier-contact" data-toggle="tab">供应商联系人</a></li>
    -->
</ul>

<div id="tab-content"></div>

<script>

var params = {{json_encode($gets)}};
var routes = {
    '#modal-user': 'user/user/dialog',
    '#modal-role': 'user/role/dialog',
    '#modal-department': 'user/department/dialog',
    '#modal-customer': 'customer/customer/dialog',
    '#modal-customer-contact': 'customer/contact/dialog',
    '#modal-supplier': 'supplier/supplier/dialog',
    '#modal-supplier-contact': 'supplier/contact/dialog'
};

function loadData(target) {
    $.get(app.url(routes[target], params), function(html) {
        $('#tab-content').html(html);
    });
}

$(function() {
    loadData('#modal-department');
    $('a[data-toggle=tab]').click(function() {
        var target = $(this).attr('href');
        loadData(target);
    });
});

</script>