<div class="panel">

    @include('query')
    <form method="post" id="myform" name="myform">
    <div class="wrapper b-t">

        <div class="form-inline">

            <div class="row">

                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label" for="set_col_width">名称</label>
                        <input type="text" id="name" name="name" value="{{$template['name']}}" class="form-control input-sm">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label" for="set_col_hidden">范围</label>
                        {{Dialog::search($template, 'id=receive_id&name=receive_name&multi=1&width=135px')}}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label" for="set_col_readonly">类型</label>
                        <select multiple="multiple" class="input-select2 form-control input-sm" style="width:153px;" id="type" name="type[]">
                            <option value="create" @if(in_array('create', $template['type'])) selected @endif>新增</option>
                            <option value="edit" @if(in_array('edit', $template['type'])) selected @endif>编辑</option>
                            <option value="show" @if(in_array('show', $template['type'])) selected @endif>显示</option>
                            <option value="print" @if(in_array('print', $template['type'])) selected @endif>打印</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label" for="set_col_hidden">客户端</label>
                        <select multiple="multiple" class="input-select2 form-control input-sm" style="width:153px;" id="client" name="client[]">
                            <option value="web" @if(in_array('web', $template['client'])) selected @endif>web</option>
                            <option value="app" @if(in_array('app', $template['client'])) selected @endif>app</option>
                            <option value="wx" @if(in_array('wx', $template['client'])) selected @endif>wx</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="model_id" id="model_id" value="{{$model_id}}">
                <input type="hidden" name="id" id="id" value="{{$template['id']}}">
            </div>
        </div>
    </div>

    <div class="col-sm-2 m-b">

        <div class="panel b-a panel-default">

            <div class="panel-heading">
                <div>字段列表</div>
            </div>

            <ul class="list-group">

                <!-- 多行子表 -->
                @foreach($fields as $field) @if($field['parent_id'])

                <div class="list-group-item fld field" data-col="12" data-hidden="0" data-readonly="0" data-id="{{$field['id']}}" data-type="{{$field['type']}}" data-field="{{$field['table']}}">
                    <div class="title">
                        <i class="fa fa-w fa-remove" title="删除"></i> {{$field['name']}}
                    </div>
                    <div class="desc-sublist"></div>
                </div>

                @foreach($field['fields'] as $_field)
                <div class="list-group-item subfld field" data-col="12" data-hidden="0" data-readonly="0" data-id="{{$_field['id']}}" data-type="{{$_field['type']}}" data-field="{{$_field['field']}}">
                    <div class="title">
                        <i class="fa fa-w fa-remove" title="删除"></i> {{$_field['name']}}
                    </div>
                </div>
                @endforeach

                @else

                <div class="list-group-item fld field" data-col="12" data-hidden="0" data-readonly="0" data-id="{{$field['id']}}" data-type="0" data-field="{{$field['field']}}">
                    <div class="title">
                        <i class="fa fa-w fa-remove" title="删除"></i> {{$field['name']}}
                    </div>
                    <div class="desc"></div>
                </div>

                @endif @endforeach

            </ul>
    </div>

    </div>

    <div class="col-sm-7 m-b">
        <div class="droppedFieldsBox b-a" id="droppedFieldsBox"></div>
    </div>

    <div class="col-sm-3 m-b">
        
        <div class="panel b-a panel-default m-b b-t">
                    
            <div class="panel-heading">
                <div>字段属性</div>
            </div>

            <div class="panel-body">

            <div class="form-group">
                <label for="set_col_width">占用行比例</label>
                <select class="form-control input-sm" id="set_col_width" onchange="setColWidth(this.value)" required="required">
                    <option value="12">12</option>
                    <option value="11">11</option>
                    <option value="10">10</option>
                    <option value="9">9</option>
                    <option value="8">8</option>
                    <option value="7">7</option>
                    <option value="6">6</option>
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>
            </div>

            <div class="form-group">
                <label for="set_col_readonly">字段只读</label>
                <select class="form-control input-sm" id="set_col_readonly" onchange="setColReadonly(this.value)" required="required">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
            </div>

            <div class="form-group">
                <label for="set_col_hidden">字段隐藏</label>
                <select class="form-control input-sm" id="set_col_hidden" onchange="setColHidden(this.value)" required="required">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
            </div>

            <div class="form-group m-b-none">
                <a id="add-form-group" class="btn btn-info">
                    <i class="fa fa-plus-circle"></i>
                    添加表单组
                </a>
                <!--
                <a onclick="preview()" class="btn btn-default">预览</a>
                -->
                <a onclick="submit()" class="btn btn-success">
                    <i class="fa fa-check-circle"></i> 保存
                </a>
            </div>

            </div>

        </div>

    </div>

    <div class="clearfix"></div>

