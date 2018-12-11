<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="pull-right">
        @if(isset($access['delete']))
            <a class="btn btn-sm btn-danger" href="javascript:optionDelete('#myform','{{url('delete')}}');"><i class="icon icon-remove"></i> 删除</a>
        @endif
    </div>

    @if(isset($access['add']))
        <a href="{{url('add')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
    @endif

    @include('searchForm')

</form>

<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data:{{json_encode($search['forms'])}},
        init:function(e) {
            var self = this;
            e.status = function(i) {
                self._select([{id:1,name:'启用账户'},{id:0,name:'禁用账户'}], i);
            }
        }
    });
});
</script>