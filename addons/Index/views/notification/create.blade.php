<table class="table table-form m-b-none">
    <tbody>
        <tr>
            <td width="10%" align="right">姓名</td>
            <td width="40%">{{$row->nickname}}</td>
            <td width="10%" align="right">性别</td>
            <td width="40%">{{option('user.gender', $row['gender'])}}</td>
        </tr>
        <tr>
            <td align="right">部门</td>
            <td>{{$row->department->title}}</td>
            <td align="right">岗位</td>
            <td>{{$row->role->title}}</td>
        </tr>
        <tr>
            <td align="right">手机</td>
            <td>{{$row->mobile}}</td>
            <td align="right">邮箱</td>
            <td>{{$row->email}}</td>
        </tr>
        <tr>
            <th colspan="4" align="left">站内私信</th>
        </tr>
        <tr>
            <td align="right">内容</td>
            <td colspan="3">
                <textarea rows="2" class="form-control" id="content" name="content"></textarea>
            </td>
        </tr>
    </tbody>
</table>