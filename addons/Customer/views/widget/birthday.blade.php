<table id="widget-customer-birthday">
    <thead>
    <tr>
        <th data-field="nickname" data-align="left">客户名称</th>
        <th data-field="fullname" data-width="80" data-align="center">负责人</th>
        <th data-field="mobile" data-width="120" data-align="center">负责人手机</th>
        <th data-field="birthday" data-width="100" data-align="center">生日</th>
    </tr>
    </thead>
</table>

<script>
(function($) {
    var $table = $('#widget-customer-birthday');
    $table.bootstrapTable({
        sidePagination: 'server',
        showColumns: false,
        showHeader: true,
        height: 200,
        pagination: false,
        url: '{{url("customer/widget/birthday")}}',
    });

})(jQuery);
</script>