<table class="table m-b-none table-hover">
    <thead>
    <tr>
        <th align="center">序号</th>
        <th align="left">产品名称</th>
        <th align="right">报价</th>
        <th align="right">售价</th>
        <th align="center">审核</th>
        <th align="center">核销</th>
    </tr>
    </thead>
    <tbody>
    @if($rows)
    @foreach($rows as $i => $row)
    <tr>
        <td align="center">{{$i+1}}</td>
        <td>{{$row->product_name}}</td>
        <td align="right">{{$row->offer}}</td>
        <td align="right">{{$row->price}}</td>
        <td align="center">@if($row->audit == 1) 进店 @else 不进 @endif</td>
        <td align="center">@if($row->status == 1) 是 @else 否 @endif</td>
    </tr>
    @endforeach
    @endif
    </tbody>
</table>