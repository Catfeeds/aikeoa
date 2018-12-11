<div class="panel">
    
    @include('tabs')

    <div class="wrapper">
        @include('promotion/query')
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
                <th align="left">{{url_order($search,'promotion.start_at','开始日期')}}</th>
                <th align="left">{{url_order($search,'promotion.end_at','结束日期')}}</th>
                <th>促销类型</th>
                <th>兑现方式</th>
                <th>促销单品</th>
                <th>促销方法</th>
                <th>兑现依据</th>
                <th>兑现金额</th>
                <th align="center">素材照片</th>
                <th align="left">{{url_order($search,'promotion.step_number','流程')}}</th>
                <th>{{url_order($search,'promotion.created_at','创建时间')}}</th>
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
                <td align="center">{{$row->number}}</td>
                <td>{{$row->nickname}}</td>
                <td>
                    {{$row->start_at}}
                </td>
                <td>
                    <?php $date = date('Y-m-d', strtotime('-1 month')); ?>
                    @if($date >= $row->end_at)
                    <span class="red">{{$row->end_at}}</span>
                    @else
                    {{$row->end_at}}
                    @endif
                </td>
                <td align="center">{{option('promotion.type', $row->type_id)}}</td>
                <td align="center">{{$cashs[$row->data_18]['name']}}</td>
                <td align="center"><a class="option" onclick="viewBox('view','单品明细','{{url('detail', ['id' => $row['id']])}}');" href="javascript:;"> 明细 </a></td>
                <td align="center"><a class="option" data-toggle="tooltip" title="{{$row->data_5}}" href="javascript:$.messager.alert('促销方法', '{{$row->data_5}}');">详情</a></td>
                <td align="center"><a class="option" data-toggle="tooltip" title="{{$row->data_19}}" href="javascript:$.messager.alert('兑现依据', '{{$row->data_19}}');">详情</a></td>
                <td align="center"><a class="option" onclick="viewBox('view','兑现明细','{{url('cash/promotion', ['promotion_id' => $row['id']])}}');" href="javascript:;"> {{$row->cashs->sum('money')}} </a></td>
                <td align="center"><a class="option" href="{{url('material/detail', ['promotion_id' => $row['id']])}}">{{$materials[$row->material_id]['name']}}({{(int)$_materials[$row->id]}})</a></td>
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
                    @if(isset($access['delete']))
                    <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
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