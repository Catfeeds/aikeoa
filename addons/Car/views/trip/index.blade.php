@include('layouts/map')
<div class="panel">

    <div class="wrapper">
        <form id="myform" role="form" class="form-inline" name="myform" action="{{url()}}" method="get">
            @include('query')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table b-t table-hover">
            <thead>
            <tr>
                <th align="center">车牌号</th>
                <th align="center">使用人</th>
                <th align="left">出发前</th>
                <th align="left">回驻地</th>
                <th align="center">阶段行程</th>
                <th align="center">阶段耗时</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
             @if($rows)
             @foreach($rows as $date => $row)
                <tr>
                <td align="center">{{$row['car_number']}}</td>
                <td align="center">{{get_user($row['created_by'], 'nickname')}}</td>
                @if($row['id'] > 0)
                <td align="left">
                    <a href="javascript:viewBox('photo','照片','{{url('car/car/photo',array('type'=>'trip','id'=>$row['id']))}}');"><i class="icon icon-picture"></i></a>
                    &nbsp;
                    <a href="javascript:viewBox('location','位置信息','{{url('index/api/location',array('lng'=>$row['lng'],'lat'=>$row['lat']))}}');"><i class="icon icon-map-marker"></i></a>
                    &nbsp;
                    {{date('Y-m-d H:i',$row['created_at'])}}
                    &nbsp;
                    {{$row['km']}}公里
                    &nbsp;
                    {{$row['remark']}}
                </td>
                @else
                <td></td>
                @endif

                @if($row['back']['id'] > 0)
                <td align="left">
                    <a href="javascript:viewBox('photo','照片','{{url('car/car/photo',array('type'=>'trip','id'=>$row['back']['id']))}}');"><i class="icon icon-picture"></i></a>
                    &nbsp;
                    <a href="javascript:viewBox('location','位置信息','{{url('index/api/location',array('lng'=>$row['back']['lng'],'lat'=>$row['back']['lat']))}}');"><i class="icon icon-map-marker"></i></a>
                    &nbsp;
                    {{date('Y-m-d H:i',$row['back']['created_at'])}}
                    &nbsp;
                    {{$row['back']['km']}}公里
                </td>
                @else
                <td></td>
                @endif

                <td align="center">
                @if($row['back']['created_at'] > 0)
                    {{$row['back']['km']-$row['km']}}
                @endif
                </td>
                <td align="center">
                @if($row['back']['created_at'] > 0)
                    {{remain_time($row['created_at'], $row['back']['created_at'])}}
                @endif
                </td>

                <td align="center">
                    <a class="option" href="javascript:app.confirm('{{url('delete',array('id'=>$row['id']))}}','确定要删除吗？');">删除</a>
                </td>

                </tr>
                @endforeach
             @endif
            </tbody>
        </table>
    </div>

    <footer class="panel-footer">
        <div class="text-right text-center-xs">
            {{$rows->render()}}
        </div>
    </footer>
</div>