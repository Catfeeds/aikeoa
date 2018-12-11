<table id="widget-workflow-workflow">
    <thead>
    <tr>
        <th data-field="title" data-formatter="title1Formatter" data-align="left">流程主题</th>
        <th data-field="step_title" data-width="160" data-align="center">当前步骤</th>
        <th data-field="turn_time" data-formatter="datetimeFormatter" data-width="120" data-align="center">交办时间</th>
    </tr>
    </thead>
</table>

<script>

function datetimeFormatter(value, row) {
    return format_datetime(value);
}

function title1Formatter(value, row) {
    return '<a href="javascript:;" data-toggle="addtab" data-url="'+app.url('workflow/workflow/edit', {process_id: row.id})+'" data-id="'+row.id+'" data-name="待办流程[' + value + ']">' + value + '</a>';
}

(function($) {
    var $table = $('#widget-workflow-workflow');
    $table.bootstrapTable({
        method: 'post',
        sidePagination: 'server',
        showColumns: false,
        showHeader: true,
        height: 200,
        pagination: false,
        url: '{{url("workflow/widget/index")}}',
    });

})(jQuery);
</script>