<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="pull-right">
        @if(isset($access['audit']))
            <button type="button" onclick="optionDelete('#myform','{{url('audit')}}', '确认要审核对账单吗?');" class="btn btn-sm btn-info">审核</button>
        @endif
        @if(isset($access['delete']))
            <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger">删除</button>
        @endif
    </div>

    @if(isset($access['create']))
        <a data-toggle="dialog-form" data-title="查询对账单" data-url="{{url('create')}}" data-id="window-form" href="javascript:;" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 查询对账单</a>
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