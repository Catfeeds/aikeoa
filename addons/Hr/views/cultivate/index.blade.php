<div class="panel">

    <div class="panel-heading tabs-box">
        <ul class="nav nav-tabs">
            @foreach(Aike\Hr\HrJob::$_status as $k => $v)
                <li class="@if($search['query']['status'] == $k) active @endif">
                    <a class="text-sm" href="{{url('index',['status'=>$k])}}">{{$v}}</a>
                </li>
            @endforeach
        </ul>
        </ul>
    </div>

    <div class="wrapper-sm b-b b-light">
        @include('query')
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="left">姓名</th>
                <th align="left">培训机构</th>
                <th align="left">培训项目</th>
                <th align="center">培训周期</th>
                <th align="center">培训费用</th>
                <th align="center">日期</th>
                <th align="center">状态</th>
                <th align="center">编号</th>
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
                    <td align="left">{{$row->hr_name}}</td>
                    <td align="left">{{$row->organization}}</td>
                    <td align="left">{{$row->name}}</td>
                    <td align="center">{{$row->cycle}}</td>
                    <td align="right">{{$row->cost}}</td>
                    <td align="center">{{$row->start_date}}</td>
                    <td align="center">
                        @if($step['edit'])
                            <a class="label label-{{$step['color']}} label-turn" href="javascript:;" onclick="app.turn('{{$step['key']}}', true);">{{$step['name']}}</a>
                        @else
                            <span class="label label-{{$step['color']}}">{{$step['name']}}</span>
                        @endif
                    </td>
                    <td align="center">{{$row->id}}</td>
                    <td align="center">
                        @if(isset($access['create']))
                            @if($row->status == 0)
                                <a class="option" href="{{url('create',['id'=>$row->id])}}">编辑</a>
                            @else
                                <a class="option" onclick="app.confirm('{{url('create',['id'=>$row->id])}}','编辑后需要重新审批','编辑记录');">编辑</a>
                            @endif
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
