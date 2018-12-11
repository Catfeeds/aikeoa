<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{$setting['title']}} - Powered By {{$setting['powered']}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="{{$asset_url}}/dist/index.min.css" type="text/css" />
    <script src="{{$public_url}}/common" type="text/javascript"></script>
    <script src="{{$asset_url}}/dist/index.min.js" type="text/javascript"></script>
    <!--[if lt IE 9]>
    <script src="{{$asset_url}}/libs/html5shiv.js"></script>
    <script src="{{$asset_url}}/libs/respond.min.js"></script>
    <script src="{{$asset_url}}/libs/excanvas.js"></script>
    <![endif]-->

</head>

<body class="theme-{{auth()->user()->theme ?: 'blue'}}">

    <header class="header navbar">

        <div class="navbar-header">

            <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
                <i class="icon icon-cog"></i>
            </a>

            <a href="{{url('/')}}" class="navbar-brand">
                <!--
                <img src="{{$asset_url}}/images/logo.png" class="m-r-sm">
                -->
                <i class="fa text-lg fa-buysellads"></i> {{$setting['title']}}
            </a>

            <a class="btn btn-link visible-xs nav-trigger" data-target="#nav">
                <span></span>
            </a>

        </div>
        
        <ul class="nav navbar-nav tabs-list hidden-xs" id="tabs-list">
            <li role='presentation'>
                <a href="#tab_0" aria-controls="0" data-toggle="tab" role="tab">
                    <i class="fa fa-square-o"></i>
                    <span>个人空间</span>
                </a>
            </li>
            <!--
            <li>
                <a href="#tab_003"><span>个人空间</span></a>
            </li>
            -->
        </ul>
        
        <div id="notification"><notification/></div>
    </header>

    <div class="nav-scroll">

        <div class="side-nav" id="tabs-left">

            @if(Auth::user()->avatar_show == 1)
            <div class="side-nav-avatar">

                <a href="javascript:;" data-toggle="side-folded" class="folded">
                    <i class="fa fa-angle-left text"></i>
                    <i class="fa fa-angle-right text-active"></i>
                </a>

                <span class="thumb-md avatar">
                    <a href="javascript:;" data-toggle="addtab" data-url="user/user/profile" data-id="02" data-name="个人资料">
                        <img src="{{avatar()}}" class="img-circle">
                        <i class="on md b-white bottom"></i>
                    </a>
                </span>
                <span class="text-avatar text-muted text-xs block m-t-xs">
                    <?php echo Auth::user()->nickname; ?>
                </span>
            </div>
            @endif

            <ul>
                <?php $i = 0; ?>
                @foreach($menus['children'] as $menu) 
                @if($menu['selected'])
                <li class="has-children">
                    <a href="javascript:;" class="a{{$i}}" title="{{$menu['name']}}">

                        <span class="pulse-box">
                            <span id="badge_menu_{{$menu['id']}}" class="pulse" style="display:none;"></span>
                        </span>

                        <span class="pull-right">
                            <i class="fa fa-fw fa-angle-right text"></i>
                            <i class="fa fa-fw fa-angle-down text-active"></i>
                        </span>

                        <i class="fa {{$menu['icon']}}"></i>

                        <span class="title">{{$menu['name']}}</span>
                    </a>
                    <ul>
                        @foreach($menu['children'] as $groupId => $group)
                        @if($group['selected'])
                        <li class="has-children">
                            <a href="javascript:;" data-toggle="addtab" data-url="{{$group['url']}}" data-id="{{$group['id']}}" data-name="{{$group['name']}}">
                                @if($group['children'])
                                <span class="pull-right">
                                    <i class="fa fa-fw fa-angle-right text"></i>
                                    <i class="fa fa-fw fa-angle-down text-active"></i>
                                </span>
                                @endif

                                @if($group['url'])
                                    <b data-menu_id="{{$menu['id']}}" id="badge_{{str_replace('/', '_', $group['url'])}}" class="badge bg-danger pull-right" style="display:none;"></b>
                                @endif

                                {{$group['name']}}
                            </a>

                            @if($group['children'])
                            <ul>
                                @foreach($group['children'] as $action) 
                                @if($action['selected'])
                                <li class="@if($action['active']) active @endif">
                                    <a href="javascript:;" data-toggle="addtab" data-url="{{$action['url']}}" data-id="{{$action['id']}}" data-name="{{$action['name']}}">
                                        {{$action['name']}}
                                    </a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                            @endif

                        </li>
                        @endif 
                        @endforeach
                    </ul>
                </li>
                <?php
                if($i == 8) {
                    $i = 0;
                } else {
                    $i++;
                }
                ?> 
                @endif 
                @endforeach
            </ul>
            <ul>
                <li class="label">个人</li>
                <li>
                    <a href="javascript:;" data-toggle="addtab" data-url="{{url('user/message/index')}}" data-id="00" data-name="通知提醒"
                        title="通知提醒">
                        <i class="fa fa-bell"></i>
                        <span class="title">通知提醒</span>
                        <!--
                        <span class="count badge pull-right bg-danger">3</span>
                        -->
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content" id="tabs-content">
        <div role="tabpanel" class="tab-pane active" id="tab_pane_0">
            <iframe src="{{$url}}" id="tab_iframe_0" frameBorder=0 scrolling=auto width="100%" height="100%"></iframe>
        </div>
    </div>
    <script src="{{$asset_url}}/dist/bundle.min.js"></script>

    @if($chat == true)
    <link href="{{$asset_url}}/vendor/layim/css/layui.css" rel="stylesheet">
    <script src="{{$asset_url}}/vendor/layim/layui.js"></script>
    <script src="{{$asset_url}}/libs/websocket.js"></script>
    <style>
        .layui-layim-close { display: none; }
    </style>
    <script>
    layui.use('layim', function(layim) {
        // 基础配置
        layim.config({
            // 初始化接口
            init: {
                url: '{{url("index/im/list")}}' // '{{$asset_url}}/layim/json/getList.json'
                ,data: {}
            }
            // 查看群员接口
            ,members: {
                url: '{{$asset_url}}/layim/json/getMembers.json'
                ,data: {}
            }
            ,uploadImage: {
                url: '' //（返回的数据格式见下文）
                ,type: '' //默认post
            }
            ,uploadFile: {
                url: '' //（返回的数据格式见下文）
                ,type: '' //默认post
            }
            ,isAudio: false //开启聊天工具栏音频
            ,isVideo: false //开启聊天工具栏视频
            
            //扩展工具栏
            ,brief: false //是否简约模式（若开启则不显示主面板）
            //,title: '即时消息' //自定义主面板最小化时的标题
            //,right: '100px' //主面板相对浏览器右侧距离
            //,minRight: '90px' //聊天面板最小化时相对浏览器右侧距离
            //,initSkin: '3.jpg' //1-5 设置初始背景
            //,skin: ['aaa.jpg'] //新增皮肤
            //,isfriend: false //是否开启好友
            //,isgroup: false //是否开启群组
            ,min: true //是否始终最小化主面板，默认false
            ,notice: true //是否开启桌面消息提醒，默认false
            //,voice: false //声音提醒，默认开启，声音文件为：default.mp3
            
            //,msgbox: '/layim/demo/msgbox.html' //消息盒子页面地址，若不开启，剔除该项即可
            //,find: '/layim/demo/find.html' //发现页面地址，若不开启，剔除该项即可
            ,chatLog: '/layim/demo/chatlog.html' //聊天记录页面地址，若不开启，剔除该项即可
            
        });

        var ws = new AikeWebSocket("{{$ws}}");
        ws.reconnectInterval = 5000;

        ws.onopen = function(e) {
            // $.toastr('info', '消息服务器连接成功');
            ws.send('{"action":"login","data":{"token":"{{create_token(auth()->id(), 7)}}"}}');
        }

        ws.onclose = function (e) {
            console.log(e);
        };

        ws.onmessage = function (e) {

            var res = JSON.parse(e.data);

            console.log(res.data);

            switch(res.action) {
                // 服务端ping
                case 'ping':
                    ws.send('{"type":"pong"}');
                    break;
                // 登录
                case 'login':
                    layim.setFriendStatus(res.data.user_id, 'online');
                    break;
                // 注销
                case 'logout':
                    layim.setFriendStatus(res.data.user_id, 'offline');
                    break;
                // 收到消息
                case 'layimMessage':
                    layim.getMessage(res.data);
                    break;
            }
        };

        // 监听在线状态的切换事件
        layim.on('online', function(status) {
            layer.msg(status);
        });
        
        // 监听签名修改
        layim.on('sign', function(value) {
            layer.msg(value);
        });
        
        // 监听layim建立就绪
        layim.on('ready', function(res) {
            // 模拟消息盒子有新消息，实际使用时，一般是动态获得
            // layim.msgbox(5); 
        });

        // 监听发送消息
        layim.on('sendMessage', function(res) {
            var to = res.to;
            if(to.type === 'friend') {
                var send = JSON.stringify({
                    action: 'layimMessage',
                    data: res
                });
                ws.send(send);
            }
        });

        // 监听查看群员
        layim.on('members', function(data) {
            console.log(data);
        });
        
        // 监听聊天窗口的切换
        layim.on('chatChange', function(res) {
            var type = res.data.type;
                console.log(res.data.id)
            if(type === 'friend') {
                //模拟标注好友状态
                //layim.setChatStatus('<span style="color:#FF5722;">在线</span>');
            } else if(type === 'group') {
                //模拟系统消息
                layim.getMessage({
                    system: true
                    ,id: res.data.id
                    ,type: "group"
                    ,content: '模拟群员'+(Math.random()*100|0) + '加入群聊'
                });
            }
        });

        $('.layui-layim').hide();

    });

    </script>
    @endif

</body>

</html>