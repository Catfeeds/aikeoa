<div class="panel">

<table class="table">
<tr>
    <th align="left" colspan="2">基本信息</th>
</tr>
<tr>
    <td align="right" width="10%">有效日期</td>
    <td align="left">
        {{date('Y-m-d',$row['end_time'])}}
    </td>
</tr>

</table>

</div>
<div class="panel">

<table class="table" id="view_category_item">
    <tr>
        <th align="left">产品类别</th>
        <th align="left">折扣率(%)</th>
    </tr>
    @foreach($categorys as $category)
    <?php 
        $_category = $row['category_item'][$category['id']];
    ?>
    @if($_category['active'] == 1)
    <tr>
        <td align="left">
            {{$category['text']}}
        </td>
        <td align="left">
            {{$_category['rate']}}
        </td>
    </tr>
    @endif
    @endforeach
</table>

<table class="table b-t" id="view_price_item">
    <tr>
        <th align="left" colspan="3">产品单价</th>
    </tr>
    <tr>
        <th align="left" width="300">产品</th>
        <th align="left" width="100">单价</th>
        <th align="left" width="100">折扣率(%)</th>
    </tr>
    @if(sizeof($price_items) > 0)
        @foreach($price_items as $price_item)
        <tr>
            <td align="left">
                {{$price_item['product_text']}}
            </td>
            <td align="left">
                {{$price_item['price']['price']}}
            </td>
            <td align="left">
                {{$price_item['price']['rate']}}
            </td>
        </tr>
        @endforeach
    @endif
</table>

</div><div class="panel">

<table class="table">
<tr>
    <th align="left" width="15%" colspan="12">月任务(万)</th>
</tr>
<tr>
    {{:$months = range(1, 12)}}
    @foreach($months as $v)
        <th class="center">{{$v}}月</th>
    @endforeach
</tr>
<tr>
    @foreach($months as $v)
        <td align="center">{{$row['month_task'][$v]}}</td>
    @endforeach
</tr>
</table>

<table class="table b-t">
<tr>
    <th align="left" colspan="4" width="15%">季度任务(万)</th>
</tr>
<tr>
    {{:$quarter = array(1=>'一',2=>'二',3=>'三',4=>'四')}}
    @foreach($quarter as $v)
        <th align="center">{{$v}}季度</th>
    @endforeach
</tr>
<tr>
    @foreach($quarter as $k => $v)
    <td align="center">{{$row['quarter_task'][$k]}}万</td>
    @endforeach
</tr>
</table>

</div><div class="panel">

<table class="table">
<tr>
    <td align="right" width="10%">备注</td>
    <td align="left">{{nl2br($customer['remark'])}}</td>
</tr>
</table>

</div>