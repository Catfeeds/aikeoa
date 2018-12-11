<form method="post" action="{{url()}}" id="myform" name="myform">

<div class="panel">

<table class="table table-form">
<tr>
    <th align="left" colspan="2">基本信息</th>
</tr>
<tr>
    <td align="right" width="10%">有效日期</td>
    <td align="left">
        <input type="text" data-toggle="date" class="form-control input-sm input-inline" name="end_time" value="{{date('Y-m-d',$row['end_time'])}}" />
    </td>
</tr>
</table>

</div><div class="panel">

<table class="table b-b table-form" id="view_category_item">
    <tr>
        <th align="left">产品类别</th>
        <th align="left">类别折扣率(%)</th>
    </tr>
    @foreach($categorys as $category)
    <?php 
        $_category = (array)$row['category_item'][$category['id']];
        $category_rate = $_category['active'] > 0 ? $_category['rate'] : 100;
     ?>
    <tr>
        <td align="left">
            <label class="checkbox-inline">
                <input type="checkbox" name="category_item[{{$category['id']}}][active]" value="1" @if($_category['active'] == '1') checked @endif /> {{$category['layer_html']}}{{$category['name']}}
            </label>
        </td>
        <td align="left">
            <input type="text" class="form-control input-sm input-inline" name="category_item[{{$category['id']}}][rate]" value="{{$category_rate}}" />
        </td>
    </tr>
    @endforeach
</table>

@include('contract.add_product')

</div><div class="panel">

<table class="table table-form">
<tr>
    <th align="left" width="15%" colspan="12">月任务(万)</th>
</tr>
<tr>
    {{:$months = range(1, 12)}}
    @foreach($months as $v)
        <th align="center">{{$v}}月</th>
    @endforeach
</tr>
<tr>
    @foreach($months as $v)
        <td align="center"><input type="text" size="8" class="form-control input-sm input-inline" name="month_task[{{$v}}]" value="{{$row['month_task'][$v]}}" /></td>
    @endforeach
</tr>
</table>

<table class="table b-t table-form">
<tr>
    <th align="center">一季度(万)</th>
    <th align="center">二季度(万)</th>
    <th align="center">三季度(万)</th>
    <th align="center">四季度(万)</th>
</tr>
<tr>
    <td align="center">{{$row['quarter_task'][1]}}</td>
    <td align="center">{{$row['quarter_task'][2]}}</td>
    <td align="center">{{$row['quarter_task'][3]}}</td>
    <td align="center">{{$row['quarter_task'][4]}}</td>
</tr>
</table>

</div><div class="panel">

<table class="table table-form">
<tr>
    <td width="15%" align="right">备注</td>
    <td align="left"><textarea class="form-control input-sm" name="remark">{{$row['remark']}}</textarea></td>
</tr>
</table>

</div>

<div class="panel">
    <div class="panel-body">
        <input type="hidden" id="id" name="id" value="{{$row['id']}}" />
        <input type="hidden" id="customer_id" name="customer[id]" value="{{$customer['id']}}" />
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </div>
</div>

</form>