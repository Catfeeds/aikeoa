<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="pull-right">

    @if(isset($access['export']))
        <button type="button" onclick="optionSort('#search-form','{{url('export')}}');" class="btn btn-sm btn-default"> 导出全部</button>
    @endif

    @if(isset($access['delete']))
        <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger"><i class="icon icon-trash"></i> 删除</button>
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
        data: {{json_encode($search['forms'])}},
        init: function(e) {
            var self = this;
            e.status = function(i) {
                self._select([{id:1,name:'启用'},{id:0,name:'禁用'}], i);
            }
            e.category = function(i) {
                var rows = {{json_encode($categorys)}};
                /*
                var options = [];
                $.map(rows, function(row) {
                    options.push({id: row.id, name: row.layer_space + row.name});
                });
                */
                self._select(rows, i);
            }
            e.warehouse = function(i) {
                var rows = {{json_encode($warehouse)}};
                var options = [];
                $.map(rows, function(row) {
                    options.push({id: row.id, name: row.layer_space + row.title});
                });
                self._select(options, i);
            }
        }
    });
});
</script>