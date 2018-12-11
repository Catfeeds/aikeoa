<div role="tabpanel" class="hbox hbox-auto-xs hbox-auto-sm">

<div class="col w-md bg-light bg-auto lt b-r">

    <div class="wrapper b-b">
        <span class="label label-primary">
        <i class="icon icon-user"></i>
        {{$row->name}}
        </span>
    </div>

    <div class="wrapper-sm">
        <!-- Nav tabs -->
        <ul class="nav nav-pills nav-stacked nav-sm" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="pull-right fa fa-angle-right"></i> 基本资料</a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="pull-right fa fa-angle-right"></i> 工作记录</a></li>
            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="pull-right fa fa-angle-right"></i> 培训记录</a></li>
            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="pull-right fa fa-angle-right"></i> 扣罚记录</a></li>
        </ul>
    </div>

</div>

<div class="col">
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">

        <div class="panel">

            <div class="panel-heading b-b b-light text-base">
                @if(authorise('create'))
                    <span class="pull-right">
                        <a href="{{url('create',['id'=>$row->id])}}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                        <button onclick="history.back();" class="btn btn-xs btn-black">返回</button>
                    </span>
                @endif
                基本资料
            </div>

            <div class="table-responsive">
            <table class="table">
                <tr>
                    <td align="center" colspan="4">
                        @if($row->image)
                        <img id="avatar" width="120" height="120" src="{{$upload_url}}/{{$row->image}}" class="thumbnail m-b-none">
                        @else
                        <img id="avatar" width="120" height="120" src="{{$asset_url}}/images/a1.jpg" class="thumbnail m-b-none">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th width="15%" align="right">姓名</th>
                    <td width="35%">{{$row->name}}</td>
                    <th width="15%" align="right">部门</th>
                    <td width="35%">{{get_department(get_user($row->user_id, 'department_id'),'title')}}</td>
                </tr>

                <tr>
                    <th align="right">职级</th>
                    <td>{{$positions[$row->position_id]->name}}</td>
                    <th align="right">岗位</th>
                    <td>{{$row->position}}</td>
                </tr>

                <tr>
                    <th align="right">入职日期</th>
                    <td>{{$row->test_date}}</td>
                    <th align="right">转正日期</th>
                    <td>{{$row->job_date}}</td>
                </tr>

                <tr>
                    <th align="right">年龄</th>
                    <td>@age($row->birthday)</td>
                    <th align="right">工龄</th>
                    <td>@age($row->test_date)</td>
                </tr>

                <tr>
                    <th align="right">保状</th>
                    <td>{{option('hr.insurance', $row->insurance)}}</td>
                    <th align="right">生日</th>
                    <td>{{$row->birthday}}</td>
                </tr>

                <tr>
                    <th align="right">身份证</th>
                    <td>{{$row->idcard}}</td>
                    <th align="right">学历</th>
                    <td>{{$row->degre}}</td>
                </tr>

                <tr>
                    <th align="right">家庭住址</th>
                    <td>{{$row->home_address}}</td>
                    <th align="right">联系方式</th>
                    <td>{{$row->home_contact}}</td>
                </tr>

                <tr>
                    <th align="right">状态</th>
                    <td>{{Hr::$_status[$row->status]}}</td>
                    <th align="right">描述</th>
                    <td>{{$row->description}}</td>
                </tr>

            </table>
            </div>
        </div>


    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
        
        <div class="panel">

            <div class="panel-heading b-b b-light text-base">
                <span class="pull-right">
                    @if(authorise('job.create'))
                        <a href="{{url('hr/job/create',['hr_id'=>$row->id])}}" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> 新建</a>
                    @endif
                </span>
                工作记录
            </div>
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th align="center" width="160">日期</th>
                        <th align="center" width="140">部门</th>
                        <th align="center" width="140">角色</th>
                        <th align="center" width="140">职位</th>
                        <th align="center" width="160">岗位描述</th>
                        <th align="center" width="120">职级</th>
                        <th align="left" width="120">流程编号</th>
                        <th align="left">备注</th>
                    </tr>
                </thead>
                @if($jobs)
                    @foreach($jobs as $job)
                    <tr>
                        <td align="center">{{$job->start_date}}</td>
                        <td align="center">
                            {{get_department($job->department_id, 'title')}}
                        </td>
                        <td align="center">{{$job->name}}</td>
                        <td align="center">
                            {{$positions[$job->position_id]->name}}
                        </td>
                        <td align="left">{{$job->process_id}}</td>
                        <td align="left">{{$job->description}}</td>
                        <td align="right">
                            @if(authorise('job.delete'))
                                <a onclick="app.confirm('{{url('hr/job/delete',['id'=>$job->id])}}','确定要删除吗？');" class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            </div>
        </div>


    </div>
    <div role="tabpanel" class="tab-pane" id="messages">
        
        <div class="panel">

    <div class="panel-heading b-b b-light text-base">
        <span class="pull-right">
            @if(authorise('cultivate.create'))
                <a href="{{url('hr/cultivate/create',['hr_id'=>$row->id])}}" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> 新建</a>
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
                    @if(authorise('cultivate.delete'))
                    <a onclick="app.confirm('{{url('hr/cultivate/delete',['id'=>$cultivate->id])}}','确定要删除吗？');" class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    </div>
</div>

    </div>
    <div role="tabpanel" class="tab-pane" id="settings">
        

        <div class="panel">

            <div class="panel-heading b-b b-light text-base">
                <span class="pull-right">
                    @if(authorise('punish.create'))
                        <a href="{{url('hr/punish/create',['hr_id'=>$row->id])}}" class="btn btn-xs btn-info"><i class="fa fa-plus"></i> 新建</a>
                    @endif
                </span>
                扣罚记录
            </div>
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th align="center" width="160">日期</th>
                        <th align="center" width="100">扣罚原因</th>
                        <th align="center" width="100">扣罚分</th>
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
                            @if(authorise('punish.delete'))
                            <a onclick="app.confirm('{{url('hr/punish/delete',['id'=>$punish->id])}}','确定要删除吗？');" class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>
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

</div>


