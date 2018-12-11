<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    @if(isset($access['create']))
        <button type="button" data-toggle="dialog-form" data-title="新建" data-url="{{url('create', ['id'=>$row->id])}}" data-id="window-form" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</button>
    @endif

    @if(isset($access['delete']))
        <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger">删除</button>
    @endif

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
        }
    });
});
</script>