<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="pull-right">
        
        @if(Request::action() == 'index' && isset($access['delete']))
            <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger">删除</button>
        @endif

    </div>

    日期：<input name="start_at" id="search-start_at" value="{{$start_at}}" type="text" data-toggle="date" class="form-control input-sm"> - <input name="end_at" id="search-end_at" value="{{$end_at}}" type="text" data-toggle="date" class="form-control input-sm">&nbsp;

    @include('searchForm')

</form>

<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data: {{json_encode($search['forms'])}},
        init:function(e) {
            var self = this;
            e.post = function(i) {
                self._select({{search_select($types)}}, i);
            }
        }
    });
});
</script>