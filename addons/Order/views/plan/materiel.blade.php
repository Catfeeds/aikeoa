<div class="panel">

    <div class="wrapper">
        <form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

        <div class="form-group">
            年-月
            <input type="text" id="date" name="date" onclick="datePicker({dateFmt:'yyyy-MM'});" value="{{$date}}" class="form-control input-sm">
        </div>

        <button id="search-submit" type="submit" class="btn btn-sm btn-default">搜索</button>
    </form>
</div>

<table class="table m-b-none table-hover b-t">
<tr>
    <th align="center" width="40">序号</th>
    <th align="center" width="100">客户代码</th>
    <th align="left">客户名称</th>
    <th align="right" width="140">金额</th>
</tr>

@if($rows)
@foreach($rows as $k => $row)
<tr>
    <td align="center">{{$k}}</td>
    <td align="center">{{$row['username']}}</td>
    <td align="left">{{$row['nickname']}}</td>
    <td align="right">{{number_format($row['total'], 2)}}</td>
</tr>
@endforeach 
@endif
</table>

</div>