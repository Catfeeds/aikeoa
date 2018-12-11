<div class="panel">

	<div class="wrapper b-b">
		<span class="text-md">{{$project['name']}}</span> <span class="text-muted">{{$project['description']}}</span>
	</div>

	<div class="wrapper" id="index-wrapper">
		<form id="search-task-form" class="form-inline" name="mytasksearch" method="get">
		<div class="pull-right">
			<div class="btn-group">
				<a href="{{url('index', ['project_id' => $project['id'], 'tpl' => 'index'])}}" class="btn btn-sm btn-default @if($query['tpl'] == 'index') active @endif">列表</a>
				<a href="{{url('index', ['project_id' => $project['id'], 'tpl' => 'gantt'])}}" class="btn btn-sm btn-default @if($query['tpl'] == 'gantt') active @endif">甘特图</a>
				<!--
				<a href="{{url('index', ['project_id' => $project['id'], 'tpl' => 'board'])}}" class="btn btn-sm btn-default @if($query['tpl'] == 'board') active @endif">看板</a>
				-->
			</div>
		</div>

		<a href="{{url($referer)}}" class="btn btn-sm btn-default"><i class="fa fa-reply"></i> 返回</a>

		@if(isset($access['add']))

			@if($permission['add_item'])
			<a href="javascript:addItem();" title="添加列表" class="hinted btn btn-sm btn-info"><i class="icon icon-plus"></i> 添加列表</a>
			@endif
			
			@if($permission['add_task'])
			<a href="javascript:addTask();" title="添加任务" class="hinted btn btn-sm btn-info"><i class="icon icon-plus"></i> 添加任务</a>
			@endif
			
		@endif

		@include('searchForm')

		<script type="text/javascript">
		$(function() {
			$('#search-task-form').searchForm({
				data: {{json_encode($search['forms'])}},
				init:function(e) {
					var self = this;
				}
			});
		});
		</script>
		</form>
	</div>

	<div class="list-jqgrid">
		<table id="jqgrid-table"></table>
	</div>

</div>

<script>

var t = null;

var project_id = "{{(int)$project['id']}}";
var params = {project_id:project_id};
var auth_id = '{{auth()->id()}}';

var model = [
	{name: "id", hidden: true},
	{name: "type", hidden: true},
	{name: "option_edit", hidden: true},
	{name: "option_delete", hidden: true},
	{name: "text", index: 'text', label: '任务', width: 260, align: 'left'},
	{name: "user_name", index: 'user_name', label: '执行者', width: 140, align: 'center'},
	{name: "users", index: 'users', label: '参与者', width: 480, align: 'left'},
	{name: "progress", formatter: formatterProgress, index: 'progress', label: '状态', width: 100, align: 'center'},
	{name: "start_at", formatter: 'datetime', index: 'start_at', label: '开始时间', width: 120, align: 'center'},
	{name: "end_at", formatter: 'datetime', index: 'end_at', label: '结束时间', width: 120, align: 'center'},
	{name: "duration_date", formatter: formatterDuration, index: 'duration_date', label: '持续时间', width: 100, align: 'center'},
	{name: "created_at", index: 'created_at', label: '创建时间', width: 140, align: 'center'},
];

function formatterProgress(cellvalue, options, row) {
	if(row.type == 'task' || row.type == 'subtask') {
		if(cellvalue == 1) {
			return '<span class="label label-success">已完成</span>';
		} else {
			return '<span class="label label-' + (auth_id == row.user_id ? 'danger' : 'info') + '">进行中</span>';
		}
	}
	return '';
}

function formatterDuration(cellvalue, options, row) {
	if(cellvalue) {
		return '<span class="hinted" title="任务持续' + cellvalue + '">' + cellvalue + '</span>';
	}
	return '';
}

(function($) {

    var search = $('#search-task-form');
    search.find('#search-submit').on('click', function() {

        var query = search.serializeArray();
        $.map(query, function(row) {
            params[row.name] = row.value;
        });
        t.jqGrid('setGridParam', {
            postData: params,
            page: 1
        }).trigger('reloadGrid');
        return false;
    });

    t = $("#jqgrid-table").jqGrid({
        caption: '',
        datatype: 'json',
        mtype: 'get',
        url: '{{url()}}',
        colModel: model,
        rowNum: 25,
        autowidth: true,
        multiselect: true,
        viewrecords: true,
        rownumbers: false,
        width: '100%',
        height: getPanelHeight(),
        footerrow: false,
		postData: params,
		ExpandColumn : 'text',
        ExpandColClick: true,
        treeGrid: true,
        treedatatype:"json",
        treeGridModel:"adjacency",
        treeReader: {
            parent_id_field:"parent_id",
            level_field:"level",
            leaf_field:"isLeaf",
            expanded_field:"expanded",
            loaded:"loaded"
        },
        ondblClickRow: function(rowid) {
			var task = $(this).getRowData(rowid);
			if(task.type == 'item') {
				editItem(task.id);
			}
			if(task.type == 'task') {
				editTask(task.id);
			}
			if(task.type == 'subtask') {
				editSubTask(task.id);
			}
        },
        gridComplete: function() {
            $(this).jqGrid('setColsWidth');
        }
    });

})(jQuery);

function getPanelHeight() {
	var height = $('#index-wrapper').outerHeight();
	var iframeHeight = $(window).height();
    return iframeHeight - height - 107;
}

$(window).resize(function() {
	t.jqGrid('setGridHeight', getPanelHeight());
});

function dataReload() {
	t.jqGrid('setGridParam', {
            postData: params,
            page: 1
    }).trigger('reloadGrid');
}

function getTask(id) {
	return t.getRowData(id);
}

</script>

@include('task/index/js')