</form>

</div>

<style>

@media (min-width: 768px) {
    .col-sm-7 {
        padding-left: 0px;
        padding-right: 0px;
    }
}

.panel-heading .panel-title {
    font-size: 12px;
}

.panel-heading .fa {
    vertical-align: middle;
    color: #999;
    cursor: pointer;
}

.panel-heading .fa:hover {
    color: #666;
}

.desc {
    display: none;
    color: #999;
}

.field {
    cursor: pointer;
}

.droppedFieldsBox {
    padding: 15px;
    background:#f0f3f4;
}

.droppedFields .title > .fa {
    display: none;
    float: right;
    color: #999;
}

.droppedFields .active .title > .fa {
    display: none;
    float: right;
    color: #fff;
}

.droppedFields .title:hover > .fa {
    display: block;
}

.list-group {
    position: relative;
    height: 500px;
    overflow-x: hidden;
    overflow-y: auto;
}

.list-group .fa {
    display: none;
}

.list-group .field:hover {
    background-color: #eee;
    color: #666;
}

.droppedFields .field {
    cursor: move;
    border-top: #ddd solid 1px;
    border-bottom: #ddd solid 1px;
    border-right: #ddd solid 1px;
    border-left: #ddd solid 1px;
    background-color: #fff;
    display: inline-block;
    width: 100%;
    margin-top: -1px;
    white-space: nowrap;
    margin: 1px;
}

.list-group-item {
    padding: 0;
}

.title {
    padding: 10px;
    display: inline-block;
    text-align: left;
}

.droppedFields .title {
    background-color: #eee;
    width: 160px;
}

.droppedFields .desc-sublist .title {
    width: 120px;
}

.droppedFields .active > .title {
    background-color: #0e90d2;
    color: #fff;
}

.droppedFields .fa {
    cursor: pointer;
}

.droppedFields .desc {
    display: inline-block;
}

.droppedFields .desc-sublist {
    width: 100%;
    padding: 10px;
    min-height: 30px;
    white-space: normal;
    border-top: #ddd solid 1px;
}

.droppedFields .subfld {
    white-space: normal;
    width: auto;
}

.droppedFields {
    min-height: 60px;
    background-color: #fff;
    padding: 10px;
}

.highlightDroppable {
    padding: 10px;
    border: 1px dashed #f6c483;
    background: #fffdfa;
    text-align: center;
    color: #ccc;
    display: inline-block;
    width: 100%;
    margin-top: -1px;
    position: relative;
    margin: 1px;
}

.desc-sublist .highlightDroppable {
    width: 120px;
}

</style>

<script>

var fields = $.parseJSON('{{$template["tpl"]}}');

var activeField = null;

var droppedFieldsBox = null;

function setColWidth(v) {
    activeField.attr('data-col', v);
    activeField.css({ width: 'calc(' + (v / 12 * 100) + '% - 2px'});
}

function setColReadonly(v) {
    activeField.attr('data-readonly', v);
}

function setColHidden(v) {
    activeField.attr('data-hidden', v);
}

var sortableOptions = {
    opacity: 0.4,
    delay: 50,
    cursor: "move",
    placeholder: "highlightDroppable",
    stop: function (event, ui) {
        var type = ui.item.hasClass('component');
        if (type == undefined) {
            ui.item.attr('component', 1);
            ui.item.css({ width: '100%' });
        }
        ui.item.trigger("click");

        if(ui.item.data('type') == 1) {
            droppedFieldsBox.find('.desc-sublist:not(.ui-sortable)').sortable(sortableOptions);
        }

    }, 
    start: function (event, ui) {
        ui.item.removeClass('list-group-item');
        var h = $(this).find(".highlightDroppable");
        h.outerWidth(ui.item[0].style.width);
        h.html('拖放控件到这里');
    }, out: function (event, ui) {
    }
}

