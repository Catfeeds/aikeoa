<table id="widget-setting-system">
    <thead>
    <tr>
        <th data-field="title" data-align="left">标题</th>
    </tr>
    </thead>
</table>

<script>
(function($) {
    var $table = $('#widget-setting-system');
    $table.bootstrapTable({
        sidePagination: 'server',
        showColumns: false,
        showHeader: false,
        height: 200,
        pagination: false,
        url: '{{url("setting/widget/system")}}',
    });

})(jQuery);
</script>