<div class="panel">
    
    @include('tabs')

    <div class="wrapper">
        @include('approach/query')
    </div>

    <form method="post" id="myform" name="myform">

    <div class="table-responsive">

        <table class="table m-b-none b-t table-hover">
            <thead>
            <tr>
                <th align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="center">促销编号</th>
                <th align="left">客户名称</th>
                <th align="left">开始日期</th>
                <th align="left">结束日期</th>
                <th>促销类型</th>
                <th>兑现方式</th>
                <th align="center">素材</th>
                <th align="left">流程</th>
                <th>创建时间</th>
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
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"><i></i>
                    </label>
                </td>
                <td align="center">{{$row->number}}</td>
                <td>{{$row->nickname}}</td>
                <td>
                    {{$row->start_at}}
                </td>
                <td>
                    {{$row->end_at}}
                </td>
                <td align="center">{{option('promotion.type', $row->type_id)}}</td>
                <td align="center">{{$cashs[$row->data_18]['name']}}</td>
                <td align="center"><span class="label label-{{$materials[$row->material_id]['color']}}">{{$materials[$row->material_id]['name']}}</span></td>
                <td align="left">
                    <span class="@if($step['edit'])bg-danger @endif badge">{{$row->step->number}}</span> {{$row->step->name}}
                </td>
                <td align="center">@datetime($row->created_at)</td>
                <td align="center">
                    <a class="option" href="{{url('promotion/promotion/show', ['id' => $row['id']])}}"> 查看 </a>
                    @if(isset($access['restore']))
                    <a class="option" onclick="app.confirm('{{url('restore',['id'=>$row['id']])}}','确定要恢复除吗？');" href="javascript:;">恢复</a>
                    @endif
                    @if(isset($access['destroy']))
                    <a class="option" onclick="app.confirm('{{url('destroy',['id'=>$row['id']])}}','确定要销毁除吗？');" href="javascript:;">销毁</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
    </form>

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