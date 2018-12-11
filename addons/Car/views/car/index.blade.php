<div class="panel">

    <div class="wrapper">
        @if(isset($access['create']))
            <a href="{{url('create')}}" class="btn btn-info btn-sm"><i class="icon icon-plus"></i> 新建</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table b-t table-hover">
            <thead>
            <tr>
                <th align="center">车牌号</th>
                <th align="center">购买时间</th>
                <th align="center">车型</th>
                <th align="center">车架号</th>
                <th align="center">行驶证号</th>
                <th align="center">使用人</th>
                <th align="left">备注</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                <td align="center">{{$row['car_number']}}</td>
                <td align="center">{{$row['car_buy_date']}}</td>
                <td align="center">{{$row['car_type']}}</td>
                <td align="center">{{$row['car_frame_number']}}</td>
                <td align="center">{{$row['car_driving_license']}}</td>
                <td align="center">
                {{:$car_users = explode(',',$row['car_user_id'])}}
                @foreach($car_users as $key => $user_id)
                    @if($key > 0),@endif
                    {{get_user($user_id, 'nickname')}}
                @endforeach
                </td>
                <td align="left">{{$row['remark']}}</td>
                <td align="center">
                  <a class="option" href="{{url('create',array('id'=>$row['id']))}}">编辑</a>
                  <a class="option" href="javascript:app.confirm('{{url('delete',array('id'=>$row['id']))}}','确定要删除吗？');">删除</a>
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer class="panel-footer">
        <div class="text-right text-center-xs">
            {{$rows->render()}}
        </div>
    </footer>
</div>