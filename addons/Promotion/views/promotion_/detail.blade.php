<table class="table m-b-none table-hover">
    <thead>
    <tr>
        <th align="center">序号</th>
        <th align="left">产品名称</th>
        <th align="left">百分比</th>
        <th align="center"></th>
    </tr>
    </thead>
    <tbody>
    @if($rows)
    @foreach($rows as $i => $row)
    <tr>
        <td align="center">{{$i}}</td>
        <td>{{$row->product_name}}</td>
        <td>
            {{$row->percent}}
        </td>
        <td align="center"></td>
    </tr>
    @endforeach
    @endif
    </tbody>
</table>