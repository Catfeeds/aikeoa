<table class="table">
  <thead>
    <tr>
    <th align="left">产品类别</th>
    <th align="left">产品名称</th>
    <th align="right">数量</th>
    <th align="center">生产批号</th>
    <th align="left">备注</th>
	</tr>
</thead>

   @if($rows) @foreach($rows as $v)
  <tr>
    <td align="left">{{$v['category_name']}}</td>
    <td align="left">{{$v['name']}} - {{$v['spec']}}</td>
    <td align="right">{{$v['amount']}}</td>
    <td align="center">{{$v['batch']}}</td>
    <td align="left">{{$v['stock_remark']}}</td>
  </tr>
   @endforeach @endif
</table>
