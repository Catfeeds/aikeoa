var select2List = {};

$(function() {

    var $document = $(document);

    // 提示
    $('[data-toggle="tooltip"]').tooltip();

    var input_select2 = $document.find('.input-select2');
    if(input_select2.length) {
        input_select2.select2();
    }

    // 新提示 
    $document.tooltip({
        container: 'body',
        placement: 'auto',
        selector:'.hinted',
        delay: {show: 200, hide: 0}
    });

    // 批量操作
    $('.select-all').on('click', function() {
        var tr = $('.select-row').closest('tr');
        if($(this).prop('checked')) {
            tr.addClass('success');
        } else {
            tr.removeClass('success');
        }
        $(".select-row").prop('checked', $(this).prop('checked'));
    });

    // 阻止事件冒泡
    $('.table tbody td a').on('click', function(e) {
        e.stopPropagation();
    });

    // 点击td选择行
    $('.table tbody tr').on('click', function(e) {

        var tr = $(this);
        var checkbox = tr.find('.select-row');
        var checked  = checkbox.prop('checked');

        if(checkbox.length == 0) {
            return;
        }

        if(e.target.tagName == 'INPUT') {
            setCheckbox(checked);
        }

        if(e.target.tagName == 'DIV') {
            setCheckbox(!checked);
        }

        if(e.target.tagName == 'TD') {
            setCheckbox(!checked);
        }

        function setCheckbox(checked) {
            if(checked) {
                tr.addClass('success');
            } else {
                tr.removeClass('success');
            }
            checkbox.prop('checked', checked);
        }
    });

    // 转到某些地址，单页面内类型切换
    $document.on('change', '[data-toggle="redirect"]', function() {
        // 从select的rel传递过来的地址
        var url = $(this).attr('rel');
        var id  = $(this).attr('id');
        var selected = $(this).find("option:selected").val();
        var result = url.replace(new RegExp('('+id+'=)[^&]*','g'),'$1'+selected);
        location.href = result;
    });

    // 弹出用户角色部门
    $document.on('click.dialog.search', '[data-toggle="dialog-search"]', function() {
        var params = $(this).data();
        delete params['toggle'];
        var url = app.url('index/api/dialog', params);
        $('#dialog-search').__dialog({
            url: url,
            title: '',
            dialogClass: 'modal-lg',
            buttons: [{
                text: "确定",
                'class': "btn-default",
                click: function() {
                    $(this).dialog("close");
                }
            }]
        });
    });

    // 弹出对话框表单
    $document.on('click.dialog.view', '[data-toggle="dialog-view"]', function() {
        var params = $(this).data();
        params.size = params.size || 'md'
        $('#view-dialog').__dialog({
            title: params.title,
            url: params.url,
            dialogClass: 'modal-' + params.size,
            buttons: [{
                text: '确定',
                'class': "btn-default",
                click: function() {
                    $(this).dialog("close");
                }
            }]
        });
    });

    // 弹出对话框表单
    $document.on('click.dialog.image', '[data-toggle="dialog-image"]', function() {
        var params = $(this).data();
        $('#image-dialog').__dialog({
            title: params.title,
            html: '<img style="text-align:center;max-width:100%;" src="'+params.url+'" />',
            buttons: [{
                text: '确定',
                'class': 'btn-default',
                click: function() {
                    $(this).dialog("close");
                }
            }]
        });
    });

    // 弹出对话框表单
    $document.on('click.dialog.form', '[data-toggle="dialog-form"]', function() {
        var params = $(this).data();
        
        params.id = params.id || 'myform';
        params.size = params.size || 'md';

        $('#form-' + params.size).__dialog({
            title: params.title,
            url: params.url,
            dialogClass: 'modal-' + params.size,
            buttons: [{
                text: '提交',
                class: 'btn-info',
                click: function() {
                    var me = this;
                    var action = $('#'+params.id).attr('action');
                    var formData = $('#'+params.id).serialize();

                    $.post(action, formData, function(res) {

                        if (typeof success === 'function') {
                            success.call(me, res); 
                        } else {
                            if(res.status) {
                                if(res.data == 'reload') {
                                    window.location.reload();
                                } else {
                                    $.toastr('success', res.data);
                                    $(me).dialog('close');
                                }
                            } else {
                                $.toastr('error', res.data);
                            }
                        }

                    },'json');
                }
            },{
                text: '取消',
                class: 'btn-default',
                click: function() {
                    var me = this;
                    if (typeof error === 'function') {
                        error.call(me, res); 
                    } else {
                        $(me).dialog("close");
                    }
                }
            }]
        });
    });

    // 删除确认
    $document.on('click.option.delete', '[data-toggle="option-delete"]', function() {

        var params = $(this).data();
        var count = $("input.select-row[type='checkbox']:checked").length;
        var content = params.content || '确定要删除吗？';

        var e = $(params.id);
        e.attr('action', params.url);

        if(count > 0) {

            $.messager.confirm('操作确认', content, function() {
                e.submit();
            });

        } else {
            $.toastr('error', '最少选择一行记录。');
        }
        
    });

    // 排序按钮
    $document.on('click.option.sort', '[data-toggle="option-sort"]', function() {
        var params = $(this).data();
        var e = $(params.id);
        e.attr('action', params.url);
        e.submit();
    });

    // 清除用户角色部门选择
    $document.on('click.dialog.search.clear', '[data-toggle="dialog-search-clear"]', function() {
        var params = $(this).data();
        document.getElementById(params.id).value = '';
        document.getElementById(params.name).value = '';
        document.getElementById(params.id+'_text').innerHTML = '';
    });

    // 日期选择
    $document.on('click.date', '[data-toggle="date"]', function() {
        var format = $(this).data('format') || 'yyyy-MM-dd';
        datePicker({errDealMode:2, dateFmt:format});
    });

    // 日期时间选项
    $document.on('click.datetime', '[data-toggle="datetime"]', function() {
        var format = $(this).data('format') || 'yyyy-MM-dd HH:mm';
        datePicker({errDealMode:2, dateFmt:format});
    });

    // 显示产品图片
    $("a.goods-image").hover(function(e) {
        var img = $('<p id="goods"><img src="'+ this.rel + '" alt="" /></p>');
		$("body").append(img);

        $(this).find('img').stop().fadeTo('slow', 0.5);

        var $window = $(window);
        var $image = $(document).find('#goods');

		var height = $image.height();
        var width  = $image.width();

        var left = ($window.scrollLeft() + ($window.width() - width) / 2) + 'px';
        var top = ($window.scrollTop() + ($window.height() - height) / 2) +'px';

        var offset = $(this).offset();
        $image.css({left: offset.left + 100, top: top});
        $image.fadeIn('fast');

	}, function() {
	    $(this).find('img').stop().fadeTo('slow', 1);
		$("#goods").remove();
    });

    // 表格拖动排序
    $('#table-sortable tbody').sortable({
        // opacity: 0.6,
        delay: 50,
        cursor: "move",
        axis:"y",
        items: "tr",
        handle: 'td.move',
        // containmentType:"parent",
        // placeholder: "ui-sortable-placeholder",
        helper: function(event, ui) {
            // 在拖动时，拖动行的cell（单元格）宽度会发生改变。
            ui.children().each(function() {
                $(this).width($(this).width());
            });  
            return ui;
        },
        stop: function (event, ui) {
        }, 
        start: function (event, ui) {
            ui.placeholder.outerHeight(ui.item.outerHeight());
        },
        update: function() {
            var url = $(this).parent().attr('url');
            var orders = $(this).sortable("toArray");
            $.post(url, {sort:orders}, function(res) {
                $.toastr('success', res.data);
            });
        }
    })//.disableSelection();
});

