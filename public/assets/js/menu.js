var iframeHeight = 0;
var tabActiveId  = 0;

var tabsList    = null;
var tabsLeft    = null;
var tabsContent = null;

function checkMQ() {
	// check if mobile or desktop device
	return window.getComputedStyle(document.querySelector('.main-content'), '::before').getPropertyValue('content').replace(/'/g, "").replace(/"/g, "");
}

function addTab(url, id, name) {

	/*
	$('#tab_iframe_0').attr('src', url);

	// 不刷新页面改变地址
	if(history.replaceState) {
		var i = url.replace(settings.public_url + '/', '');
		history.replaceState(null, '', settings.public_url + '?i=' + i);
	}
	*/
	if($('.side-nav').hasClass('nav-is-visible')) {
		var sidebar = $('.side-nav'),
		scroll = $('.nav-scroll'),
		sidebarTrigger = $('.nav-trigger');
		$([sidebar, sidebarTrigger, scroll]).toggleClass('nav-is-visible');
	}
	  
	// 删除hover样式
	$('.side-nav').find('.hover').removeClass('hover');
	$('.side-nav').find('.selected').removeClass('selected');

	tabActiveId = id;
	tabsList.find("[role='presentation']").removeClass('active');
    tabsContent.find("[role='tabpanel']").removeClass('active');

	if($('#tab_' + id).length) {
		// 标签存在刷新src
		$('#tab_iframe_'+id).attr('src', url);

	} else {
		tabsList.append('<li role="presentation" id="tab_'+id+'"><a href="#tab_'+id+'" aria-controls="'+id+'" data-toggle="tab" role="tab"><span>'+name+'</span></a><i class="tab-close fa fa-remove"></i></li>');
		tabsContent.append('<div role="tabpanel" class="tab-pane active" id="tab_pane_'+id+'"><iframe id="tab_iframe_'+id+'" name="tab_iframe_'+id+'" src="'+ url +'" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe></div>');
	}

	// 激活TAB
    $("#tab_" + id).addClass('active');
	$("#tab_pane_" + id).addClass("active");
}

var supportsOrientationChange = "onorientationchange" in window,
orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";
$(window).on(orientationEvent, function() {
	setIframeHeight();
});

function setIframeHeight() {
	
	var h = $(window).height() - 50;
	$('.nav-scroll').height(h);
	$('#tabs-content').height(h);

	iframeHeight = h;

	if($('#tab_iframe_'+tabActiveId)[0].contentWindow.iframeResize) {
		$('#tab_iframe_'+tabActiveId)[0].contentWindow.iframeResize();
	}
}

$(function() {

	setIframeHeight();

	tabsList    = $('#tabs-list');
	tabsLeft    = $('#tabs-left');
	tabsContent = $('#tabs-content');

	$('[data-toggle="addtab"]').on('click', function(event) {

		event.preventDefault();

		// 触屏设备不触发事件
		var mq = checkMQ();
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
		addTab(data.url, data.id, data.name);
	});

	tabsList.on('click', '.tab-close', function() {

		var id = $(this).prev('a').attr('aria-controls');

		// 如果关闭的是当前激活的TAB，激活他的前一个TAB
        if (tabsList.find('li.active').attr('id') == 'tab_' + id) {
            if ($('#tab_' + id).next().length > 0) {
                $('#tab_' + id).next().trigger('click');
			} else if($('#tab_' + id).prev().length > 0) {
				$('#tab_' + id).prev().trigger('click');
			}
		}
		
        // 关闭TAB
		$('#tab_' + id).remove();
		// 关闭If
        $('#tab_pane_' + id).remove();
		
        return false;
	});
	
    tabsList.on('dblclick', 'li[role=presentation]', function() {
        $(this).find('.tab-close').trigger('click');
	});
	
	tabsList.on('click', 'li[role=presentation]', function() {
		var id = $(this).find('a').attr('aria-controls');
		tabsList.find("[role='presentation']").removeClass('active');
		tabsContent.find("[role='tabpanel']").removeClass('active');
		$("#tab_" + id).addClass("active");
		$("#tab_pane_" + id).addClass("active");
    });

	// cache DOM elements
	var mainContent = $('.main-content'),
		header = $('.main-header'),
		sidebar = $('.side-nav'),
		scroll = $('.nav-scroll'),
		sidebarTrigger = $('.nav-trigger');

	// mobile only - open sidebar when user clicks the hamburger menu
	sidebarTrigger.on('click', function(event) {
		event.preventDefault();
		$([sidebar, sidebarTrigger, scroll]).toggleClass('nav-is-visible');
	});
	
	// side folded
    $(document).on('click', "[data-toggle=side-folded]", function(event) {
		event.preventDefault();
		$(this).toggleClass('active');
		$('body').toggleClass('side-folded');
    });

	// click on item and show submenu
	$('.has-children > a').on('click', function(event) {

		var mq = checkMQ(),
			selectedItem = $(this);

		if( mq == 'mobile' || mq == 'tablet' ) {

			event.preventDefault();

			if(selectedItem.parent('li').hasClass('selected')) {
				selectedItem.parent('li').removeClass('selected');
			} else {
				selectedItem.parent().parent().find('>.has-children.selected').removeClass('selected');
				selectedItem.parent('li').addClass('selected');
			}
		}
	});

	/*
	$(document).on('click', function(event) {
		if(!$(event.target).is('.has-children a')) {
			sidebar.find('.has-children.selected').removeClass('selected');
		}
	});
	*/

	//on desktop - differentiate between a user trying to hover over a dropdown item vs trying to navigate into a submenu's contents
	/*
	sidebar.children('ul').menuAim({
        activate: function(row) {
        	//$(row).addClass('hover');
        },
        deactivate: function(row) {
        	//$(row).removeClass('hover');
        },
        exitMenu: function() {
        	sidebar.find('.hover').removeClass('hover');
        	return true;
        },
        submenuSelector: ".has-children",
    });
	*/

	$('.has-children').on('mouseover mouseout', function(event) {

		var mq = checkMQ();

		if(mq == 'desktop') {

			// 鼠标悬浮
			if(event.type == 'mouseover') {

				var wh = $(window).height();
				
				$(this).addClass('hover');
				var list = $(this).find('ul:visible').not('.fix');
				list.each(function(i) {
					var uh = $(this).height();
					var p = $(this).offset();
					var c = wh - p.top - uh;

					/* 二级菜单和三级菜单高出window */
					/*
					var wwh = wh - 50;
					if(wwh < uh) {
						$(this).addClass('ccc');
						c = wh - p.top - uh / 2;
					}
					*/
					
					if(c < 0) {
						$(this).css({top:c});
						$(this).addClass('fix');
					}
				});

			// 鼠标离开
			} else if(event.type == 'mouseout') {
				$(this).removeClass('hover');
			}
		}
	});
});