<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    @include('searchForm')

</form>
<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data:{{json_encode($search['forms'])}},
        init:function(e) {
            var self = this;
            e.post = function(i) {
                self._select({{search_select($types)}}, i);
            }
            e.status = function(i) {
                self._select([{id:1,name:'启用客户'},{id:0,name:'禁用客户'}], i);
            }
        }
    });
});
</script>