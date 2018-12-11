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
                <th align="center">促销编号</th>
                <th align="left">客户名称</th>
                <th align="left">{{url_order($search,'promotion.start_at','开始日期')}}</th>
                <th align="left">{{url_order($search,'promotion.end_at','结束日期')}}</th>
                <th>促销类型</th>
                <!--<th align="center">素材</th>-->
                <th align="left">{{url_order($search,'promotion.step_number','流程')}}</th>
                <th align="center">促销商品</th>
                <th align="left">促销范围</th>
                <!--<th align="left">促销目标</th>-->
                <th align="left">促销方法</th>
                <th align="left">区域经理支持意见</th>
                <th align="right">预估费用</th>
                <th align="right">兑现费用</th>
            </tr>
            </thead>
            <tbody>
            @if($rows)
            @foreach($rows as $row)
            <?php
                $step = get_step_status($row);
            ?>
            <tr>
                <td align="center">{{$row->number}}</td>
                <td>{{$row->nickname}}</td>
                <td>{{$row->start_at}}</td>
                <td>{{$row->end_at}}</td>
                <td align="center">{{option('promotion.type', $row->type_id)}}</td>
                <!--
                <td align="center">{{$materials[$row->material_id]['name']}}</td>
                -->
                <td align="left">
                    ({{$row->step->number}}){{$row->step->name}}
                </td>
                <td align="center"><a class="option" onclick="viewBox('view','单品明细','{{url('detail', ['id' => $row['id']])}}');" href="javascript:;"> 明细点击 </a></td>
                <td>{{$row->data_4}}</td>
                <!--
                <td>{{$row->data_3}}</td>
                -->
                <td>{{$row->data_5}}</td>
                <td>{{$row->data_10}}</td>
                <td align="right">{{$row->data_amount}}</td>
                <td align="right">{{$row->data_amount1}}</td>
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