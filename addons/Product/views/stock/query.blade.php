<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

        <div class="pull-right">
            @if(isset($access['merge']))
                <a class="btn btn-sm btn-default" href="javascript:optionDelete('#myform','{{url('merge')}}', '确定要合并单据吗?');"><i class="fa fa-code-fork"></i> 合并</a>
            @endif
            @if(isset($access['delete']))
                <a class="btn btn-sm btn-danger" href="javascript:optionDelete('#myform','{{url('delete')}}');"><i class="icon icon-remove"></i> 删除</a>
            @endif
        </div>

        @if(isset($access['create']))
            <a href="{{url('create')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif

        @include('searchForm')

</form>

<script type="text/javascript">

$(function() {
    $('#search-form').searchForm({
        data: {{json_encode($search['forms'])}},
        init: function(e) {
            var self = this;
            e.type = function(i) {
                self._select({{search_select($types)}}, i);
            }
        }
    });
});
</script>