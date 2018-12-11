<table id="widget-promotion-index">
    <thead>
    <tr>
        <th data-field="step_number" data-width="80" data-align="center">步骤序号</th>
        <th data-field="name" data-align="left">步骤名称</th>
        <th data-field="count" data-width="100" data-align="center">步骤数量</th>
    </tr>
    </thead>
</table>

<script>

(function($) {
    var $table = $('#widget-promotion-index');
    $table.bootstrapTable({
        sidePagination: 'server',
        showColumns: false,
        showHeader: true,
        height: 200,
        pagination: false,
        url: '{{url("promotion/widget/index")}}',
    });

})(jQuery);
</script>