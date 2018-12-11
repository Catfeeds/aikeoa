<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">
    
    <div class="pull-right">
        @if(isset($access['trash']))
            <a href="{{url('trash')}}" class="btn btn-sm btn-default"><i class="icon icon-trash"></i> 回收站</a>
        @endif
    </div>

    @if(isset($access['create']))
        <a href="{{url('create')}}" class="btn btn-info btn-sm"><i class="icon icon-plus"></i> 新建</a>
    @endif

    @include('searchForm')

</form>

<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data:{{json_encode($search['forms'])}},
        init:function(e) {
            var self = this;
            e.asset = function(i) {
                self._select({{search_select($assets)}}, i);
            }
        }
    });
});
</script>