<table id="widget-order-index">
    <thead>
    <tr>
        <th data-field="title" data-align="left">标题</th>
    </tr>
    </thead>
</table>

<script>
(function($) {
    var $table = $('#widget-order-index');
    $table.bootstrapTable({
        sidePagination: 'server',
        showColumns: false,
        showHeader: false,
        height: 200,
        pagination: false,
        url: '{{url("order/widget/index")}}',
    });

})(jQuery);
</script>