<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    @include('searchForm')

</form>
<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data:{{json_encode($search['forms'])}},
        init:function(e) {
            var self        = this;
            var values      = self.options.data;
            var province_id = values.search[0];
            var city_id     = values.search[1];

            e.post = function(i) {
                self._select({{search_select($types)}}, i);
            }
            e.status = function(i) {
                self._select([{id:1,name:'启用客户'},{id:0,name:'禁用客户'}], i);
            }
            e.invoice = function(i) {
                self._select({{search_select(option('customer.invoice'))}}, i);
            }
        }
    });
});
</script>