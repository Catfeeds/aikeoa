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
                <th align="center">日期</th>
                <th align="center">车牌号</th>
                <th align="center">公里(照片)</th>
                <th align="center">加油(升)</th>
                <th align="center">加油(金额)</th>
                <th align="center">当前公里(km)</th>
                <th align="center">位置信息</th>
                <th align="center">操作</th>
            </tr>
            </thead>
            <tbody>
             
            @foreach($rows as $key => $row)

                {{:$_km    = $row['km'] - $km}}
                {{:$_litre = $row['km'] > 0 ? $row['litre']/$row['km'] - $km : 0}}
                {{:$_money = $row['km'] > 0 ? $row['money']/$row['km'] - $km : 0}}

                <tr>
                    <td align="center">{{date('Y-m-d H:i',$row['created_at'])}}</td>
                    <td align="center">{{$row['car_number']}}</td>
                    <td align="center"><a href="javascript:viewBox('photo','照片','{{url('car/car/photo',array('type'=>'refuel','id'=>$row['id']))}}');"><i class="icon icon-picture"></i></a></td>
                    <td align="center">{{$row['litre']}}</td>
                    <td align="center">{{$row['money']}}</td>
                    <td align="center">{{$row['km']}}</td>
                    <td align="center"><a href="javascript:viewBox('location','位置信息','{{url('index/api/location',array('lng'=>$row['lng'],'lat'=>$row['lat']))}}');"><i class="icon icon-map-marker"></i></a></td>
                    <td align="center">
                      <a class="option" href="javascript:app.confirm('{{url('delete',array('id'=>$row['id']))}}','确定要删除吗？', '操作确认');">删除</a>
                    </td>
                </tr>
                {{:$km = $row['km']}}

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