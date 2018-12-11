<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">
    
    <div class="pull-right">

        @if($trash == 0)
            @if(isset($access['delete']))
                <button type="button" onclick="optionDelete('#myform','{{url('delete',['status'=>0])}}');" class="btn btn-sm btn-danger"><i class="icon icon-remove"></i> 删除</button>
            @endif
        @else
            @if(isset($access['destroy']))
                <button type="button" onclick="optionDelete('#myform','{{url('destroy')}}');" class="btn btn-sm btn-danger"><i class="icon icon-remove"></i> 销毁</button>
            @endif
        @endif

        @if(isset($access['export']))
            <button type="button" onclick="optionSort('#search-form','{{url('export')}}');" class="btn btn-sm btn-default"> 导出全部</button>
        @endif

        @if(isset($access['trash']))
            <a href="{{url('trash')}}" class="btn btn-sm btn-default"><i class="icon icon-trash"></i> 回收站</a>
        @endif

    </div>

    @if($trash == 0)
        @if(isset($access['create']))
            <a href="{{url('create')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif
    @else
        @if(isset($access['delete']))
            <button type="button" onclick="optionDelete('#myform','{{url('delete',['status'=>1])}}','确定要恢复人事资料吗');" class="btn btn-sm btn-info">恢复</button>
        @endif
    @endif

    @include('searchForm')
    
</form>

<script type="text/javascript">
$(function() {
    $('#search-form').searchForm({
        data:{{json_encode($search['forms'])}}
    });
});
</script>