var app = {
    /**
     * 确认窗口
     */
    confirm: function(url, content, title)
    {
        title = title || '操作确认';
        $.messager.confirm(title, content, function() {
            location.href = url;
        });
    },
    /**
     * 警告窗口
     */
    alert: function(title, content) {
        $.messager.alert(title, content);
    },
    /**
     * 获取附带基本路径的URL
     */
    url: function(uri, params) {
        if(uri == '/') {
            return settings.public_url;
        }
        query = (params == '' || params === undefined) ? '' : '?' + $.param(params);
        return settings.public_url + '/' + uri + query;
    },
    redirect: function(uri, params) {
        return window.location.href = app.url(uri, params);
    },
    /**
     * 汉字转换为拼音
     */
    pinyin: function(read, write, type) {
        type = type || 'first';
        var field = $('#'+write).val();
        if (field == '') {
            $.get(app.url('index/api/pinyin?type='+ type +'&id='+Math.random()), {name:$('#'+read).val()}, function(data) {
                $('#'+write).val(data);
            });
        }
    }
}

var uploader = {
    file: function(fileId) {
        var id = $('#'+fileId).find(".id").val();
        location.href = app.url('index/attachment/download',{id:id});
    },
    cancel: function(fileId) {
        var id = $('#'+fileId).find(".id").val();
        if (id > 0) {
            var name = $('#'+fileId).find(".file-name a").text();
            $.messager.confirm('删除文件', '确定要删除 <strong>'+name+'</strong> 此文件吗', function() {
                $.get(app.url('index/attachment/delete'), {id:id}, function(res) {
                    if(res == 1) {
                        $('#'+fileId).remove();
                    }
                });
            });
        } else {
            $('#'+fileId).remove();
        }
    },
    insert: function(fileId) {
        var id = $('#'+fileId).find(".id").val();
        var name = $('#'+fileId).find(".file-name a").text();
        // 检查图片类型
        if(/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(name)) {
            var html = '<img src="' + app.url('index/attachment/show',{id: id}) + '" title="'+name+'">';
        } else {
            var html = '<a href="' + app.url('index/attachment/download',{id: id}) + '" title="'+name+'">'+name+'</a>';
        }
        UE.getEditor("content").execCommand('insertHtml', html);
    }
}

