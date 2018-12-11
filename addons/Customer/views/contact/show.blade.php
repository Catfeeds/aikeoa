<div class="panel">

        <div class="table-responsive">
            <table class="table table-form">
                <tr>
                    <td width="15%" align="right">名字</td>
                    <td align="left">
                        {{$row->user->nickname}}
                    </td>
                </tr>

                <tr>
                    <td align="right">所属客户</td>
                    <td align="left">
                        {{get_user($row->customer_id, 'nickname')}}
                    </td>
                </tr>

                <tr>
                    <td align="right">性别</td>
                    <td align="left">
                        {{option('user.gender', $row->user->gender)}}
                    </td>
                </tr>

                <tr>
                    <td align="right">生日</td>
                    <td align="left">
                        {{$row->user->birthday}}
                    </td>
                </tr>

                <tr>
                    <td align="right">职位</td>
                    <td align="left">
                        {{option('contact.post', $row->user->post)}}
                    </td>
                </tr>

                <tr>
                    <td align="right">类型</td>
                    <td align="left">
                        {{option('contact.type', $row->type)}}
                    </td>
                </tr>

                <tr>
                    <td align="right">微信</td>
                    <td align="left">
                        {{$row->weixin}}
                    </td>
                </tr>

                <tr>
                    <td align="right">手机</td>
                    <td align="left">
                        {{$row->user->mobile}}
                    </td>
                </tr>

                <tr>
                    <td align="right">描述</td>
                    <td align="left">
                        {{$row->description}}
                    </td>
                </tr>
            </table>
    </div>
</div>
