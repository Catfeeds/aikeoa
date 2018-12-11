<style type="text/css">
.content-body { margin: 0; }

.row-sm { margin-left: 8px; margin-right: 8px; }
.row-sm > div { padding-left: 8px; padding-right: 8px; }
.row-sm > div > .panel {
    margin-bottom: 16px !important;
    text-align: center; 
}

.widget-item .table {
    border-radius: 0;
}
.widget-item .fixed-table-container {
    border: 0;
}
.widget-item .fixed-table-container tbody td,
.widget-item .fixed-table-container thead th {
    border-left: 0;
}
.widget-item .bootstrap-table .table > thead > tr > th {
    border-bottom: 0;
}
.row-todos .panel { padding-bottom: 10px; position: relative; }
.todo-logo { color: #fff; padding-top:20px; position:absolute; top:0; bottom:0; left:0; width: 80px; }
.todo-text { margin-left: 0; }

.app-title {
    padding-top: 15px;
    padding-bottom: 15px;
}

@media (min-width: 768px) {
    .widget-item {
        height: 200px;
    }
    .todo-text { margin-left: 60px; }
}

.frame-primary .dashboard-title {
    color: #58666e;
}

.widget-droppable {
    padding-bottom: 15px;
}
.widget-droppable div {
    border: 1px dashed #f6c483;
    background: #fffdfa;
    text-align: center;
    color: #ccc;
}

.panel-heading {
    padding: 8px 15px;
}

</style>

<div class="app-title wrapper-md">
    <div class="pull-right">
        <a class="btn btn-sm btn-info" data-toggle='widget-event' data-action='add' title="添加部件">
            <i class="fa fa-gear"></i> 添加部件
        </a>
    </div>
    <div class="text-white text-md dashboard-title"><i class="fa fa-dashboard text-md"></i> 个人空间</div>
</div>

@include('layouts/errors')

<div class="dashboard-widget">

    <div class="row row-sm row-todos">
        @foreach($todos as $todo)
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="panel">
                <div class="todo-logo hidden-xs bg-{{$todo['color']}}">
                    <i class="fa fa-3x {{$todo['icon']}}"></i>
                </div>
                <div class="todo-text">
                    <a href="javascript:;" data-toggle="addtab" data-url="{{url($todo['url'])}}" data-id="{{$todo['data']}}" data-name="{{$todo['name']}}">
                        <div class="text-3x text-info todo-item" data-type="2" data-name="{{$todo['name']}}" data-icon="{{$todo['icon']}}" data-color="{{$todo['color']}}" data-data="{{$todo['data']}}" data-url="{{$todo['url']}}" data-id="{{$todo['id']}}">&nbsp;</div>
                    </a>
                    <div class="text-muted">{{$todo['name']}}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row row-sm row-widgets">

        @foreach($widgets as $widget)
            <div class="col-xs-12 col-sm-{{$widget['grid']}}">
                <div class="panel panel-{{$widget['color']}}">
                    <div class="panel-heading text-base b-b">
                        <div class="pull-left">
                            <i class="fa {{$widget['icon']}}"></i> {{$widget['name']}}
                        </div>
                        <div class="pull-right">
                            <div class="btn-group dropdown">
                                <a href="javascript:;" data-toggle="dropdown" class="text-xs">配置 <i class="fa fa-caret-down" style="color:#0e90d2;"></i> </button>
                                <ul class="dropdown-menu text-xs">
                                    <li><a data-toggle='widget-event' data-action='refresh' data-id='{{$widget["id"]}}'><i class="fa fa-refresh"></i> 刷新</a></li>
                                    <li><a data-toggle='widget-event' data-action='edit' data-id='{{$widget["id"]}}'><i class="fa fa-gear"></i> 配置</a></li>
                                    <li class='divider'></li>
                                    <li><a data-toggle='widget-event' data-action='delete' data-id='{{$widget["id"]}}'><i class="fa fa-times"></i> 删除</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="widget-item" data-type="1" data-name="{{$widget['name']}}" data-icon="{{$widget['icon']}}" data-color="{{$widget['color']}}" data-url="{{$widget['url']}}" data-data="{{$widget['data']}}" data-grid="{{$widget['grid']}}" data-id="{{$widget['id']}}"></div>
                </div>
            </div>
            @endforeach
    </div>
</div>

<script>
(function($) {
    var $document = $(document);
    $document.on('click', '[data-toggle="addtab"]', function(event) {
        event.preventDefault();
        // 触屏设备不触发事件
        var mq = parent.checkMQ();
        if($(this).parent().find('ul').length) {
            if(mq == 'mobile' || mq == 'tablet') {
                return false;
            }
        }
        // 无ID不触发事件
        var data = $(this).data();
        if(data.id == undefined) {
            return false;
        }
        parent.addTab(data.url, data.id, data.name);
    });

    $('[data-toggle="widget-event"]').on('click', function() {
        var data = $(this).data();
        if (data.action == 'edit') {
            formDialog({
                title: '编辑部件',
                url: app.url('user/message/create', {id: data.id}),
                id: 'user-message-form-edit',
                dialogClass:'modal-md',
                success: function(res) {
                    $.toastr('success', res.data);
                    $(this).dialog("close");
                },
                error: function(res) {
                    $.toastr('error', res.data);
                }
            });
        }
    });

    function widgetInit() {
        var items = $('.widget-item');
        items.each(function(index, item) {
            var data = $(item).data('data');
            if(data.indexOf('/')) {
                $(item).load(app.url(data));
            }
        });
        var items = $('.todo-item');
        items.each(function(index, item) {
            var me = $(item);
            var data = me.data('data');
            $.get(app.url('index/index/todo', {type: data}), function(res) {
                if(res > 0) {
                    me.removeClass('text-info').addClass('text-danger');
                } else {
                    me.removeClass('text-danger').addClass('text-info');
                }
                me.text(res);
            });
        });
    }

    function widgetEdit() {
        var rows = $('.widget-item');
        var positions = [];
        if(rows.length) {
            $.map(rows, function(row) {
                positions.push({
                    name: $(row).attr('title'),
                    id: $(row).attr('id')
                });
            });
        }
    }

    function widgetClose(id) {
        var panel = $('#'+id).closest('.panel');
        panel.remove();
    }

    function widgetSort() {
        var items = $('.widget-item');
        var widgets = [];
        items.each(function(index, item) {
            var data = $(item).data();
            widgets.push(data);
        });
        var items = $('.todo-item');
        var todos = [];
        items.each(function(index, item) {
            var data = $(item).data();
            todos.push(data);
        });
        $.post('{{url("index/widget/sort")}}', {widgets: widgets, todos: todos}, function(res) {
            if(res.status) {
                $.toastr('success', res.data);
            } else {
                $.toastr('error', res.data)
            }
        });
    }

    widgetInit();

    $('.row-widgets').sortable({
        handle: '.panel-heading',
        opacity: 0.6,
        delay: 50,
        cursor: 'move',
        placeholder: 'widget-droppable',
        // revert: true,
        start: function (event, ui) {
            var h = $(this).find('.widget-droppable');
            h.addClass(ui.item[0].className);
            h.append('<div/>');
            h.find('div').outerHeight($(ui.item[0]).height() - 16);
        },
        update: function(event, ui) {
            widgetSort();
        }
    });
    $('.row-widgets').disableSelection();

    $('.row-todos').sortable({
        handle: '.todo-logo',
        opacity: 0.6,
        delay: 50,
        cursor: 'move',
        placeholder: 'widget-droppable',
        // revert: true,
        start: function (event, ui) {
            var h = $(this).find('.widget-droppable');
            h.addClass(ui.item[0].className);
            h.append('<div/>');
            h.find('div').outerHeight($(ui.item[0]).height()-16);
            // h.html('拖放控件到这里');
        }, 
        update: function(event, ui) {
            widgetSort();
        }
    });
    $('.row-todos').disableSelection();


})(jQuery);

</script>