/** 
 * 框架盒子
 */
function iframeBox(title, src, ok, cancel) {
    ok     = ok || '确定';
    cancel = cancel || '取消';

    $('#iframe-box').__dialog({
        title: title,
        dialogClass: 'modal-lg',
        backdrop: 'static',
        html: '<iframe id="iframe-dialog" name="iframe-dialog" src="'+src+'" scrolling="auto" frameborder="0" width="100%" height="100%"></iframe>',
        buttons: [{
            text: ok,
            'class': "btn-info",
            click: function() {
                var dialog = window.frames['iframe-dialog'].iframeSave();
                if(dialog == true) {
                    $(this).find('#iframe-dialog').attr('src', 'about:blank');
                    $(this).dialog("close");
                }
            }
        },{
            text: cancel,
            'class': "btn-default",
            click: function() {
                window.frames['iframe-dialog'].iframeCancel();
                $(this).find('#iframe-dialog').attr('src', 'about:blank');
                $(this).dialog("close");
            }
        }]
    });
}

/**
 * 模式弹窗
 */
function dialogUser(title, url, id, multi)
{
    var url = app.url(url, {id:id, multi:multi});
    $('#dialog-user').__dialog({
        title: title,
        url: url,
        dialogClass: 'modal-lg',
        buttons: [{
            text: "确定",
            'class': "btn-default",
            click: function() {
                if(window.selectBox) {
                    window.selectBox.getSelecteds();
                } else {
                    $(this).dialog("close");
                }
            }
        }]
    });
}

/**
 * 显示窗口
 */
function viewBox(name, title, url, size) {
    size = size || 'md'
    $('#view-box').__dialog({
        title: title,
        url: url,
        dialogClass: 'modal-' + size,
        buttons: [{
            text: "确定",
            'class': "btn-default",
            click: function() {
                $(this).dialog("close");
            }
        }]
    });
}

