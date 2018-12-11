<style>
    .board-list-view {
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        height: 100%;
        position: relative;
        padding: 10px;
    }

    .board-list-view::-webkit-scrollbar {
        width: 10px;
        height: 10px
    }

    .board-list-view::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, .05);
        margin-left: 5px;
        margin-right: 5px;
        border-radius: 4px;
    }

    .board-list-view::-webkit-scrollbar-button:start:decrement,
    .board-list-view::-webkit-scrollbar-button:end:increment {
        background: transparent;
        display: none;
    }

    .board-list-view::-webkit-scrollbar-thumb:vertical,
    .board-list-view::-webkit-scrollbar-thumb:horizontal {
        background: #c2c2c2;
        border-radius: 4px;
        display: block;
        height: 40px;
    }

    .board-list-view .list {
        padding: 5px;
        background-color: #f3f5f7;
        color: #555;
        margin-right: 10px;
        border-radius: 4px;
        border: 1px solid #eee;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        height: 100%;
        max-height: 100%;
        position: relative;
        width: 260px;
        display: -webkit-inline-flex;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-align-items: stretch;
        -ms-flex-align: stretch;
        align-items: stretch;
        white-space: normal;
        vertical-align: top;
    }

    .ui-sortable-helper {
        transform: rotate(2deg);
        border-radius: 4px !important;
    }

    .board-list-view .ui-sortable-helper {
        box-shadow: 0 7px 21px rgba(0, 0, 0, 0.15);
    }

    .board-list-view .board-list-placeholder.list {
        border: 1px dashed #999;
        background-color: #eee;
    }

    .board-list-view .list:last-child {
        height: auto;
        margin-right: 0;
    }

    .board-list-view .list .list-content {

        max-width: 260px;
        min-height: 5px;
        position: relative;

        -moz-box-flex: 1;
        -webkit-box-flex: 1;

        line-height: 1.3;

        margin-bottom: 0;
        word-wrap: break-word;
        overflow-x: hidden;
        overflow-y: auto;

        padding: 0 5px;
    }

    .board-list-view .list .list-content .avatar {
        position: inherit
    }

    .board-list-view .list .list-content .panel {
        margin-bottom: 10px;
        border-radius: 4px !important;
    }

    .board-list-view .list .list-content .panel:last-child {
        margin-bottom: 2px;
    }

    .board-list-view .list .list-header {
        min-height: 26px;
        margin-bottom: 5px;
        margin-top: 0;
        max-width: 260px;
        padding: 7px 10px;
    }

    .board-list-view .list .list-footer {
        margin: 2px -3px -3px;
        padding: 7px 10px
    }

    .board-list-view .list .list-header,
    .board-list-view .list .list-footer {
        flex: 0 0 auto;
        -ms-flex: 0 0 auto;
        -webkit-flex: 0 0 auto;
        position: relative;
        -webkit-box-flex: 0;
        -moz-box-flex: 0
    }

    .board-list-view .list .vertical-scrollbar .dropdown-menu {
        width: 200px;
        margin-bottom: 1px
    }

    .board-list-view .list .panel {
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .15);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .15);
        border: 1px solid #fff
    }

    .board-list-view .list .panel.active {
        -webkit-box-shadow: 5px 5px 15px #f47564;
        box-shadow: 5px 5px 15px #f47564
    }

    .board-list-view .list .panel.active .card-id {
        background: #f47564;
        color: #fff
    }

    .board-list-view .dropdown-menu.arrow.arrow-right {
        right: -20px
    }

    .board-list-view textarea.form-control {
        resize: none
    }

    .list-card-group-addon {
        padding: 0;
    }

    .card-list-placeholder {
        margin-bottom: 8px;
        min-height: 40px;
        background: #eee;
        border: 1px dashed #c2c2c2;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05)
    }
    .card-list-placeholder:last-child {
        margin-bottom: 0;
    }

    .vertical-scrollbar,
    .dockmodal-body {
        overflow: auto
    }

    .vertical-scrollbar::-webkit-scrollbar,
    .dockmodal-body::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .vertical-scrollbar::-webkit-scrollbar-track,
    .dockmodal-body::-webkit-scrollbar-track {
        background: #dbdbdb;
        border-radius: 4px;
    }

    .vertical-scrollbar::-webkit-scrollbar-thumb,
    .dockmodal-body::-webkit-scrollbar-thumb {
        background: #c2c2c2;
        border-radius: 40px
    }

    .vertical-scrollbar::-webkit-scrollbar-thumb:vertical,
    .dockmodal-body::-webkit-scrollbar-thumb:vertical,
    .vertical-scrollbar::-webkit-scrollbar-thumb:horizontal,
    .dockmodal-body::-webkit-scrollbar-thumb:horizontal {
        background: #c2c2c2;
        border-radius: 4px;
    }

    .vertical-scrollbar::-webkit-scrollbar-thumb:vertical:hover,
    .dockmodal-body::-webkit-scrollbar-thumb:vertical:hover,
    .vertical-scrollbar::-webkit-scrollbar-thumb:horizontal:hover,
    .dockmodal-body::-webkit-scrollbar-thumb:horizontal:hover {
        background: #c2c2c2;
    }

    .vertical-scrollbar::-webkit-scrollbar-corner,
    .dockmodal-body::-webkit-scrollbar-corner {
        background: transparent;
    }

</style>

