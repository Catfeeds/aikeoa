<script>
(function($) {
    var table = '{{$header["table"]}}';
    var config = window[table];
    var search = config.search;
    search.advanced.el = $('#' + table + '-search-form-advanced').searchForm({
        data: search.forms,
        advanced: true,
        init: search.searchInit
    });
    search.simple.el = $('#' + table + '-search-form').searchForm({
        data: search.forms,
        init: search.searchInit
    });
    search.simple.el.find('#search-submit').on('click', function() {
        var query = search.simple.el.serializeArray();
        search.queryType = 'simple';
        $.map(query, function(row) {
            search.simple.query[row.name] = row.value;
        });
        config.grid.jqGrid('setGridParam', {
            postData: search.simple.query,
            page: 1
        }).trigger('reloadGrid');
        return false;
    });

    var panel = $('#' + table + '-controller');
    var action = config.action;
    panel.on('click', '[data-toggle="' + table + '"]', function() {
        var data = $(this).data();
        action[data.action]();
    });
})(jQuery);
</script>