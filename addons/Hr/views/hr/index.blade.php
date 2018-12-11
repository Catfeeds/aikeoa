<div class="panel">

    <div class="panel-heading tabs-box">
        <ul class="nav nav-tabs">
            @foreach($status as $k => $v)
                <li class="@if($search['query']['status'] == $k) active @endif">
                    <a class="text-sm" href="{{url('',['status'=>$k])}}">{{$v}}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="wrapper">
        @include('hr/query')

    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table b-t m-b-none table-hover">
            <thead>
            <tr>
                <th align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="left">姓名</th>
                <th align="left">系统用户</th>
                <th align="center">系统部门</th>
                <th align="center">系统角色</th>
                <th align="center">系统职位</th>
                <th align="center">职级</th>
                <th align="left">岗位描述</th>
                <th align="center">单元</th>
                <th align="center">年龄</th>
                <th align="center">工龄</th>
                <th align="left">联络方式</th>
                <th align="center">ID</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
             @if($rows)
             @foreach($rows as $row)
                <tr>
                    <td align="center">
                        <label class="i-checks i-checks-sm m-b-none">
                            <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"><i></i>
                        </label>
                    </td>
                    <td align="left">{{$row->name}}</td>
                    <td align="left">{{$row->user->nickname}}</td>
                    <td align="center">{{$row->user->department->title}}</td>
                    <td align="center">{{$row->user->role->title}}</td>
                    <td align="center">{{option('user.position', $row->user->post)}}</td>
                    <td align="center">{{option('hr.rank', $row->rank_id)}}</td>
                    <td align="left">{{$row->position}}</td>
                    <td align="center">{{option('hr.unit', $row->unit)}}</td>
                    <td align="center">@age($row->birthday)</td>
                    <td align="center">@age($row->test_date)</td>

                    <td align="left">{{$row->home_contact}}</td>
                    <td align="center">{{$row->id}}</a></td>
                    <td align="center">
                        @if(isset($access['view']))
                            <a class="option" href="{{url('view',['id'=>$row->id])}}">查看</a>
                        @endif
                        @if(isset($access['create']) && $row->deleted_at == 0)
                            <a class="option" href="{{url('create',['id'=>$row->id])}}">编辑</a>
                        @endif

                    </td>
                </tr>
             @endforeach
             @endif
            </tbody>
        </table>
    </div>
    </form>

    <footer class="panel-footer">
        <div class="row">
            <div class="col-sm-1 hidden-xs">
            </div>
   
            <div class="col-sm-11 text-right text-center-xs">
                {{$rows->render()}}
            </div>
        </div>
    </footer>
</div>
