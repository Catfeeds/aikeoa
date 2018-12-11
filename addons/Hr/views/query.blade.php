<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    @include('searchForm')

</form>

<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data:{{json_encode($search['forms'])}}
    });
});
</script>