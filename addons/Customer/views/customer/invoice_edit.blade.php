<form method="post" action="{{url()}}" id="myform" name="myform">

<div class="panel">
<table class="table table-form">
<tr>
    <td align="right" width="120">客户名称</td>
    <td>
        {{$row->user->nickname}}
    </td>
</tr>

<tr>
    <td align="right">开票类型</td>
    <td>
        <select class="form-control input-inline input-sm" name="invoice_type" id="invoice_type">
            <option value="">无</option>
             @foreach(option('customer.invoice') as $v)
                <option value="{{$v['id']}}" @if($row->invoice_type == $v['id']) selected @endif>{{$v['name']}}</option>
             @endforeach
        </select>
    </td>
</tr>

<tr>
    <th align="right"></th>
    <td>
        <input type="hidden" name="id" value="{{$row->id}}">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>提交</button>
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    </td>
</tr>

</table>

</form>

</div>