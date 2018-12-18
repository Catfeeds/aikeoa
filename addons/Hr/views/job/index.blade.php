<div class="panel">

    <div class="panel-heading tabs-box">
        <ul class="nav nav-tabs">
            @foreach(Aike\Hr\HrJob::$_status as $k => $v)
                <li class="@if($query['status'] == $k) active @endif">
                    <a class="text-sm" href="{{url('index',['status'=>$k])}}">{{$v}}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="wrapper">
        @include('query')
    </div>
    
    <div class="table-responsive">
        <table class="table b-t table-hover">
            <thead>
            <tr>
                <th align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="center">姓名</th>
                <th align="center">性别</th>
                <!--
                <th align="center">系统部门</th>
                <th align="center">系统角色</th>
                <th align="center">系统职位</th>
                -->
                <th align="center">岗位描述</th>
                <th align="center">类型</th>
                <th align="center">日期</th>
                <th align="center">审批</th>
                <th align="center">ID</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
             @if($rows)
             @foreach($rows as $row)

                <?php
                    $step = get_step_status($row);
                ?>

                <tr>
                    <td align="center">
                        <label class="i-checks i-checks-sm m-b-none">
                            <input class="select-row" type="checkbox" name="post[{{$row->id}}]"><i></i>
                        </label>
                    </td>
                    <td align="center">{{$row->nickname}}</td>
                    <td align="center">
                        {{option('user.gender', $row->gender)}}
                    </td>
                    <!--
                    <td align="center">{{$row->department->name}}</td>
                    <td align="center">{{$row->role->name}}</td>
                    <td align="center">{{option('user.position', $row->post)}}</td>
                    -->
                    <td align="center">{{$row->name}}</td>
                    <td align="center">{{option('hr.job.type', $row->type)}}</td>
                    <td align="center">{{$row->start_date}}</td>
                    <td align="center">
                        @if($step['edit'])
                            <a class="label label-danger label-turn" href="javascript:;" onclick="app.turn('{{$step['key']}}', true);">{{$row->step->name}}</a>
                        @else
                            <span class="label label-success">
                            {{$row->step->name}}
                            </span>
                        @endif
                    </td>
                    <!--
                    <td align="center">
                        {{$step['html']}}
                    </td>
                    -->
                    <td align="center">{{$row->id}}</td>
                    <td align="center">
                        @if(isset($access['create']))
                            <a class="option" onclick="app.confirm('{{url('create',['id'=>$row->id])}}','编辑后需要重新审批','编辑记录');">编辑</a>
                        @endif
                        @if(isset($access['delete']))
                            <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row->id])}}','确定要删除吗？');">删除</a>
                        @endif
                    </td>
                </tr>
             @endforeach
             @endif
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-1 hidden-xs">
            </div>
            <div class="col-sm-11 text-right text-center-xs">
                {{$rows->render()}}
            </div>
        </div>
    </div>

</div>
