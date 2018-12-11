<div class="panel">
    <form class="form-horizontal form-controller" method="post" action="{{url()}}" id="myform" name="myform">

        <div class="form-group no-border">
            <label class="col-sm-2 control-label">人事资料ID</label>
            <div class="col-sm-10 control-text">
                <input type="text" class="form-control input-sm" name="hr_id" value="{{$row->hr_id}}" readonly="readonly">
            </div>
        </div>

        <!--
        <div class="form-group">
            <label class="col-sm-2 control-label">系统部门</label>
            <div class="col-sm-10">
                {{Dialog::user('department', 'department_id', $row->hr->user->department_id, 0, 0)}}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">系统角色</label>
            <div class="col-sm-10">
                {{Dialog::user('role', 'role_id', $row->hr->user->role_id, 0, 0)}}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">直属领导</label>
            <div class="col-sm-10">
                {{Dialog::user('user','leader_id', $row->hr->user->leader_id, 0, 0)}}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">系统职位</label>
            <div class="col-sm-10">
                <select class="form-control input-sm" id='position_id' name='position_id'>
                    <option value=''> - </option>
                    @foreach(option('user.position') as $position)
                        <option value='{{$position['id']}}' @if($row->hr->user->post == $position['id']) selected @endif>{{$position['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        -->
        
        <div class="form-group">
            <label class="col-sm-2 control-label">工作职级</label>
            <div class="col-sm-10 control-text">
                <select class="form-control input-sm" id='rank_id' name='rank_id'>
                    <option value=''> - </option>
                    @foreach(option('hr.rank') as $rank)
                        <option value='{{$rank['id']}}' @if($row->rank_id == $rank['id']) selected @endif>{{$rank['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">工作类型</label>
            <div class="col-sm-10 control-text">
                <select class="form-control input-sm" id='type' name='type'>
                    <option value=''> - </option>
                    @foreach(option('hr.job.type') as $type)
                        <option value='{{$type['id']}}' @if($row->type == $type['id']) selected @endif>{{$type['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">岗位描述</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="name" name="name" value="{{$row->name}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">流程编号</label>
            <div class="col-sm-10 control-text">
                <input class="form-control input-sm" type="text" id="process_id" name="process_id" value="{{$row->process_id}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">工作日期</label>
            <div class="col-sm-10 control-text">
                <input data-toggle="date" class="form-control input-sm" type="text" id="start_date" name="start_date" value="{{$row->start_date}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">详细描述</label>
            <div class="col-sm-10 control-text">
                <textarea class="form-control input-sm" name="description" id="description">{{$row->description}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 control-btn"></div>
            <div class="col-sm-10 control-label">
                <input type="hidden" name="id" value="{{$row->id}}">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            </div>
        </div>
    </form>
</div>
