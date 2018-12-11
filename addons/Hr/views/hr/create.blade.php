<div class="panel">

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{url()}}" id="myform" name="myform">
<div class="table-responsive">
    
<table class="table table-form table-bordered">
    <tr>
        <th colspan="5" align="left">基础资料</th>
    </tr>
    <tr>
        <td width="10%" align="right">姓名</td>
        <td width="30%" align="left">
            <input class="form-control input-inline input-sm" type="text" id="name" name="hr[name]" value="{{$row['name']}}">
        </td>
        <td width="10%" align="right">系统用户</td>
        <td width="30%" align="left">
            {{Dialog::user('user','hr[user_id]', $row['user_id'], 0, 0)}}
        </td>
        <td width="20%" align="left" rowspan="4">
            @if($row['image'])
            <img id="avatar" width="120" height="120" src="{{$upload_url}}/{{$row['image']}}" class="thumbnail m-b-xs">
            @else
            <img id="avatar" width="120" height="120" src="{{$asset_url}}/images/a1.jpg" class="thumbnail m-b-xs">
            @endif
            <input type="file" id="image" name="hr[image]" value="{{$row['image']}}"> <span class="help-inline">(建议尺寸：120x120)</span>
        </td>
    </tr>

    <tr>
        <td align="right">性别</td>
        <td align="left">
            <select class="form-control input-inline input-sm" name="hr[gender]" id="gender">
                @foreach(option('user.gender') as $gender)
                    <option value='{{$gender['id']}}' @if($row['gender'] == $gender['id']) selected @endif>{{$gender['name']}}</option>
                @endforeach
            </select>
        </td>
        <td align="right">生日</td>
        <td align="left">
             <input data-toggle="date" data-format="yyyy-MM-dd" class="form-control input-inline input-sm" type="text" id="birthday" name="hr[birthday]" placeholder="国历" value="{{$row['birthday']}}">
        </td>
    </tr>

    <tr>
        <td align="right">身份证</td>
        <td align="left">
            <input class="form-control input-sm" type="text" id="idcard" name="hr[idcard]" value="{{$row['idcard']}}">
        </td>
        <td align="right">学历</td>
        <td align="left">
            <input class="form-control input-inline input-sm" type="text" id="degre" name="hr[degre]" value="{{$row->degre}}">
        </td>
    </tr>
    <tr>
        <td align="right">联系方式</td>
        <td align="left">
            <input class="form-control input-sm" type="text" id="home_contact" name="hr[home_contact]" value="{{$row->home_contact}}">
        </td>
        <td align="right">家庭住址</td>
        <td align="left">
             <input class="form-control input-sm" type="text" id="home_address" name="hr[home_address]" value="{{$row->home_address}}">
        </td>
    </tr>
    </table>

    <table class="table table-form table-bordered">
    <tr>
        <th colspan="5" align="left">个人资料</th>
    </tr>
        <tr>
        <td align="right">职级</td>
        <td align="left">
            <select class="form-control input-sm input-inline" id='rank_id' name='hr[rank_id]'>
                <option value=''> - </option>
                @foreach(option('hr.rank') as $rank)
                        <option value='{{$rank['id']}}' @if($row['rank_id'] == $rank['id']) selected @endif>{{$rank['name']}}</option>
                @endforeach
            </select>
        </td>
        <td align="right">工作单元</td>
        <td align="left">
            <select class="form-control input-sm input-inline" id='unit' name='hr[unit]'>
                <option value=''> - </option>
                @foreach(option('hr.unit') as $unit)
                    <option value='{{$unit['id']}}' @if($row->unit == $unit['id']) selected @endif>{{$unit['name']}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td align="right" width="10%">岗位描述</td>
        <td align="left" width="30%">
            <input class="form-control input-inline input-sm" type="text" id="position" name="hr[position]" value="{{$row['position']}}">
        </td>
        <td align="right" width="10%">入职日期</td>
        <td align="left" width="50%">
            <input data-toggle="date" class="form-control input-inline input-sm" type="text" id="test_date" name="hr[test_date]" value="{{$row['test_date']}}">
        </td>
    </tr>
    <tr>
        <td align="right">转正日期</td>
        <td align="left">
            <input data-toggle="date" class="form-control input-inline input-sm" type="text" id="job_date" name="hr[job_date]" value="{{$row->job_date}}">
        </td>
        <td align="right">员工状态</td>
        <td align="left">
            <select class="form-control input-inline input-sm" id='status' name='hr[status]'>
                @foreach($status as $k => $v)
                    <option value='{{$k}}' @if($row['status'] == $k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td align="right">保险状态</td>
        <td align="left">
             <select class="form-control input-inline input-sm" name="hr[insurance]" id="insurance">
                <option value=''> - </option>
                @foreach(option('hr.insurance') as $insurance)
                    <option value='{{$insurance['id']}}' @if($row['insurance'] == $insurance['id']) selected @endif>{{$insurance['name']}}</option>
                @endforeach
            </select>
        </td>
        <td align="right">详细描述</td>
        <td align="left">
             <textarea class="form-control input-sm" name="hr[description]" id="description">{{$row['description']}}</textarea>
        </td>
    </tr>

    <tr>
        <td align="left" colspan="4">
            <input type="hidden" name="hr[id]" value="{{$row['id']}}">
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
        </td>
    </tr>     
</table>

</div>

</form>

</div>