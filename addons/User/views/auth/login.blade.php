<style>
    body {
        /* color: #badbe6; */
        text-align: center;
        background: -webkit-linear-gradient(right, #198fb4, #0b6fab);
        background: -o-linear-gradient(right, #198fb4, #0b6fab);
        background: linear-gradient(to left, #198fb4, #0b6fab);
        background-color: #3586b7;
    }
    .text-muted {
        color: #badbe6;
    }
    .text-muted a {
        color: #fff;
    }
    .bg-img {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        opacity: 1;
        z-index: -1;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .login-box {
        margin: 0 auto;
        text-align: left;
    }
    .login-box .h5 {
        text-align: center;
        line-height: 20px;
    }

    .toast {
        text-align: left;
    }

    .login-box {
        padding-top: 80px;
    }
    @media (max-width: 767px) {
        .login-box {
            padding-top: 60px;
        }
    }
</style>

<div class="login-box">

    <div class="wrapper text-center">
        <h2 class="text-white">{{$setting['title']}}</h2>
        <small class="text-muted">Different Office</small>
    </div>

    <div class="container w-xxl w-auto-xs">

        @if(Session::has('error'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            {{Session::pull('error')}}
        </div>
        @endif

        <form class="form-horizontal" url="{{url('login')}}" method="post" id="myform" name="myform">

            <div class="panel">
                <div class="panel-body" style="padding:20px 30px 0 30px;">
                    <div class="form-group">
                        <input type="text" placeholder="账号" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="密码" class="form-control" name="password" required>
                    </div>

                    @if($setting['login_captcha'] <= $log->error_count)
                    <div class="form-group">
                        <input type="text" name="captcha" class="form-control" id="input-code" required placeholder="验证码">
                        <span class="help-block">
                            <img src="{{url('user/auth/captcha')}}" id="captcha" title="点击刷新" style="vertical-align:middle;">
                            <!-- <a href="javascript:captcha('#captcha');"> 刷新 </a> -->
                        </span>
                    </div>
                    @endif

                    <div class="form-group">
                        <div class="checkbox m-b-md m-t-none">
                            <label class="i-checks i-checks-sm">
                                <input type="checkbox" name="remember">
                                <i></i> 下次自动登录
                            </label>
                        </div>
                        <div class="line line-dashed"></div>
                        <button type="submit" class="btn btn-lg btn-success btn-block"> 登录 </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--
    <div class="line line-dashed"></div>
    <div class="text-center text-muted">
        <a class="text-base" href="{{url('qrcode')}}">
            <i class="fa fa-qrcode"></i> 扫码登录</a>
    </div>
    -->
    <div class="line line-dashed"></div>

    <div class="text-center">
        <small class="text-muted">© {{date('Y')}} {{$version}}</small>
    </div>
</div>

<script>
// ajax 登录
$('#myform').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (res) {
        if (res.status) {
            $.toastr('success', res.data);
            app.redirect('/');
        } else {
            $.toastr('error', res.data);
        }
    }, 'json');
    return false;
});

// 刷新验证码
$(document).on('click', '#captcha', function () {
    $(this).attr('src', settings.public_url + '/user/auth/captcha?_=' + Math.random());
});
</script>