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
                <th align="center">进店编号</th>
                <th align="left">客户名称</th>
                <th>零售全称</th>
                <th>进店类型</th>
                <th>兑现方式</th>
                <th>进店单品</th>
                <th align="left">{{url_order($search,'approach.step_number','流程')}}</th>
                <th>{{url_order($search,'approach.created_at','创建时间')}}</th>
                <th align="center">创建到现在时间</th>
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
                <td align="center">{{$row->sn}}</td>
                <td>{{Dialog::text('customer', $row->customer_id)}}</td>
                <td align="center">{{$row->data_1}}</td>
                <td align="center">{{option('approach.type', $row->type)}}</td>
                <td align="center">{{$cashs[$row->data_18]['name']}}</td>
                <td align="center"><a class="option" onclick="viewBox('view','单品明细','{{url('detail', ['id' => $row['id']])}}');" href="javascript:;"> 明细 </a></td>
                <td align="left">
                    <span class="@if($step['edit'])bg-danger @endif badge">{{$row->step->number}}</span> {{$row->step->name}}
                </td>
                <td align="center">@datetime($row->created_at)</td>
                <td align="center"><?php echo time_day_hour($row->created_at); ?></td>
                <td align="center">
                    <a class="option" href="{{url('show', ['id' => $row['id']])}}"> 查看 </a>
                    @if($step['edit'] == 1)
                        <a class="option" href="{{url('edit', ['id' => $row['id']])}}">审核</a>
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