<template>
    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user">
        <!--
        <li class="dropdown">
            <a data-toggle="addtab" data-url="index/index/dashboard" data-id="dashboard" data-name="个人空间" class="dropdown-toggle hidden-xs">
                <i class="fa fa-bar-chart-o"></i>
                <span>个人空间</span>
            </a>
        </li>
        -->
        <li class="dropdown">
            <a href="javascript:;" @click="chatToggle()" title="即时消息" class="dropdown-toggle hidden-xs">
                <i class="fa fa-comments pulse-box">
                    <span class='pulse green'></span>
                </i>
                <span class="visible-xs-inline">即时消息</span>
            </a>
        </li>

        <li class="dropdown hidden-xs">
            <!--
            <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-bell-o notify-box">
                    <span class="pulse" v-if="count.total > 0"></span>
                </i>
                <span class="visible-xs-inline">通知</span>
            </a>
            -->
            <a href="#" data-toggle="dropdown" title="通知" class="dropdown-toggle">
                <i class="fa fa-bell-o pulse-box">
                    <span :class="[this.countTotal > 0 ? 'pulse' : 'hidden']"></span>
                </i>
                <span class="visible-xs-inline">通知</span>
            </a>

            <div class="dropdown-menu w-xl">
                <div class="panel bg-white">
                    <div class="list-group no-radius">
                        <a href="#" :class="[this.countArticle > 0 ? 'list-group-item' : 'hidden']">
                            <span class="pull-left thumb-sm">
                                <i class="fa fa-bullhorn fa-2x text-danger"></i>
                            </span>
                            <span class="block m-b-none">
                                <span><span class="text-danger pull-right-xs">0</span> 条未读公告</span>
                                <br />
                                <small class="text-muted text-xs">点击阅读</small>
                            </span>
                        </a>
                        <a href="#" :class="[this.countMail > 0 ? 'list-group-item' : 'hidden']">
                            <span class="pull-left thumb-sm">
                                <i class="fa fa-envelope-o fa-2x text-success"></i>
                            </span>
                            <span class="block m-b-none">
                                <span><span class="text-danger pull-right-xs">2</span> 条未读邮件</span>
                                <br />
                                <small class="text-muted text-xs">点击阅读</small>
                            </span>
                        </a>
                        <a href="#" :class="[this.countNotification > 0 ? 'list-group-item' : 'hidden']" data-toggle="addtab" :data-url="url('user/message/index')" data-id="00" data-name="通知提醒">
                            <span class="pull-left thumb-sm">
                                <i class="fa fa-bell-o fa-2x text-info"></i>
                            </span>
                            <span class="block m-b-none">
                                <span><span class="text-danger pull-right-xs">{{this.countNotification}}</span> 条未读通知</span>
                                <br />
                                <span class="text-muted text-xs">点击阅读</span>
                            </span>
                        </a>
                    </div>
                    <div class="panel-footer text-sm">
                        <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                        <a href="#">提醒设置</a>
                    </div>
                </div>
            </div>
        </li>
        <li class="dropdown">

            <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle clear hidden-xs">
                <i class="icon icon-cog"></i>
            </a>

            <!-- animated fadeInUp -->
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript:;" data-toggle="addtab" :data-url="url('user/user/profile')" data-id="02" data-name="个人资料">个人资料</a>
                </li>
                <li>
                    <a>菜单设置</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a :href="url('user/auth/logout')">注销</a>
                </li>
            </ul>
        </li>
    </ul>
</template>

<script>
export default {
    data: function() {
        return {
            countNotification: 0,
            countTotal: 0,
            countArticle: 0,
            countMail: 0,
        };
    },
    mounted() {
        console.log('Notification 模块初始化完成');
        this.tick()
        this.timer = setInterval(() => this.tick(), 1000 * 60);
    },
    methods: {
        chatToggle() {
            $('.layui-layim').toggle();
        },
        tick() {
            let me = this;
            
            $.get(app.url('user/message/count'), function (count) {
                if (me.countNotification == count) {
                    return;
                }
                me.countTotal = count;
                me.countNotification = count;
            }, 'json');

            // 查询待办流程
            $.post(app.url('workflow/widget/index'), function (res) {
                let badge = $('#badge_workflow_workflow_index');
                let menu_id = badge.data('menu_id');
                if (res.total) {
                    $('#badge_menu_' + menu_id).show();
                    badge.show();
                    badge.text(res.total);
                } else {
                    badge.hide();
                }
            });
        }
    }
}
</script>
