var select2List = {};

(function($) {

    $.fn.select2Field = function(options) {

        $this = $(this);

        var defaults = {
            width: '240px',
            placeholder:' - ',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                type: 'POST',
                url: '',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        field_0: options.search_key + '.name',
                        condition_0: 'like',
                        search_0: params.term,
                        page: params.page
                    };
                },
                processResults: function (res, params) {
                    params.page = params.page || 1;
                    return {
                        results: res.data,
                        pagination: {
                            more: params.page < res.last_page
                        }
                    };
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            }, 
            templateResult: function(m) {
                return m.text;
            }, 
            // 函数用来渲染结果
            templateSelection: function(m) {
                // 无备注就不是后台字段
                if(m.remark == undefined) {
                    return m.text;
                }
                return m.name;
            }
        };

        options = $.extend(true, {}, defaults, options);
        var select2 = $this.select2(options);
        /*
        select2.on('select2:open', function() {
            var data = $(this).data('select2');
            $('.select2-link').remove();
            data.$results.parents('.select2-results')
            .append('<div class="select2-link"><a> <i class="fa fa-plus-square"></i> 更多</a></div>')
            .on('click', function () {
                data.trigger('close');
            });
        });
        */
        return this;
    }
})(jQuery);