function editDialog(options) {
    var defaultOptions = {
        title: '',
        html: '<div class="panel-body"><div class="form-group"><label>名字</label><input type="text" class="form-control input-sm"></div><div class="form-group"><label>类型</label><select class="form-control input-sm"><option value="panel">panel</option><option value="tabs">tabs</option></select></div></div>',
        buttons: [{
            class: 'btn-info',
            text: '<i class="fa fa-check"></i> 保存',
            click: function () {
                if(typeof options.onSubmit === 'function') {
                    options.onSubmit.call(this);
                }
            }
        },{
            class: 'btn-default',
            text: '<i class="fa fa-remove"></i> 取消',
            click: function () {
                $(this).dialog('close');
        }}]
    };

    options = $.extend(defaultOptions, options);
    $('#edit-modal').__dialog(options);
}

$(function() {

    droppedFieldsBox = $('#droppedFieldsBox');

    // 添加表单组
    $("#add-form-group").on('click', function() {

        editDialog({
            title: '添加表单组', 
            onSubmit: function() {

                var title = $(this).find('input.form-control').val();
                var type  = $(this).find('select.form-control').val();
                var size  = $('.droppedFields').size() + 1;

                droppedFieldsBox.append('<div class="panel b-a"><div class="panel-heading b-b"><span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-remove"></i></span><span class="label bg-light panel-type">' + type + '</span> <span class="panel-title">' + title + '</span></div><div data-type="' + type + '" data-title="' + title + '" data-column="' + size + '" id="selected-column-' + size + '" class="droppedFields"></div></div>');
                $('.droppedFields:not(.ui-sortable)').sortable(sortableOptions);
                (this).dialog('close');
            }
        });
    });

    // 编辑表单组
    droppedFieldsBox.on('click', '.panel-heading .fa-pencil', function() {

        var heading = $(this).closest('.panel');
        var dropped = heading.find('.droppedFields');

        editDialog({
            title: '编辑表单组',
            onShow: function() {
                var me = this;
                me.html(me.options['html']);
                $(this).find('input.form-control').val(dropped.attr('data-title'));
                $(this).find('select.form-control').val(dropped.attr('data-type'));
            },
            onSubmit: function() {

                var title = $(this).find('input.form-control').val();
                var type = $(this).find('select.form-control').val();
                dropped.attr('data-title', title);
                dropped.attr('data-type', type);

                heading.find('.panel-title').text(title);
                heading.find('.panel-type').text(type);

                $(this).dialog('close');
            }
        });

    });

    // 删除表单组
    droppedFieldsBox.on('click', '.panel-heading .fa-remove', function() {
        $(this).closest('.panel').remove();
    });

    // 删除字段
    droppedFieldsBox.on('click', '.droppedFields .fa', function() {
        $(this).closest('.field').remove();
    });

    // 点击字段
    droppedFieldsBox.on('click', '.droppedFields .field', function(e) {

        e.stopPropagation();

        var me = $(this);
        activeField = me;

        $(document).find('.droppedFields .field').removeClass('active');
        me.addClass('active');

        $('#set_col_width').val(me.attr('data-col'));
        $('#set_col_readonly').val(me.attr('data-readonly'));
        $('#set_col_hidden').val(me.attr('data-hidden'));

    });

    $('.fld').draggable({
        connectToSortable: '.droppedFields',
        helper: "clone",
        revert: "invalid",
        start: function (event, ui) {
            var width = ui.helper.parent().outerWidth();
            ui.helper.outerWidth(width);
        }
    });

    $('.subfld').draggable({
        connectToSortable: '.desc-sublist',
        helper: "clone",
        revert: "invalid",
        start: function (event, ui) {
            var width = ui.helper.parent().outerWidth();
            ui.helper.outerWidth(width);
        }
    });

    // 初始化字段
    $.each(fields, function(k, form_group) {

        var type  = form_group.type;
        var size  = form_group.column;
        var title = form_group.title;
        var _fields = Array();
        $.each(form_group.fields, function(k, v) {

            var w = 'calc(' + (v.col / 12 * 100) + '% - 2px)';

            if(v.type == 0) {
                _fields.push('<div class="fld field" data-hidden="' + v.hidden + '" data-readonly="' + v.readonly + '" data-col="' + v.col + '" data-type="' + v.type + '" data-field="' + v.field + '" data-id="' + v.id + '" style="display: inline-block; width: ' + w + ';"><div class="title"><i class="fa fa-w fa-remove" title="删除"></i> ' + v.name + '</div> <div class="desc"></div></div>');
            } else {
                var _subs = [];
                $.each(v.fields, function(k, vv) {
                    _subs.push('<div class="subfld field" data-hidden="' + vv.hidden + '" data-readonly="' + vv.readonly + '" data-col="' + vv.col + '" data-type="' + vv.type + '" data-field="' + vv.field + '" data-id="' + vv.id + '"><div class="title"><i class="fa fa-w fa-remove" title="删除"></i> ' + vv.name + '</div></div>');
                });
                _fields.push('<div class="subfld field" data-hidden="' + v.hidden + '" data-readonly="' + v.readonly + '" data-col="' + v.col + '" data-type="' + v.type + '" data-field="' + v.field + '" data-id="' + v.id + '" style="display: inline-block; width: ' + w + ';"><div class="title"><i class="fa fa-w fa-remove" title="删除"></i> ' + v.name + '</div> <div class="desc-sublist">' + _subs.join('') + '</div></div>');
                
            }
        
        });

        droppedFieldsBox.append('<div class="panel b-a"><div class="panel-heading b-b"><span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-remove"></i></span><span class="label bg-light panel-type">' + type + '</span> <span class="panel-title">' + title + '</span></div><div data-type="' + type + '" data-title="' + title + '" data-column="' + size + '" id="selected-column-' + size + '" class="droppedFields">' + _fields.join('') + '</div></div>');

    });

    $('.droppedFields:not(.ui-sortable)').sortable(sortableOptions);
    droppedFieldsBox.find('.desc-sublist:not(.ui-sortable)').sortable(sortableOptions);
    droppedFieldsBox.find(".droppedFields").disableSelection();

});