var viewDialogIndex = 0;
function viewDialog(options) {

    if (options.id === undefined) {
        options.id = 'view-dialog-' + viewDialogIndex;
        viewDialogIndex ++;
    }

    var exist = $('#modal-' + options.id);
    if (exist.length > 0) {
        exist.dialog('show');
    }
    var defaults = {
        title: name,
        url: url,
        buttons: [{
            text: '确定',
            'class': 'btn-default',
            click: function() {
                $(this).dialog('close');
            }
        }]
    };
    var settings = $.extend({}, defaults, options);
    $('#' + options.id).__dialog(settings);
}

/**
 * 表单窗口
 */
var formDialogIndex = 0;
function formDialog(options)
{
    if(options.id === undefined) {
        options.id = 'form-dialog-' + formDialogIndex;
        formDialogIndex ++;
    }

    var exist = $('#modal-' + options.id);
    if(exist.length > 0) {
        exist.dialog('show');
    } else {
        var defaults = {
            title: 'formDialog',
            backdrop: 'static',
            buttons: [{
                text: '提交',
                class: 'btn-info',
                click: function() {
                    var me = this;
                    var options = me.options;

                    // 自定义提交函数
                    if (typeof options.onSubmit === 'function') {
                        options.onSubmit.call(me, res);
                    } else {
                        // 默认提交方法
                        var action = $('#' + options.id).attr('action');
                        var formData = $('#' + options.id).serializeArray();
                        var query = {};
                        $.each(formData, function(k, v) {
                            query[v.name] = v.value;
                        });

                        var loading = layer.msg('数据提交中...', {
                            icon: 16, shade: 0.1
                        });
                        $.post(action, formData, function(res) {
                            layer.close(loading);
                            if(res.status) {
                                if (typeof options.success === 'function') {
                                    options.success.call(me, res); 
                                }
                            } else {
                                if (typeof options.error === 'function') {
                                    options.error.call(me, res); 
                                }
                            }
                        },'json');
                    }
                }
            },{
                text: '取消',
                class: 'btn-default',
                click: function() {
                    var me = this;
                    if (typeof error === 'function') {
                        error.call(me, res); 
                    } else {
                        $(me).dialog("close");
                    }
                }
            }]
        };
        var settings = $.extend({}, defaults, options);
        $('#' + options.id).__dialog(settings);
    }
}

function optionSort(id, url) {
    var e = $(id);
    e.attr('action', url);
    e.submit();
}

function optionSubmit(id, url, confirm) {

    var form = $(id);
    var count = form.find("input.select-row[type='checkbox']:checked").length;

    function postData() {
        var formData = form.serialize();
        $.post(url, formData, function(res) {
            if(res.status) {
                if(res.url) {
                    // 返回页面刷新
                    self.location.href = res.url;
                }
                if(res.data == 'reload') {
                    location.reload();
                } else {
                    $.toastr('success', res.data);
                }
            } else {
                $.toastr('error', res.data);
            }
        },'json');
    }
    
    if(count > 0) {
        if(confirm) {
            top.$.messager.confirm('操作确认', confirm, function() {
                postData();
            });
        } else {
            postData();
        }

    } else {
        $.toastr('error', '最少选择一行记录。');
    }
}

function optionDefault(id, url, target) {

    var count = $("input.select-row[type='checkbox']:checked").length;

    var e = $(id);
    e.attr('action', url);
    if(target == '_blank') {
        e.attr('target', '_blank');
    }

    if(count > 0) {
        e.submit();
    } else {
        $.toastr('error', '最少选择一行记录。');
    }
}

function optionDelete(id, url, content) {
    var count = $("input.select-row[type='checkbox']:checked").length;
    content = content || '确定要删除吗？';

    var e = $(id);
    e.attr('action', url);
    e.attr('target', '_self');

    if(count > 0) {

        top.$.messager.confirm('操作确认', content, function() {
            e.submit();
        });

    } else {
        $.toastr('error', '最少选择一行记录。');
    }
}

