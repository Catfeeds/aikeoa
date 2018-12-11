<div class="panel">

    <div class="table-responsive">
        <table class="table table-form">
        <tr>
            <th align="left" colspan="4">基本资料</th>
        </tr>
        <tr>
            <td width="10%" align="right">用户名:</td>
            <td width="40%">
                {{$res['username']}}
            </td>
            <td width="10%" align="right">姓名:</td>
            <td width="40%">{{$res['nickname']}}</td>
        </tr>

        <tr>
            <td align="right">手机:</td>
            <td>
               {{$res['mobile']}}
            </td>
            <td align="right">邮箱:</td>
            <td>{{$res['email']}}</td>
        </tr>

        <tr>
            <td align="right">工作电话:</td>
            <td>{{$res['tel']}}</td>

            <td align="right">生日:</td>
            <td>
                {{$res['birthday']}}
            </td>
        </tr>

        <tr>
            <td align="right">部门:</td>
            <td align="left">
                {{$res->department->title}}
            </td>
            <td align="right">角色:</td>
            <td align="left">
                {{$res->role->title}}
            </td>
        </tr>

        <tr>
            <td align="right">直属领导:</td>
            <td align="left">
                {{get_user($res['leader_id'], 'nickname')}}
            </td>

            <td align="right">职位:</td>
            <td align="left">
                {{$res->position->title}}
            </td>
        </tr>

        <tr>
            <td align="right">性别:</td>
            <td>
                {{option('user.gender', $res['gender'])}}
            </td>
            <td align="right">账户密钥:</td>
            <td align="left">
                <strong>{{$secretKey}}
                <a href="javascript:;" onclick='$.messager.alert("二次验证二维码","<div align=\"center\"><img src=\"{{$secretImg}}\"><div><code>{{$secretKey}}</code></div></div>");'>
                    <i class="icon icon-qrcode"></i>
                </a>
                </strong>
            </td>
        </tr>

        <tr>
            <td align="right"></td>
            <td>
                <label class="checkbox-inline i-checks i-checks-sm">
                    <input type="checkbox" disabled @if($res['id'] > 0 && $res['status'] == 0) checked @endif><i></i> 停用账户
                </label>

                <label class="checkbox-inline i-checks i-checks-sm">
                    <input type="checkbox" disabled @if($res['auth_totp'] == 1) checked @endif><i></i> 安全登录
                </label>
            </td>
            <td align="right">绑定IP:</td>
            <td align="left">{{$res['auth_ip']}}</td>
        </tr>

        <tr>
            <td align="left" colspan="4">
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            </td>
        </tr>

        </table>
    </div>
</div>
