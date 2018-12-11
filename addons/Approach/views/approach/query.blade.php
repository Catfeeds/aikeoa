<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="pull-right">

        @if($tpl == 'trash')
            <a href="{{url('index')}}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> 返回列表</a>
        @else 
            @if(isset($access['trash']))
                <a href="{{url('trash')}}" class="btn btn-sm btn-default"><i class="icon icon-trash"></i> 回收站</a>
            @endif
        @endif

        @if($tpl == 'index' || $tpl == 'count')
            @if(isset($access['delete']))
                <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger"><i class="icon icon-remove"></i> 删除</button>
            @endif
        @else
            @if(isset($access['destroy']))
                <button type="button" onclick="optionDelete('#myform','{{url('destroy')}}');" class="btn btn-sm btn-danger"><i class="icon icon-remove"></i> 销毁</button>
            @endif
        @endif
        
    </div>

    @if($tpl == 'index')
        @if(isset($access['create']))
            <a href="{{url('create')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif
    @endif

    @include('searchForm')

</form>

<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data: {{json_encode($search['forms'])}},
        init: function(e) {
            var me = this;
            e.cash = function(i) {
                me._select({{json_encode($cashs, JSON_UNESCAPED_UNICODE)}}, i);
            }
            e.step = function(i) {
                me._select({{json_encode($steps, JSON_UNESCAPED_UNICODE)}}, i);
            }
            e.material = function(i) {
                me._select({{json_encode($materials, JSON_UNESCAPED_UNICODE)}}, i);
            }
        }
    });
});
</script>