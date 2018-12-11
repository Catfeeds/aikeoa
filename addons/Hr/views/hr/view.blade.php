<div class="panel">

            <div class="panel-heading text-base">
                @if(authorise('create'))
                    <span class="pull-right">
                        <a href="{{url('create',['id'=>$row->id])}}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                        <button onclick="history.back();" class="btn btn-xs btn-default">返回</button>
                    </span>
                @endif
                基本资料
            </div>

            <div class="table-responsive">

                <table class="table table-bordered">
                    <tr>
                        <td width="10%" align="right">姓名</td>
                        <td width="30%" align="left">
                            {{$row['name']}}
                        </td>
                        <td width="10%" align="right">系统用户</td>
                        <td width="30%" align="left">
                            {{$row->user->nickname}}
                        </td>
                        <td width="20%" align="left" rowspan="4">
                            @if($row['image'])
                            <img id="avatar" width="120" height="120" src="{{$upload_url}}/{{$row['image']}}" class="thumbnail m-b-xs">
                            @else
                            <img id="avatar" width="120" height="120" src="{{$asset_url}}/images/a1.jpg" class="thumbnail m-b-xs">
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td align="right">性别</td>
                        <td align="left">
                            {{option('user.gender', $row->gender)}}
                        </td>
                        <td align="right">生日</td>
                        <td align="left">
                            {{$row['birthday']}}
                        </td>
                    </tr>

                    <tr>
                        <td align="right">身份证</td>
                        <td align="left">
                            {{$row['idcard']}}
                        </td>
                        <td align="right">学历</td>
                        <td align="left">
                            {{$row->degre}}
                        </td>
                    </tr>
                    <tr>
                        <td align="right">联系方式</td>
                        <td align="left">
                            {{$row->home_contact}}
                        </td>
                        <td align="right">家庭住址</td>
                        <td align="left">
                            {{$row->home_address}}
                        </td>
                    </tr>
                    </table>

                    <table class="table table-bordered">
                    <tr>
                        <td colspan="5" align="left">个人资料</td>
                    </tr>
                        <tr>
                        <td align="right">职级</td>
                        <td align="left">
                            {{option('hr.rank', $row['rank_id'])}}
                        </td>
                        <td align="right">工作单元</td>
                        <td align="left">
                            {{option('hr.unit', $row->unit)}}
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="10%">岗位描述</td>
                        <td align="left" width="30%">
                            {{$row['position']}}
                        </td>
                        <td align="right" width="10%">入职日期</td>
                        <td align="left" width="50%">
                            {{$row['test_date']}}
                        </td>
                    </tr>
                    <tr>
                        <td align="right">转正日期</td>
                        <td align="left">
                            {{$row->job_date}}
                        </td>
                        <td align="right">员工状态</td>
                        <td align="left">
                            @foreach($status as $k => $v)
                                @if($row['status'] == $k) {{$v}} @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td align="right">保险状态</td>
                        <td align="left">
                            {{option('hr.insurance', $row['insurance'])}}
                        </td>
                        <td align="right">详细描述</td>
                        <td align="left">
                            {{$row['description']}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
    <div class="panel">

            <div class="panel-heading b-b b-light text-base">
                <span class="pull-right">
                    @if(authorise('job.create'))
                        <a href="{{url('hr/job/create',['hr_id'=>$row->id,'refer'=>$refer])}}" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> 新建</a>
                    @endif
                </span>
                工作记录
            </div>
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th align="center" width="160">日期</th>
                        <!--
                        <th align="center" width="140">部门</th>
                        <th align="center" width="140">角色</th>
                        <th align="center" width="140">职位</th>
                        -->
                        <th align="center" width="120">职级</th>
                        <th align="center" width="160">岗位描述</th>
                        <th align="left" width="120">流程编号</th>
                        <th align="left">备注</th>
                    </tr>
                </thead>
                @if($jobs)
                    @foreach($jobs as $job)
                    <tr>
                        <td align="center">{{$job->start_date}}</td>
                        <!--
                        <td align="center">
                            {{$job->department->title}}
                        </td>
                        <td align="center">
                            {{$job->role->title}}
                        </td>
                        <td align="center">
                            {{option('user.position', $job->position_id)}}
                        </td>
                        -->
                        <td align="center">{{option('hr.rank', $job->rank_id)}}</td>
                        <td align="center">
                            {{$job->name}}
                        </td>
                        <td align="left">{{$job->process_id}}</td>
                        <td align="left">{{$job->description}}</td>
                        <td align="right">
                            @if(authorise('job.create'))
                                <a href="{{url('hr/job/create',['id'=>$job->id,'refer'=>$refer])}}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> 编辑</a>
                            @endif
                            @if(authorise('job.delete'))
                                <a onclick="app.confirm('{{url('hr/job/delete',['id'=>$job->id,'refer'=>$refer])}}','确定要删除吗？');" class="btn btn-xs btn-default"><i class="fa fa-times"></i> 删除</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            </div>
        </div>


<div class="panel">

    <div class="panel-heading b-b b-light text-base">
        <span class="pull-right">
            @if(authorise('cultivate.create'))
                <a href="{{url('hr/cultivate/create',['hr_id'=>$row->id,'refer'=>$refer])}}" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> 新建</a>
            @endif
        </span>
        培训记录
    </div>
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th align="center" width="160">日期</th>
                <th align="center" width="100">培训机构</th>
                <th align="left" width="140">培训项目</th>
                <th align="center" width="100">培训周期</th>
                <th align="center" width="100">培训费用</th>
                <th align="center" width="100">流程编号</th>
                <th align="left">备注</th>
                <th></th>
            </tr>
        </thead>
        @if($cultivates)
            @foreach($cultivates as $cultivate)
            <tr>
                <td align="center">{{$cultivate->start_date}}</td>
                <td align="center">{{$cultivate->organization}}</td>
                <td align="left">{{$cultivate->name}}</td>
                <td align="center">{{$cultivate->cycle}}</td>
                <td align="center">{{$cultivate->cost}}</td>
                <td align="center">{{$cultivate->process_id}}</td>
                <td align="left">{{$cultivate->description}}</td>
                <td align="right">
                    @if(authorise('cultivate.create'))
                        <a href="{{url('hr/cultivate/create',['id'=>$cultivate->id,'refer'=>$refer])}}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> 编辑</a>
                    @endif
                    @if(authorise('cultivate.delete'))
                        <a onclick="app.confirm('{{url('hr/cultivate/delete',['id'=>$cultivate->id,'refer'=>$refer])}}','确定要删除吗？');" class="btn btn-xs btn-default"><i class="fa fa-times"></i> 删除</a>
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    </div>
</div>

<div class="panel">

            <div class="panel-heading b-b b-light text-base">
                <span class="pull-right">
                    @if(authorise('punish.create'))
                        <a href="{{url('hr/punish/create',['hr_id'=>$row->id,'refer'=>$refer])}}" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> 新建</a>
                    @endif
                </span>
                扣罚记录
            </div>
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th align="center" width="120">日期</th>
                        <th align="center" width="360">扣罚原因</th>
                        <th align="center" width="60">扣罚分</th>
                        <th align="center" width="100">流程编号</th>
                        <th align="left">备注</th>
                        <th></th>
                    </tr>
                </thead>
                @if($punishs)
                    @foreach($punishs as $punish)
                    <tr>
                        <td align="center">{{$punish->start_date}}</td>
                        <td align="center">{{$punish->name}}</td>
                        <td align="center">{{$punish->grade}}</td>
                        <td align="center">{{$punish->process_id}}</td>
                        <td align="left">{{$punish->description}}</td>
                        <td align="right">
                            @if(authorise('punish.create'))
                                <a href="{{url('hr/punish/create',['id'=>$punish->id,'refer'=>$refer])}}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> 编辑</a>
                            @endif
                            @if(authorise('punish.delete'))
                            <a onclick="app.confirm('{{url('hr/punish/delete',['id'=>$punish->id])}}','确定要删除吗？');" class="btn btn-xs btn-default"><i class="fa fa-times"></i> 删除</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            </div>
        </div>
    </div>
  </div>