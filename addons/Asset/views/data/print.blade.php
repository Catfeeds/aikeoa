<table class="table">
<tr>
    <th align="center" colspan="3" style="font-size:16px;">四川省川南酿造有限公司固定资产使用确认表</th>
</tr>
 
<tr>
    <td align="left">固定资产品类别: {{$assets[$row->asset_id]->name}}</td>
    <td align="left">品牌: {{$row->name}}</td>
    <td align="left">型号: {{$row->model}}</td>
</tr>

<tr>
    <td align="left">识别码: {{$row->number}}</td>
    <td align="left">参照适用年限: {{$row->age_limit}}</td>
    <td align="left">采购日期:  {{$row->buy_date}}</td>
</tr>

<tr>
    <td align="left" colspan="3">目前使用人确认: (签字)</td>
</tr>

<table class="table">
<thead>
    <tr>
        <th align="center">使用日期</th>
        <th align="center">使用人</th>
    </tr>
</thead>
    @if($logs)
    @foreach($logs as $log)
    <tr>
        <td align="center">{{$log->start_date}}</td>
        <td align="center">{{get_user($log->user_id, 'nickname')}}</td>
    </tr>
    @endforeach
    @endif
</table>
