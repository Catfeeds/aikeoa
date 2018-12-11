<table class="table table-form" width="100%">
    <tr>
        <th width="6%">承运公司</th>
        <th width="10%">发货时间</th>
        <th width="10%">预达时间</th>
        <th width="10%">实达时间</th>
        <th width="10%">运费承担</th>
    </tr>
    <tr>
        <td align="left">{{$transport['carriage']}}</td>
        <td align="left">{{$order['delivery_time'] > 0 ? date("Y-m-d H:i:s",$order['delivery_time']) : ""}}</td>
        <td align="left">{{$transport['advance_arrival_time'] > 0 ? date("Y-m-d H:i:s",$transport['advance_arrival_time']) : ""}}</td>
        <td align="left">{{$order['arrival_time'] > 0 ? date("Y-m-d H:i:s",$order['arrival_time']) : ""}}</td>
        <td align="left">{{$transport['freight']}}</td>
    </tr>
</table>