function preview() {

    console.log(getColumns());
    return;

    var dialogContent, i, j;
    if (columns.length > 0) {
        var divWidth = 100 / columns.length;
        dialogContent = "<div>";
        for (i = 0; i < columns.length; i++) {
            dialogContent += "<div style='float:left;width=" + divWidth + "%;'>";
            dialogContent += "<ul><li><b>Column " + (i + 1) + "</b></li>";
            for (j = 0; j < columns[i].length; j++) {
                var obj = columns[i][j];
                dialogContent += "<li>" + obj.label + "</li>";
            }
            dialogContent += "</ul></div>";
        }
        dialogContent += "</div>";
    } else {
        dialogContent = '<div>Nothing to preview</div>';
    }

    $(dialogContent).dialog({
        modal: true,
        width: 500,
        height: 400,
        buttons: {
            Ok: function () {
                $(this).dialog("close");
            }
        }
    });
}

function getColumns() {
    var res = [];
    $.each($(".droppedFields"), function(i, v) {

        var groupTitle  = $(v).attr('data-title');
        var groupType   = $(v).attr('data-type');
        var groupColumn = $(v).attr('data-column');

        var fields = $(v).children('.field');

        var columns = [];

        if (fields.length > 0) {

            $.each(fields, function(k, v) {

                var me = $(v);
                var type = me.data('type');

                var _column = getColumn(me);

                if(type == 1) {

                    var _fields = $(v).children('.desc-sublist').children('.field');

                    if (_fields.length > 0) {
                        var __column = [];
                        $.each(_fields, function(k, _v) {
                            var _me = $(_v);
                            __column.push(getColumn(_me));
                        });
                        _column.fields = __column;
                    }
                }

                columns.push(_column);
                
            });
            res.push({title:groupTitle, type: groupType, column:groupColumn, fields:columns});
        }
    });
    return res;
}

function getColumn(me) {
    var column = {};
    column.field = me.data('field');
    column.css = me.data('css');
    column.hidden = me.data('hidden');
    column.readonly = me.data('readonly');
    column.type = me.data('type');
    column.id = me.data('id');
    column.col = me.data('col');
    column.name = me.children(".title").text().trim();
    return column;
}

function submit() {
    var columns = getColumns();
    var data = $('#myform').serialize() + '&' + $.param({columns: columns});
    $.post('{{url()}}', data, function (res) {
        console.log(res);
        location.reload();
    }, 'json');
}
</script>