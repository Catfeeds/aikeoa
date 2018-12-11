<style>
.tab-content {
    background-color: #fff;
}
.tab-content .table {
    margin-bottom: 0;
}
.nav-tabs {
    padding-left: 10px;
}
</style>

<!-- Nav tabs -->
<ul class="nav nav-tabs m-t-sm padder" role="tablist">
    <li class="active"><a href="#tab_1" role="tab" data-toggle="tab">预发配送信息</a></li>
    <li><a href="#tab_2" role="tab" data-toggle="tab">实发配送信息</a></li>
</ul>

<form id="transportform" name="transportform" action="{{url()}}" method="post">

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="tab_1">
        <table class="table table-form">
            <tr>
                <th align="right" width="20%">预发物流公司: </th>
                <td align="left">
                    {{Dialog::user('logistics', 'transport[logistics_id]', $transport['logistics_id'], 0, 0)}}
                </td>
            </tr>

            <tr>
                <th align="right">预发物流公司备注: </th>
                <td align="left">
                    <input type="text" name="transport[advance_car_company]" placeholder="预发物流公司备注" value="{{$transport['advance_car_company']}}" class="form-control input-sm" />
                </td>
            </tr>

            <tr>
                <th align="right">预发车牌号: </th>
                <td align="left">
                    <input type="text" name="transport[advance_car_number]" placeholder="运输公司车牌号" value="{{$transport['advance_car_number']}}" class="form-control input-sm" />
                </td>
            </tr>

            <tr>
                <th align="right">预发数量: </th>
                <td align="left">
                    <input type="text" name="transport[advance_amount]" placeholder="默认获取当前订单实发数量" value="{{$transport['advance_amount']}}" class="form-control input-sm" />
                </td>
            </tr>

            <tr>
                <th align="right">预发重量: </th>
                <td align="left">
                    <input type="text" name="transport[advance_weight]" placeholder="默认获取当前订单实发重量" value="{{$transport['advance_weight']}}" class="form-control input-sm" />
                </td>
            </tr>

            <tr>
                <th align="right">预发时间: </th>
                <td align="left">
                    <input type="text" name="transport[advance_time]" placeholder="选择预计发货时间" value="{{$transport['advance_time'] > 0 ? date("Y-m-d H:i:s",$transport['advance_time']) : ""}}" data-toggle="datetime" class="form-control input-sm" />
                </td>
            </tr>

            <tr>
                <th align="right">预发仓位: </th>
                <td align="left">
                    <input type="text" name="transport[advance_depot]" placeholder="预装车仓位号码" value="{{$transport['advance_depot']}}" class="form-control input-sm" />
                </td>
            </tr>

            <tr>
                <th align="right">预发仓号: </th>
                <td align="left">
                    <input type="text" name="transport[advance_depot_number]" placeholder="预装车仓号是仓位组合。" value="{{$transport['advance_depot_number']}}" class="form-control input-sm" />
                </td>
            </tr>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_2">
        <table class="table table-form">
            <tr>
                <th align="right" width="20%">承运公司: </th>
                <td align="left">
                    {{$logistics['name']}}
                </td>
            </tr>

            <tr>
                <th align="right">承运司机: </th>
                <td align="left">
                    <input type="text" name="transport[contact]" class="form-control input-sm" placeholder="承运司机姓名" value="{{$transport['contact']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">承运司机电话: </th>
                <td align="left">
                    <input type="text" name="transport[phone]" class="form-control input-sm" placeholder="承运司机电话" value="{{$transport['phone']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">运单号: </th>
                <td align="left">
                    <input type="text" name="transport[reference_number]" class="form-control input-sm" placeholder="此物流的运单号码" value="{{$transport['reference_number']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">发货方式: </th>
                <td align="left">
                    <input type="text" name="transport[manner]" class="form-control input-sm" placeholder="例如：汽运、火车等" value="{{$transport['manner']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">发货时间: </th>
                <td align="left">
                    <input type="text" name="order[delivery_time]" class="form-control input-sm" data-toggle="datetime" class="form-control input-sm" value="{{$order['delivery_time'] > 0 ? date("Y-m-d H:i:s",$order['delivery_time']) : ""}}" readonly />
                </td>
            </tr>

            <tr>
                <th align="right">预计到货时间: </th>
                <td align="left">
                    <input type="text" name="transport[advance_arrival_time]" data-toggle="datetime" class="form-control input-sm" value="{{$transport['advance_arrival_time'] > 0 ? date("Y-m-d H:i:s",$transport['advance_arrival_time']) : ""}}" readonly />
                </td>
            </tr>

            <tr>
                <th align="right">实际重量: </th>
                <td align="left">
                    <input type="text" name="transport[freight_weight]" class="form-control input-sm" value="{{$transport['freight_weight']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">实际吨位: </th>
                <td align="left">
                    <input type="text" name="transport[freight_tonnage]" class="form-control input-sm" value="{{$transport['freight_tonnage']}}" />
                </td>
            </tr>
            
            <tr>
                <th align="right">运价: </th>
                <td align="left">
                    <input type="text" name="transport[freight_price]" class="form-control input-sm" value="{{$transport['freight_price']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">送货费: </th>
                <td align="left">
                    <input type="text" name="transport[delivery_money]" class="form-control input-sm" value="{{$transport['delivery_money']}}" />
                </td>
            </tr>

            <tr>
                <th align="right">运费金额: </th>
                <td align="left">
                    <input type="text" name="transport[freight_money]" class="form-control input-sm" value="{{$transport['freight_money']}}" />
                </td>
            </tr>
    
            <tr>
                <th align="right">备注: </th>
                <td align="left">
                    <textarea class="form-control input-sm" id="desc" name="transport[desc]">{{$transport['desc']}}</textarea>
                </td>
            </tr>
        </table>
    </div>
</div>

<input type="hidden" name="order_id" id="order_id" value="{{$order['id']}}" />
<input type="hidden" name="customer_id" id="customer_id" value="{{$order['customer_id']}}" />

</form>
