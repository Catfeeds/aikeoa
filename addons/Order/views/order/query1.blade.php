<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="pull-right">

        @if(authorise('transport-settlement.create'))
            <button type="button" class="btn btn-default btn-sm" onclick="optionSubmit('#orderform','{{url('transport-settlement/create')}}','确定要结算选中的订单？');"><i class="fa fa-plane"></i> 物流结算</button>
        @endif

        @if(isset($access['merge']))
            <button type="button" class="btn btn-default btn-sm" onclick="optionDefault('#orderform','{{url('merge')}}');"><i class="fa fa-code-fork"></i> 合并</button>
        @endif

        @if(isset($access['delete']))
            <button type="button" onclick="optionDelete('#orderform','{{url('delete')}}','确定要删除订单？');" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i> 删除</button>
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
            e.step = function(i) {
                self._select({{search_select($steps)}}, i);
            }
            e.logistics = function(i) {
                self._select({{search_select($logistics)}}, i);
            }
        }
    });
});
</script>