<script>

    function iframeResize() {
        var iframeHeight = $(window).height();
        var height = $('#gantt-wrapper').outerHeight();
        $('#js-board-lists').outerHeight(iframeHeight - height - 25 + 'px');
    }

    var previous_id = 0;
    var previous_offset_vertical = '';
    var is_moving_top = '';
    var previous_move_vertical = '';
    var setintervalid_vertical = '';

    var previous_offset = '';
    var previous_move = '';
    var setintervalid = '';
    var setintervalid_horizontal = '';
    var previous_offset_horizontal = '';
    var previous_move_horizontal = '';

    $(function () {

        iframeResize();

        $(window).resize(function () {
            iframeResize();
        });

        $('#js-board-lists').sortable({
            containment: 'window',
            axis: 'x',
            items: 'div.js-board-list',
            placeholder: 'board-list-placeholder list',
            forcePlaceholderSize: true,
            cursor: 'grab',
            scrollSensitivity: 100,
            scrollSpeed: 50,
            handle: '.js-list-head',
            tolerance: 'pointer',
            update: function (ev, ui) {
                // ui.item.trigger('listSort', ev, ui);
            },
            stop: function(ev, ui) {
                    clearInterval(setintervalid);
                    is_create_setinterval = true;
                    previous_offset = 0;
                    $(ev.target).find('.js-list-head').addClass('cur-grab');
                },
                over: function(ev, ui) {
                    var scrollLeft = 0;
                    var list_per_page = Math.floor($(window).width() / 270);
                    if (previous_offset !== 0 && previous_offset != ui.offset.left) {
                        if (previous_offset > ui.offset.left) {
                            is_moving_right = false;
                        } else {
                            is_moving_right = true;
                        }
                    }
                    if (previous_move !== is_moving_right) {
                        clearInterval(setintervalid);
                        is_create_setinterval = true;
                    }
                    if (is_moving_right === true && ui.offset.left > (list_per_page - 1) * 270) {
                        if (is_create_setinterval) {
                            setintervalid = setInterval(function() {
                                scrollLeft = parseInt($('#js-board-lists').scrollLeft()) + 50;
                                $('#js-board-lists').animate({
                                    scrollLeft: scrollLeft
                                }, 10);
                            }, 100);
                            is_create_setinterval = false;
                        }
                    } else if (is_moving_right === false && ui.offset.left < 50) {
                        if (is_create_setinterval) {
                            setintervalid = setInterval(function() {
                                scrollLeft = parseInt($('#js-board-lists').scrollLeft()) - 50;
                                $('#js-board-lists').animate({
                                    scrollLeft: scrollLeft
                                }, 10);
                            }, 100);
                            is_create_setinterval = false;
                        }
                    }
                    previous_offset = ui.offset.left;
                    previous_move = is_moving_right;
                },
            start: function (ev, ui) {
                // ui.placeholder.height(ui.item.outerHeight());
                ui.item.css({ top: '10px' });
                //$(ev.target).find('.js-list-head').removeClass('cur-grab');
                //$(ev.target).find('.js-list-head').children('div.dropdown').removeClass('open');
            },
            stop1: function (ev, ui) {
                //$(ev.target).find('.js-list-head').addClass('cur-grab');
            },
            over1: function (ev, ui) {
            }
        });

        var wrap = null;

        var setintervalid_vertical = null;

        $('.js-board-list-cards').sortable({
            items: 'div.js-board-list-card',
            connectWith: '.js-board-list-cards',
            placeholder: 'card-list-placeholder',
            containment: 'body',
            appendTo: 'body',
            cursor: 'grab',
            helper: 'clone',
            tolerance: 'pointer',
            scrollSensitivity: 30,
            update: function (ev, ui) {
                // if (this === ui.item.parent()[0]) {
                // ui.item.trigger('cardSort', ev, ui);
                // }
            },
            start: function (ev, ui) {
                ui.helper.height(ui.item.outerHeight() + 10);
                ui.placeholder.height(ui.item.outerHeight());

                //console.log(ui.placeholder.offset());

                //ui.helper.css({top: ui.placeholder.offset().top});
                // $('.js-show-modal-card-view').removeClass('cur');
            },
            stop: function (ev, ui) {
                // $('.js-show-modal-card-view').addClass('cur');
                clearInterval(setintervalid_horizontal);
                        clearInterval(setintervalid_vertical);
                        is_create_setinterval_horizontal = true;
                        is_create_setinterval_vertical = true;
                        previous_offset_horizontal = 0;
                        previous_offset_vertical = 0;
                        is_create_setinterval_mobile = true;
                        previous_offset_mobile = 0;
                        is_moving_right_mobile = 0;
                        previous_move_mobile = 0;
                        previous_move_horizontal = 0;
                        previous_move_vertical = 0;
            },
            over: function (ev, ui) {

                if ($(ui.placeholder).parents('.js-board-list-cards').attr('id') == previous_id) {
                    clearInterval(setintervalid_horizontal);
                }

                var scrollLeft = 0;
                var list_per_page = Math.floor($(window).width() / 270);
                if (previous_offset_horizontal !== 0 && previous_offset_horizontal != ui.offset.left) {
                    if (previous_offset_horizontal > ui.offset.left) {
                        is_moving_right = false;
                    } else {
                        is_moving_right = true;
                    }
                }
                if (previous_move_horizontal !== is_moving_right) {
                    clearInterval(setintervalid_horizontal);
                    is_create_setinterval_horizontal = true;
                }
                if (is_moving_right === true && ui.offset.left > (list_per_page - 1) * 230) {
                    if (is_create_setinterval_horizontal) {
                        setintervalid_horizontal = setInterval(function() {
                            scrollLeft = parseInt($('#js-board-lists').scrollLeft()) + 50;
                            $('#js-board-lists').scrollLeft(scrollLeft);
                        }, 50);
                        is_create_setinterval_horizontal = false;
                    }
                } else if (is_moving_right === false && ui.offset.left < (list_per_page - 1) * 100) {
                    if (is_create_setinterval_horizontal) {
                        setintervalid_horizontal = setInterval(function() {
                            scrollLeft = parseInt($('#js-board-lists').scrollLeft()) - 50;
                            $('#js-board-lists').scrollLeft(scrollLeft);
                        }, 50);
                        is_create_setinterval_horizontal = false;
                    }
                }
                previous_offset_horizontal = ui.offset.left;
                previous_move_horizontal = is_moving_right;
            },
            sort: function (event, ui) {

                /*
                //setintervalid_vertical = null;
                wrap = ui.item.parent();

                // console.log(activePhaseBlock.scrollTop());
                
                //var scrollTop = activePhaseBlock.scrollTop();
                var is = false;

                //var scrollTop = activePhaseBlock.scrollTop();

                //console.log(wrap.scrollTop());

                
                if(ui.offset.top - ui.item.height() < 40 && wrap.scrollTop() > 0) {

                    clearInterval(setintervalid_vertical);

                    setintervalid_vertical = setInterval(function () {
                        wrap.scrollTop(wrap.scrollTop() - 10);
                    }, 10);

                    //console.log(111);

                } else if(wrap.height() - ui.offset.top < 40) {

                    clearInterval(setintervalid_vertical);
                    
                    setintervalid_vertical = setInterval(function () {
                        wrap.scrollTop(wrap.scrollTop() + 10);
                    }, 10);

                    //clearInterval(setintervalid_vertical);

                    //console.log(222);
                }
                */

                previous_id = $(ui.placeholder).parents('.js-board-list-cards').attr('id');
                var scrollTop = 0;
                var decrease_height = 0;
                var list_height = $('#' + previous_id).height();
                var additional_top = parseInt($('#js-board-lists').position().top) + parseInt($('#' + previous_id).position().top);
                var total_top = parseInt(list_height) + parseInt(additional_top);
                if (ui.placeholder.height() > list_height) {
                    decrease_height = parseInt(ui.placeholder.height()) - parseInt(list_height);
                } else {
                    decrease_height = parseInt(list_height) - parseInt(ui.placeholder.height());
                }
                var total_top1 = (parseInt($('#js-board-lists').position().top) + parseInt(ui.placeholder.position().top)) - decrease_height;
                if (previous_offset_vertical !== 0) {
                    if (previous_offset_vertical > ui.offset.top) {
                        is_moving_top = false;
                    } else {
                        is_moving_top = true;
                    }
                }
                if (previous_move_vertical !== is_moving_top) {
                    clearInterval(setintervalid_vertical);
                    is_create_setinterval_vertical = true;
                }
                if (is_moving_top === true && (ui.offset.top > total_top || (total_top1 > 0 && ui.offset.top > total_top1))) {
                    if (is_create_setinterval_vertical) {
                        setintervalid_vertical = setInterval(function () {

                            scrollTop = parseInt($('#' + previous_id).scrollTop()) + 10;
                            $('#' + previous_id).scrollTop(scrollTop);

                        }, 10);
                        is_create_setinterval_vertical = false;
                    }
                } else if (is_moving_top === false && ui.offset.top < (additional_top)) {
                    if (is_create_setinterval_vertical) {
                        setintervalid_vertical = setInterval(function () {
                            scrollTop = parseInt($('#' + previous_id).scrollTop()) - 10;
                            $('#' + previous_id).scrollTop(scrollTop);
                        }, 10);
                        is_create_setinterval_vertical = false;
                    }
                }
                previous_offset_vertical = ui.offset.top;
                previous_move_vertical = is_moving_top;
                
            }
        });

    });