// 转换时间，计算差值
function niceTime(timestamp) {
    // 当前时间戳
    var nowtime = (new Date).getTime();

    // 计算时间戳差值
    var secondNum = parseInt((nowtime-timestamp*1000)/1000);

    if(secondNum >= 0 && secondNum < 60) {
        return secondNum+'秒前';
    } else if (secondNum >= 60 && secondNum < 3600) {
        var nTime = parseInt(secondNum/60);
        return nTime+'分钟前';
    } else if (secondNum >= 3600 && secondNum < 3600*24) {
        var nTime = parseInt(secondNum/3600);
        return nTime+'小时前';
    }  else {
        var nTime = parseInt(secondNum/86400);
        return nTime+'天前';
    }
}

function ucfirst(str) {
    if(str) {
        return str[0].toUpperCase() + str.substr(1);
    } else {
        return str;
    }
}

/** 
* 数字金额大写转换(可以处理整数,小数,负数)
*/
function digitUppercase(n) {
    var fraction = ['角','分'];
    var digit = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
    var unit = [
        ['元', '万', '亿'],
        ['', '拾', '佰', '仟']
    ];
    var head = n < 0 ? '欠' : '';
    n = Math.abs(n);
    var s = '';
    for (var i = 0; i < fraction.length; i++) {  
        s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, '');  
    }  
    s = s || '整';
    n = Math.floor(n);
    for (var i = 0; i < unit[0].length && n > 0; i++) {
        var p = '';
        for (var j = 0; j < unit[1].length && n > 0; j++) {
            p = digit[n % 10] + unit[1][j] + p;
            n = Math.floor(n / 10);
        }
        s = p.replace(/(零.)*零$/, '').replace(/^$/, '零') + unit[0][i] + s;
    }
    return head + s.replace(/(零.)*零元/, '元').replace(/(零.)+/g, '零').replace(/^整$/, '零元整');  
};

/**
 * 检查变量是否为空
 */
function isEmpty(value) {
    if(value == '' || value == undefined || value == null) {
        return true;
    }
    return false;
}

/**
 * 清除字符串两边的空格
 */
String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, '');
}

// 数组过滤函数
if (!Array.prototype.filter) {
  Array.prototype.filter = function(fun /*, thisp*/)
    {
    var len = this.length;
    if (typeof fun != "function")
      throw new TypeError(); 
     var res = new Array();
    var thisp = arguments[1];
    for (var i = 0; i < len; i++)
    {
      if (i in this)
      {
        var val = this[i]; // in case fun mutates this
        if (fun.call(thisp, val, i, this))
          res.push(val);
      }
    } 
     return res;
  };
}

/**
 * 判断设备支持
 */
function isSmartDevice($window) {
    // Adapted from http://www.detectmobilebrowsers.com
    var ua = $window['navigator']['userAgent'] || $window['navigator']['vendor'] || $window['opera'];
    // Checks for iOs, Android, Blackberry, Opera Mini, and Windows mobile devices
    return (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua);
}

function url(uri, params) {
    query = (params == '' || params === undefined) ? '' : '?' + $.param(params);
    return settings.public_url + '/' + uri + query;
}

/**
 *  时间戳格式化
 */
function format_datetime(value) 
{
    function add0(v) {
        return v < 10 ? '0' + v : v;
    }
    value = parseInt(value) * 1000;
    var time = new Date(value);
    var y = time.getFullYear();
    var m = time.getMonth()+1;
    var d = time.getDate();
    var h = time.getHours();
    var mm = time.getMinutes();
    var s = time.getSeconds();
    return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm);
}

/** ajax 提交 */
function ajaxSubmit(table, callback) {

    // 监听关闭事件
    $('#' + table + '-form-close').on('click', function() {
        layerFrameClose();
    });

    // 监听提交事件
    $('#' + table + '-form-submit').on('click', function() {
        var me   = $('#' + table + '-form-edit');
        var url  = me.attr('action');
        var data = me.serialize();
        $.post(url, data, function(res) {
            if(typeof callback === 'function') {
                callback(res);
            } else {
                if(res.status) {
                    $.toastr('success', res.data);
                    if(res.url) {
                        self.location.href = res.url;
                    }
                } else {
                    $.toastr('error', res.data);
                }
            }
        }, 'json');
        return false;
    });
}

function layerFrameClose() {
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}