<table id="widget-order-goods">
    <thead>
    <tr>
        <th data-field="number" data-formatter="numberFormatter" data-align="left">订单号</th>
        <th data-field="nickname" data-align="left">客户</th>
        <th data-field="delivery_time" data-width="200" data-formatter="datetimeFormatter" data-sortable="true" data-align="center">发货时间</th>
    </tr>
    </thead>
</table>

<script>

function datetimeFormatter(value, row) {
    return format_datetime(value);
}

function numberFormatter(value, row) {
    return '<a href="'+app.url('order/order/view', {id: row.id})+'">' + value + '</a>';
}

(function($) {
    var $table = $('#widget-order-goods');
    $table.bootstrapTable({
        sidePagination: 'server',
        showColumns: false,
        showHeader: false,
        height: '100%',
        pagination: false,
        url: '{{url("order/widget/goods")}}',
    });

})(jQuery);
</script>