</script>

<div class="panel">

    <div class="wrapper" id="gantt-wrapper">
        <form id="search-form" class="form-inline" name="mysearch" method="get">

            <div class="pull-right">

                <div class="btn-group">
                    <a href="{{url('index', ['project_id' => $project['id'], 'tpl' => 'index'])}}" class="btn btn-sm btn-default @if($query['tpl'] == 'index') active @endif">甘特图</a>
                    <a href="{{url('index', ['project_id' => $project['id'], 'tpl' => 'board'])}}" class="btn btn-sm btn-default @if($query['tpl'] == 'board') active @endif">看板</a>
                </div>

            </div>

            <a href="javascript:history.back();" class="btn btn-sm btn-default">
                <i class="fa fa-reply"></i> 返回</a>

                @include('searchForm')
                
                <script type="text/javascript">
                $(function() {
                    $('#search-form').searchForm({
                        data: {{json_encode($search['forms'])}},
                        init:function(e) {
                            var self = this;
                        }
                    });
                });
                </script>
                
            
        </form>
    </div>


    <div id="js-board-lists" class="board-list-view boardlist-scrollbar b-t">

        <div data-list_id="204" class="js-board-list list">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <span>
                                    <i class="list-added-204 icon-tasks icon-large" style="color:#f47564"></i>
                                </span>
                                <strong class="get-name-204">36</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-204 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="36" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-204" data-toggle="dropdown"
                                        title="List Actions" data-list-id="204">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-204" data-toggle="dropdown"
                                        title="Sort" data-list-id="204">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-204" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-204">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="163"
                    id="js-card-163" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-163"></div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/12.d962dc624106a57dca038defd2d02455.png">
                            </div>

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">

                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>
                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>
                                <li class="pull-right card-id">
                                    <strong>#163</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>

                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="163"
                    id="js-card-163" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-163">



                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/12.d962dc624106a57dca038defd2d02455.png">
                            </div>

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#163</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>

                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="163"
                    id="js-card-163" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-163">



                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/12.d962dc624106a57dca038defd2d02455.png">
                            </div>

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#163</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>

                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="163"
                    id="js-card-163" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-163">



                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/12.d962dc624106a57dca038defd2d02455.png">
                            </div>

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#163</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>

                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="163"
                    id="js-card-163" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-163">



                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/12.d962dc624106a57dca038defd2d02455.png">
                            </div>

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#163</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>

                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="164"
                    id="js-card-164" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="feature">feature</li>
                        <li class="minor">minor</li>
                    </ul>
                    <ul class="js-card-users hide">
                        <li>DaveHyatt </li>
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-164">

                            <span class="cur">
                                <i style="color:#518a48;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="feature"
                                    title="" class="icon-circle cur"></i>
                            </span>


                            <span class="cur">
                                <i style="color:#ab846c;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="minor" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 5 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/5</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#164</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 7, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 16, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Dave Hyatt (DaveHyatt )">

                                    <i class="avatar avatar-color-194 img-rounded">DH</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="173"
                    id="js-card-173" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-173">



                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/14.6cdeb2e2c28134c261fc6e76fcfd04f1.png">
                            </div>

                            <a href="#" title="HTML5 Issues">HTML5 Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 12 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/12</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#173</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="174"
                    id="js-card-174" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-174">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="DEVELOPER">DEVELOPER</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#174</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="180"
                    id="js-card-180" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-180">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="3 checklist completed out of 3 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>3/3</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#180</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="207"
                    id="js-card-207" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>MarkBanner</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-207">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="UNRESOLVED Issues">UNRESOLVED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#207</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mark Banner (MarkBanner)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-204"></div>
            </div>

        </div>

        <div data-list_id="205" class="js-board-list list">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <span>
                                    <i class="list-added-205 icon-tasks icon-large" style="color:#f47564"></i>
                                </span>
                                <strong class="get-name-205">36.0.1</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-205 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="36.0.1" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-205" data-toggle="dropdown"
                                        title="List Actions" data-list-id="205">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right arrow arrow-right js-list-actions-response">
                                        <li class="text-center">
                                            <strong>List Actions</strong>
                                            <a class="pull-right js-list-action-hover-icon">
                                                <i class=" icon-remove"></i>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li class="dropdown js-show-list-color-settings-dropdown">
                                            <a class="dropdown-toggle js-show-list-color-settings" href="#" title="Color" data-toggle="dropdown">
                                                <i class="text-primary"></i>Color</a>
                                            <ul class="dropdown-menu dropdown-menu-left arrow col-xs-12">

                                                <li class="col-xs-12 clearfix text-center">
                                                    <div>
                                                        <span class="col-xs-10">
                                                            <strong>Select a color</strong>
                                                        </span>
                                                        <a class="js-close-popover pull-right" href="#">
                                                            <i class="icon-remove "></i>
                                                        </a>
                                                    </div>
                                                </li>

                                                <li class="col-xs-12 divider" id="js-list-demo-205"></li>


                                                <ul class="list-color-pad list-inline js-list-inline list-color-alignment">
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#34495e">
                                                        <span class="btn btn-default show well-sm" style="background-color:#34495e"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#23719f">
                                                        <span class="btn btn-default show well-sm" style="background-color:#23719f"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#5a966e">
                                                        <span class="btn btn-default show well-sm" style="background-color:#5a966e"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#bf4a40">
                                                        <span class="btn btn-default show well-sm" style="background-color:#bf4a40"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#444444">
                                                        <span class="btn btn-default show well-sm" style="background-color:#444444"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#4f4d7e">
                                                        <span class="btn btn-default show well-sm" style="background-color:#4f4d7e"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#2f663c">
                                                        <span class="btn btn-default show well-sm" style="background-color:#2f663c"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#a37e58">
                                                        <span class="btn btn-default show well-sm" style="background-color:#a37e58"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#5f778e">
                                                        <span class="btn btn-default show well-sm" style="background-color:#5f778e"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#0091ff">
                                                        <span class="btn btn-default show well-sm" style="background-color:#0091ff"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#46ba97">
                                                        <span class="btn btn-default show well-sm" style="background-color:#46ba97"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#f47564">
                                                        <span class="btn btn-default show well-sm" style="background-color:#f47564"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#6b6b6b">
                                                        <span class="btn btn-default show well-sm" style="background-color:#6b6b6b"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#7b5cb3">
                                                        <span class="btn btn-default show well-sm" style="background-color:#7b5cb3"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#65ab36">
                                                        <span class="btn btn-default show well-sm" style="background-color:#65ab36"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#bd6f32">
                                                        <span class="btn btn-default show well-sm" style="background-color:#bd6f32"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#819da2">
                                                        <span class="btn btn-default show well-sm" style="background-color:#819da2"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#47b7cd">
                                                        <span class="btn btn-default show well-sm" style="background-color:#47b7cd"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#95d9ad">
                                                        <span class="btn btn-default show well-sm" style="background-color:#95d9ad"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#f7b09c">
                                                        <span class="btn btn-default show well-sm" style="background-color:#f7b09c"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#999999">
                                                        <span class="btn btn-default show well-sm" style="background-color:#999999"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#baa1e6">
                                                        <span class="btn btn-default show well-sm" style="background-color:#baa1e6"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#9dbb1d">
                                                        <span class="btn btn-default show well-sm" style="background-color:#9dbb1d"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#ffce54">
                                                        <span class="btn btn-default show well-sm" style="background-color:#ffce54"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#b3bec1">
                                                        <span class="btn btn-default show well-sm" style="background-color:#b3bec1"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#bee5f3">
                                                        <span class="btn btn-default show well-sm" style="background-color:#bee5f3"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#caefd2">
                                                        <span class="btn btn-default show well-sm" style="background-color:#caefd2"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#f7d2c8">
                                                        <span class="btn btn-default show well-sm" style="background-color:#f7d2c8"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#d5d5d5">
                                                        <span class="btn btn-default show well-sm" style="background-color:#d5d5d5"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#dbcff1">
                                                        <span class="btn btn-default show well-sm" style="background-color:#dbcff1"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#ccdc87">
                                                        <span class="btn btn-default show well-sm" style="background-color:#ccdc87"></span>
                                                    </li>
                                                    <li class="list-inline js-change-color js-list-color-pick navbar-btn cur" data-color="#f1eabf">
                                                        <span class="btn btn-default show well-sm" style="background-color:#f1eabf"></span>
                                                    </li>

                                                    <button type="button" class="hide btn btn-primary js-remove-list-color">Remove color</button>

                                                </ul>
                                                <div class="well-sm"></div>
                                            </ul>
                                        </li>
                                        <li class="panel-settings" id="js-list-setting-205">
                                        </li>

                                        <li>
                                            <a href="#" title="Add Card" class="js-show-add-card-form hide">Add Card</a>
                                        </li>
                                        <li class="js-workflow-cards-button dropdown">
                                            <a class="dropdown-toggle js-show-additional-settings" href="#" title="Additional Settings" data-toggle="dropdown">
                                                <i class="text-primary"></i>Additional Settings</a>
                                            <ul class="dropdown-menu dropdown-menu-left arrow col-xs-12" id="js-workflow-cards-form-205">
                                                <li class="col-xs-12 time-block">
                                                    <div class="col-xs-12 js-workflow-cards-form">
                                                        <div class="well-sm"></div>
                                                        <form class="form-horizontal">
                                                            <div data-fieldsets="">
                                                                <fieldset data-fields="">
                                                                    <div class="form-group">
                                                                        <label for="auto_archive_days"> Auto Archive Days </label>
                                                                        <div>
                                                                            <span data-editor="">
                                                                                <select id="auto_archive_days" class="form-control" name="auto_archive_days">
                                                                                    <option value="">Please Select</option>
                                                                                    <option>1</option>
                                                                                    <option>2</option>
                                                                                    <option>3</option>
                                                                                    <option>4</option>
                                                                                    <option>5</option>
                                                                                    <option>6</option>
                                                                                    <option>7</option>
                                                                                    <option>8</option>
                                                                                    <option>9</option>
                                                                                    <option>10</option>
                                                                                    <option>11</option>
                                                                                    <option>12</option>
                                                                                    <option>13</option>
                                                                                    <option>14</option>
                                                                                    <option>15</option>
                                                                                    <option>16</option>
                                                                                    <option>17</option>
                                                                                    <option>18</option>
                                                                                    <option>19</option>
                                                                                    <option>20</option>
                                                                                    <option>21</option>
                                                                                    <option>22</option>
                                                                                    <option>23</option>
                                                                                    <option>24</option>
                                                                                    <option>25</option>
                                                                                    <option>26</option>
                                                                                    <option>27</option>
                                                                                    <option>28</option>
                                                                                    <option>29</option>
                                                                                    <option>30</option>
                                                                                    <option>31</option>
                                                                                </select>
                                                                            </span>
                                                                            <div data-error=""></div>
                                                                            <div></div>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary js-custom-field-submit-btn">Submit</button>
                                                        </form>
                                                        <div class="well-sm"></div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="divider"></li>

                                        <li class="js-hide-on-offline">
                                            <a href="#" title="Copy List" class="js-show-copy-list-form">Copy List</a>
                                        </li>


                                        <li>
                                            <a href="#" title="Move List" class="js-show-move-list-form" data-list-id="205">Move List</a>
                                        </li>



                                        <li class="">
                                            <a href="javascript:void(0);" title="Subscribe" class="highlight-icon js-list-subscribe" data-list-id="205" data-subscribe-id="">
                                                <span class="js-subscribe-text">Subscribe</span>
                                                <i class="icon-ok hide"></i>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#" title="Show Attachments" class="js-show-list-modal">Show Attachments</a>
                                        </li>
                                        <li class="divider"></li>

                                        <li>
                                            <a href="#" title="Move All Cards in This List" class="js-show-move-card-list-form" data-list-id="205">Move All Cards in This List</a>
                                        </li>


                                        <li>
                                            <a href="#" title="Archive All Cards in this List" class="js-show-confirm-archive-cards" data-list-id="205">Archive All Cards in this List</a>
                                        </li>

                                        <li class="divider"></li>

                                        <li>
                                            <a href="#" title="Archive This List" class="js-show-confirm-archive-list" data-list-id="205">Archive This List</a>
                                        </li>


                                        <li>
                                            <a href="#" title="Delete this List" class="js-show-confirm-list-delete" data-list-id="205">
                                                <i class="icon-sm icon-warning-sign icon-type text-primary"></i>Delete this List</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-205" data-toggle="dropdown"
                                        title="Sort" data-list-id="205">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-205" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-205">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="352"
                    id="js-card-352" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>DaveHyatt </li>
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-352">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">





                                <li>
                                    <small title="Description">
                                        <span class="icon-align-left"></span>
                                        <span></span>
                                    </small>
                                </li>


                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#352</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 11, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 17, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Dave Hyatt (DaveHyatt )">

                                    <i class="avatar avatar-color-194 img-rounded">DH</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="235"
                    id="js-card-235" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="css">css</li>
                    </ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-235">

                            <span class="cur">
                                <i style="color:#c7a628;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="css" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">





                                <li>
                                    <small title="Description">
                                        <span class="icon-align-left"></span>
                                        <span></span>
                                    </small>
                                </li>


                                <li>
                                    <small title="2 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>2/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#235</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="269"
                    id="js-card-269" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="urgent">urgent</li>
                    </ul>
                    <ul class="js-card-users hide">
                        <li>DaveHyatt </li>
                        <li>BrianGrinstead</li>
                        <li>MikedeBoer</li>
                        <li>BlakeRoss</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-269">

                            <span class="cur">
                                <i style="color:#8ff271;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="urgent" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="DEVELOPER">DEVELOPER</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">

                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#269</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Dave Hyatt (DaveHyatt )">

                                    <i class="avatar avatar-color-194 img-rounded">DH</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mikede Boer (MikedeBoer)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Blake Ross (BlakeRoss)">

                                    <img src="/img/small_thumb/User/12.34593d1e8c490f8ccf1ac5405404ab87.png" alt="[Image: BlakeRoss]" title="Blake Ross (BlakeRoss)"
                                        class="img-rounded img-responsive avatar">

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="246"
                    id="js-card-246" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-246">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="HTML5 Issues">HTML5 Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 12 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/12</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#246</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 3, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 18, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="279"
                    id="js-card-279" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-279">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="12 checklist completed out of 12 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>12/12</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#279</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="342"
                    id="js-card-342" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-342">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="UNRESOLVED Issues">UNRESOLVED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#342</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted hide">Add a card</a>
                </div>
                <div class="js-card-add-form-205">
                    <div id="js-list-card-add-form-205" class="panel js-board-list-card cur">
                        <div class="js-lables-list"></div>
                        <form method="post" role="form" class="form-horizontal js-cardAddForm col-xs-12" name="cardAddForm">
                            <input type="hidden" name="board_id" value="7">
                            <input type="hidden" name="list_id" class="js-card-add-list" value="205">
                            <input type="hidden" name="user_ids" class="js-card-user-ids" value="">
                            <input type="hidden" name="card_labels" class="js-card-add-labels" value="">
                            <input type="hidden" name="position" class="js-card-add-position" value="">
                            <div class="form-group">
                                <textarea placeholder=" Add a card" rows="3" id="AddCard" class="form-control" name="name" required=""></textarea>
                            </div>
                            <div class="row js-users-list">
                                <ul class="list-unstyled list-inline text-muted clearfix"></ul>
                            </div>
                            <div class="row">
                                <div class="pull-left">
                                    <input type="submit" value="Add" class="btn btn-primary js-cardAddForm-btn">
                                </div>
                                <ul class="list-unstyled pull-right">
                                    <li class="pull-left">
                                        <a title="Cancel" href="#" class="btn btn-link js-cancel-card-add">
                                            <i class="icon-remove text-muted"></i>
                                        </a>
                                    </li>
                                    <li class="pull-right dropdown inner-dropdown">
                                        <a title="Options" data-toggle="dropdown" class="btn btn-link btn-block dropdown-toggle js-show-card-action-list" href="#">
                                            <i class="icon-cog text-muted"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div data-list_id="206" class="js-board-list list" style="position: relative; left: 0px; top: 0px;">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <strong class="get-name-206">36.0.3</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-206 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="36.0.3" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-206" data-toggle="dropdown"
                                        title="List Actions" data-list-id="206">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-206" data-toggle="dropdown"
                                        title="Sort" data-list-id="206">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-206" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-206">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="351"
                    id="js-card-351" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="block">block</li>
                        <li class="urgent">urgent</li>
                    </ul>
                    <ul class="js-card-users hide">
                        <li>user</li>
                        <li>MikedeBoer</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-351">

                            <span class="cur">
                                <i style="color:#14511f;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="block" title=""
                                    class="icon-circle cur"></i>
                            </span>


                            <span class="cur">
                                <i style="color:#8ff271;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="urgent" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#351</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="User (user)">

                                    <i class="avatar avatar-color-194 img-rounded">U</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mikede Boer (MikedeBoer)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="353"
                    id="js-card-353" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="block">block</li>
                    </ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-353">

                            <span class="cur">
                                <i style="color:#14511f;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="block" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="HTML5 issues">HTML5 issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">



                                <li>
                                    <small title="1 Vote ">
                                        <span class="icon-thumbs-up"></span>
                                        <span>1</span>
                                    </small>
                                </li>




                                <li>
                                    <small title="0 checklist completed out of 12 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/12</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#353</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="354"
                    id="js-card-354" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>MikedeBoer</li>
                        <li>MarkBanner</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>week</li>
                        <li>month</li>
                        <li>day</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-354">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="DEVELOPER">DEVELOPER</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#354</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 8, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-future">Oct 21, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mikede Boer (MikedeBoer)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mark Banner (MarkBanner)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="357"
                    id="js-card-357" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-357">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="13 checklist completed out of 13 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>13/13</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#357</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="397"
                    id="js-card-397" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>MikedeBoer</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-397">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="UNRESOLVED Issues">UNRESOLVED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#397</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mikede Boer (MikedeBoer)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-206"></div>
            </div>

        </div>
        <div data-list_id="207" class="js-board-list list" style="position: relative; left: 0px; top: 0px;">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <strong class="get-name-207">36.0.4</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-207 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="36.0.4" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-207" data-toggle="dropdown"
                                        title="List Actions" data-list-id="207">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-207" data-toggle="dropdown"
                                        title="Sort" data-list-id="207">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-207" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-207">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="435"
                    id="js-card-435" style="border-left-width: 8px; display: block;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-435">
                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/15.f5dc9be6eca8e497fad50b9224734101.png">
                            </div>

                            <a href="#" title="HTML5 Issues">HTML5 Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="3 checklist completed out of 12 ">
                                        <span class="icon-list-ul"></span>
                                        <span>3/12</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#435</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="415"
                    id="js-card-415" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="block">block</li>
                    </ul>
                    <ul class="js-card-users hide">
                        <li>DaveHyatt </li>
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-415">

                            <span class="cur">
                                <i style="color:#14511f;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="block" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#415</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 13, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-future">Oct 20, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Dave Hyatt (DaveHyatt )">

                                    <i class="avatar avatar-color-194 img-rounded">DH</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="426"
                    id="js-card-426" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="minor">minor</li>
                    </ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-426">

                            <span class="cur">
                                <i style="color:#ab846c;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="minor" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">





                                <li>
                                    <small title="Description">
                                        <span class="icon-align-left"></span>
                                        <span></span>
                                    </small>
                                </li>


                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#426</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 1, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 15, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="447"
                    id="js-card-447" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-447">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="DEVELOPER">DEVELOPER</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#447</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="456"
                    id="js-card-456" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-456">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="14 checklist completed out of 14 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>14/14</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#456</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="492"
                    id="js-card-492" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>MarkBanner</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-492">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="UNRESOLVED issues">UNRESOLVED issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#492</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mark Banner (MarkBanner)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-207"></div>
            </div>

        </div>
        <div data-list_id="208" class="js-board-list list" style="position: relative; left: 0px; top: 0px;">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <span>
                                    <i class="list-added-208 icon-spinner icon-large" style="color:#27c5c3"></i>
                                </span>
                                <strong class="get-name-208">37</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-208 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="37" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-208" data-toggle="dropdown"
                                        title="List Actions" data-list-id="208">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-208" data-toggle="dropdown"
                                        title="Sort" data-list-id="208">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-208" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-208">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="510"
                    id="js-card-510" style="border-left-width: 8px; display: block;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-510">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">



                                <li>
                                    <small title="1 Vote ">
                                        <span class="icon-thumbs-up"></span>
                                        <span>1</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="Description">
                                        <span class="icon-align-left"></span>
                                        <span></span>
                                    </small>
                                </li>


                                <li>
                                    <small title="0 checklist completed out of 5 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/5</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#510</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="524"
                    id="js-card-524" style="border-left-width: 8px; display: block;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>DaveHyatt </li>
                        <li>MarkBanner</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-524">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 5 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/5</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#524</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 2, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 17, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Dave Hyatt (DaveHyatt )">

                                    <i class="avatar avatar-color-194 img-rounded">DH</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mark Banner (MarkBanner)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="550"
                    id="js-card-550" style="border-left-width: 8px; display: block;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-550">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="1 checklist completed out of 1 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>1/1</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#550</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="549"
                    id="js-card-549" style="border-left-width: 8px; display: block;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-549">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="DEVELOPER">DEVELOPER</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 5 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/5</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#549</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="538"
                    id="js-card-538" style="border-left-width: 8px; display: block;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-538">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="HTML5 Issues">HTML5 Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">





                                <li>
                                    <small title="Description">
                                        <span class="icon-align-left"></span>
                                        <span></span>
                                    </small>
                                </li>


                                <li>
                                    <small title="2 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>2/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#538</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-208"></div>
            </div>

        </div>
        <div data-list_id="209" class="js-board-list list">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <span>
                                    <i class="list-added-209 icon-spinner icon-large" style="color:#27c5c3"></i>
                                </span>
                                <strong class="get-name-209">37.0.1</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-209 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="37.0.1" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-209" data-toggle="dropdown"
                                        title="List Actions" data-list-id="209">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-209" data-toggle="dropdown"
                                        title="Sort" data-list-id="209">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-209" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-209">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="551"
                    id="js-card-551" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-551">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 1 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/1</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#551</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="223"
                    id="js-card-223" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>user</li>
                        <li>DaveHyatt </li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-223">



                        </div>

                        <div class="clearfix">

                            <div class="clearfix js-card-attachment-image navbar-btn ">

                                <img class="img-responsive center-block" src="/img/large_thumb/CardAttachment/16.d3b2004a254402297e0ef0cc0d61afba.png">
                            </div>

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">



                                <li>
                                    <small title="1 Vote ">
                                        <span class="icon-thumbs-up"></span>
                                        <span>1</span>
                                    </small>
                                </li>




                                <li>
                                    <small title="1 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>1/3</span>
                                    </small>
                                </li>



                                <li>
                                    <small title="1 Attachment ">
                                        <span class="icon-paper-clip"></span>
                                        <span>
                                            1
                                        </span>
                                    </small>
                                </li>


                                <li class="pull-right card-id">
                                    <strong>#223</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="User (user)">

                                    <i class="avatar avatar-color-194 img-rounded">U</i>

                                </li>

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Dave Hyatt (DaveHyatt )">

                                    <i class="avatar avatar-color-194 img-rounded">DH</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="552"
                    id="js-card-552" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="urgent">urgent</li>
                    </ul>
                    <ul class="js-card-users hide">
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-552">

                            <span class="cur">
                                <i style="color:#8ff271;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="urgent" title=""
                                    class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="2 checklist completed out of 2 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>2/2</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#552</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 12, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 17, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-209"></div>
            </div>

        </div>
        <div data-list_id="210" class="js-board-list list">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <span>
                                    <i class="list-added-210 icon-spinner icon-large" style="color:#27c5c3"></i>
                                </span>
                                <strong class="get-name-210">37.0.2</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-210 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="37.0.2" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-210" data-toggle="dropdown"
                                        title="List Actions" data-list-id="210">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-210" data-toggle="dropdown"
                                        title="Sort" data-list-id="210">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-210" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-210">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="554"
                    id="js-card-554" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels">
                        <li class="crash">crash</li>
                        <li class="trivial">trivial</li>
                    </ul>
                    <ul class="js-card-users hide">
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-554">

                            <span class="cur">
                                <i style="color:#dcaa9f;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="crash" title=""
                                    class="icon-circle cur"></i>
                            </span>


                            <span class="cur">
                                <i style="color:#156ecc;" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="trivial"
                                    title="" class="icon-circle cur"></i>
                            </span>




                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="3 checklist completed out of 3 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>3/3</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#554</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 4, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 15, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-210"></div>
            </div>

        </div>
        <div data-list_id="211" class="js-board-list list">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <strong class="get-name-211">38</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-211 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="38" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-211" data-toggle="dropdown"
                                        title="List Actions" data-list-id="211">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-211" data-toggle="dropdown"
                                        title="Sort" data-list-id="211">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-211" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-211">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="564"
                    id="js-card-564" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-564">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="NEW Issues">NEW Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 3 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/3</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#564</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="577"
                    id="js-card-577" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide">
                        <li>overdue</li>
                    </ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-577">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="CHANGED Issues">CHANGED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="1 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>1/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#577</strong>
                                </li>
                                <li class="js-start-date-label">
                                    <small title="Start Date">
                                        <span class="label label-past">Oct 2, 2017</span>
                                    </small>
                                </li>



                                <li>
                                    <small title=" Due Date">
                                        <span class="label label-danger">Oct 15, 2017</span>
                                    </small>
                                </li>

                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="587"
                    id="js-card-587" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-587">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="HTML5 Issues">HTML5 Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 7 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/7</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#587</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="601"
                    id="js-card-601" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-601">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="DEVELOPER">DEVELOPER</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 4 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/4</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#601</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="604"
                    id="js-card-604" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-604">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="1 checklist completed out of 1 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>1/1</span>
                                        </div>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#604</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="605"
                    id="js-card-605" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>BrianGrinstead</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-605">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="UNRESOLVED Issues">UNRESOLVED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 1 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/1</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#605</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Brian Grinstead (BrianGrinstead)">

                                    <i class="avatar avatar-color-194 img-rounded">BG</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-211"></div>
            </div>

        </div>
        <div data-list_id="212" class="js-board-list list">
            <div class="list-header js-list-head cur-grab">
                <div class="clearfix">
                    <a href="#" class="col-xs-8 js-show-edit-list-form">
                        <span>
                            <span class="clearfix row show">
                                <strong class="get-name-212">38.0.1</strong>
                                <span class="pull-right">
                                    <i class="icon-eye-open js-list-subscribed-212 hide"></i>
                                </span>
                            </span>
                        </span>
                    </a>

                    <form role="form" class="form-horizontal js-edit-list hide">
                        <div class="form-group">
                            <label class="col-sm-4 control-label hide" for="inputListName">Name</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="255" autocomplete="off" id="inputListName" name="name" title="Whitespace is not allowed" required=""
                                    pattern=".*\S+.*" value="38.0.1" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only col-sm-4 control-label hide" for="submit2">Submit</label>
                            <div class="col-sm-12">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-edit-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </div>
                    </form>

                    <!--
                            <div class="pull-right col-xs-4 clearfix">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle pull-right js-show-list-actions" id="js-show-list-actions-212" data-toggle="dropdown"
                                        title="List Actions" data-list-id="212">
                                        <i class="icon-cog text-primary"></i>
                                    </a>
                                </div>
                                <div class="dropdown right-mar">
                                    <a href="#" class="dropdown-toggle pull-right js-show-sort-form right-mar" id="js-show-sort-form-212" data-toggle="dropdown"
                                        title="Sort" data-list-id="212">
                                        <i class="icon-sort text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left arrow arrow-left dropdown-menu-top js-sort-list-response right-mar">
                                        <li class="text-center">
                                            <strong>Sort</strong>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a title="Votes" href="#" class="js-sort-by" data-sort-by="card_voter_count">Votes</a>
                                        </li>
                                        <li>
                                            <a title="Attachments" href="#" class="js-sort-by" data-sort-by="attachment_count">Attachments</a>
                                        </li>
                                        <li>
                                            <a title="Comments" href="#" class="js-sort-by" data-sort-by="comment_count">Comments</a>
                                        </li>
                                        <li>
                                            <a title="Checklist pending count" href="#" class="js-sort-by" data-sort-by="checklist_item_count">Checklist pending count</a>
                                        </li>
                                        <li>
                                            <a title="Checklist completed count" href="#" class="js-sort-by" data-sort-by="checklist_item_completed_count">Checklist completed count</a>
                                        </li>
                                        <li>
                                            <a title="Due date" href="#" class="js-sort-by" data-sort-by="due_date">Due date</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            -->

                </div>
            </div>

            <div id="colorPicker">
                <span class="list-card-group-addon input-new-addon-cl panel-body color-popover color-focusout" id="js-list-color-212" data-color-format="hex"></span>
            </div>

            <div class="list-content vertical-scrollbar vertical-scrollbar-box js-board-list-cards" id="js-card-listing-212">
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="606"
                    id="js-card-606" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide"></ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-606">
                        </div>

                        <div class="clearfix">
                            <a href="#" title="FIXED Issues">FIXED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">

                                <li>
                                    <small title="4 checklist completed out of 4 ">
                                        <div class="label label-success">
                                            <span class="icon-list-ul"></span>
                                            <span>4/4</span>
                                        </div>
                                    </small>
                                </li>

                                <li class="pull-right card-id">
                                    <strong>#606</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                            </ul>
                        </div>
                    </div>
                </div>
                <div data-toggle="modal" data-target="#myModal" href="#" class="panel js-board-list-card cur" data-card_id="607"
                    id="js-card-607" style="border-left-width: 8px;">
                    <ul class="hide js-card-labels"></ul>
                    <ul class="js-card-users hide">
                        <li>MikedeBoer</li>
                    </ul>
                    <ul class="js-card-due hide"></ul>
                    <div class="panel-body">

                        <div class="clearfix js-card-label-section-607">



                        </div>

                        <div class="clearfix">

                            <a href="#" title="UNRESOLVED Issues">UNRESOLVED Issues</a>
                        </div>
                        <div class="pull-left navbar-btn">
                            <ul class="list-unstyled list-inline text-muted boardlistblk clearfix">






                                <li>
                                    <small title="0 checklist completed out of 1 ">
                                        <span class="icon-list-ul"></span>
                                        <span>0/1</span>
                                    </small>
                                </li>




                                <li class="pull-right card-id">
                                    <strong>#607</strong>
                                </li>


                            </ul>
                        </div>
                        <div class="clearfix pull-right">
                            <ul class="list-unstyled list-inline text-muted clearfix">

                                <li class="pull-left js-tooltip navbar-btn" data-container="body" data-placement="bottom" title="" data-toggle="tooltip"
                                    data-original-title="Mikede Boer (MikedeBoer)">

                                    <i class="avatar avatar-color-194 img-rounded">MB</i>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-footer clearfix">
                <div class="pull-left">
                    <a href="#" title="Add a card" class="js-show-add-card-form js-bottom text-muted">Add a card</a>
                </div>
                <div class="js-card-add-form-212"></div>
            </div>

        </div>
        <div data-list_id="212" class="list">
            <div class="list-header js-list-head cur-grab">


                <div class="clearfix">


                    <div class="js-list-form">
                        <a href="#" class="js-show-add-list-form toggle-show text-muted" title="Add a list">Add a list</a>
                        <form class="js-add-list hide">
                            <div class="form-group">
                                <label for="
                                    inputListName" class="sr-only">Name</label>
                                <input type="text" id="inputListName" autocomplete="off" name="name" class="form-control" placeholder="Add a list" required=""
                                    maxlength="255" title="Whitespace is not allowed" pattern=".*\S+.*">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="Save" class="btn btn-primary" value="Save">
                                <i class="icon-remove js-hide-add-list-form btn btn-link cur" title="Cancel"></i>
                            </div>
                        </form>
                    </div>

                </div>
            </div>


        </div>

    </div>

</div>