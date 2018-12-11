@include('layouts/extjs')

<form id="myform" name="myform" method="post">

 <div class="panel">
<table class="table table-form">
    <tr>
        <th align="left" colspan="4">客户信息</th>
    </tr>

    <tr>
        <td align="right" width="10%">客户</td>
        <td align="left" width="40%">
            {{Dialog::user('customer', 'customer_id', $selects['select']['customer_id'], 0, 0)}}
        </td>
        <td align="right" width="10%">下单流程</td>
        <td align="left" width="40%">
            @if(Auth::user()->role->name == 'customer')
                无
            @else
            <select class="form-control input-sm input-inline" id="step_id" name="step_id">
                @if($audit_config)
                @foreach($audit_config as $k => $v)
                @if($k > 0 && $k < 5)
                    <option value="{{$k}}">{{$k}}</option>
                @endif
                @endforeach 
                @endif
            </select>
            @endif
        </td>
    </tr>
</table>

</div>

 <div class="panel b-a">
<table class="table">

    <tr>
        <th align="left" colspan="4">订单信息</th>
    </tr>

    <tr>
        <td align="right" width="10%"><span style="color:red;">*</span> 开票类型</td>
        <td align="left" width="40%">
            <select class="form-control input-sm input-inline" id="invoice_type" name="invoice_type" onchange="invoiceTypeText(this.value);">
                <option value="0"> - </option>
                @foreach(option('customer.invoice') as $v)
                    @if($customer->invoice_type == 0 && $v['id'] == 3)
                        {{:continue}}
                    @endif
                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                @endforeach

            </select>
        </td>
        <td align="right" width="10%"><span style="color:red;">*</span> <span id="invoice_type_title">开票抬头</span> <i id="invoice_type_hint" style="display:none;" class="hinted fa fa-question-circle" title="若没有请与客户经理联系。"></i></td>
        <td align="left" width="40%">
            <span id="invoice_type_text">请选择开票类型</span>
        </td>
    </tr>

    <tr>
        <td align="right"><span style="color:red;">*</span> 下单人姓名</td>
        <td align="left">
            <input type="text" class="form-control input-sm input-inline" id="order_people" name="order_people" placeholder="请填写下单人姓名。">
        </td>
        <td align="right"><span style="color:red;">*</span> 下单人电话</td>
        <td align="left">
            <input type="text" class="form-control input-sm input-inline" id="order_people_phone" name="order_people_phone" placeholder="请填写下单人联系电话。">
        </td>
    </tr>
    
    <tr>
        <td align="right"><span style="color:red;">*</span> 送货车辆长度</td>
        <td align="left">
            <select class="form-control input-sm input-inline" id="transport_car_type" name="transport_car_type">
                <option value="0"> - </option>
                <option value="9.6">9.6米</option>
                <option value="13">13米</option>
                <option value="17.5">17.5米</option>
                <option value="4.2">4.2米</option>
                <option value="6.8">6.8米</option>
                <option value="8.6">8.6米</option>
            </select>
        </td>
        <td align="right"></td>
        <td align="left">
        </td>
    </tr>
    
</table>

</div>

<div class="panel b-a">
    <div class="wrapper-sm">
        <a class="btn btn-sm btn-default" href='javascript:openProduct();'>新增产品</a>
        <a class="btn btn-sm btn-default" href='javascript:saveStore();'>保存编辑</a>
        <a class="btn btn-sm btn-default" href='javascript:removeStore();'>删除产品</a>
        @if(authorise('account.query','customer'))
        <a class="btn btn-sm btn-default" href='{{url("customer/account/query")}}'>客户对账</a>
        @endif
    </div>
</div>

<script type="text/javascript">
function DATA_URL() {
    var customer_id = $('#customer_id').val();
    var DATA_URL = "{{url('data')}}?table=add&customer_id=" + customer_id;
    return DATA_URL;
}
function openProduct() {
    var customer_id = $('#customer_id').val();
    iframeBox("添加产品","{{url('product_add')}}?table={{$table}}&order_id={{$order['id']}}&customer_id=" + customer_id + "&s={{time()}}","全部添加");
}
</script>

@include('order/data')

<script type="text/javascript">
function dialog_customer_id() {
    reloadStore(DATA_URL());
}
</script>

<div class="panel m-t-sm b-a">
    <div class="wrapper-sm">
        <div class="form-group">
            <textarea class="form-control" placeholder="备注信息" rows="3" id="description" name="description">{{$order['description']}}</textarea>
        </div>
        <div class="form-group">
            <label class="checkbox-inline">
                <input name="sms" id="sms" type="checkbox" checked="true"> 短信提醒
            </label>
        </div>
        <div class="form-group">
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

</form>

<script type="text/javascript">
$('#myform').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (res) {
        if (res.status) {
            $.toastr('success', res.data);
            location.href = res.url;
        } else {
            $.toastr('error', res.data);
        }
    }, 'json');
    return false;
});

function invoiceSetTypeText(title, text) {
    document.getElementById('invoice_type_text').innerHTML = text;
    document.getElementById('invoice_type_title').innerHTML = title;
}

function invoiceTypeText(id)
{
    $('#invoice_type_hint').hide();
    var text = '';
    var title = '';
    if(id == 0) {
        invoiceSetTypeText('开票抬头', '请选择开票类型');
    } else if(id == 1) {
        var customer_id = $('#customer_id').val();
        if(!customer_id) {
            alert('请先选择客户');
            return false;
        }
        $.post('{{url("customer/bank/dialog")}}', {customer_id: customer_id}, function(data) {
            var html = '<select class="form-control input-sm input-inline" id="invoice_company" name="invoice_company"><option value=""> - </option>';
            $.each(data, function(key, row) {
                html += '<option value="'+ row['tax_name'] + '">' + row['tax_name'] + '</option>';
            });
            html += '</select>';
            $('#invoice_type_hint').show();
            invoiceSetTypeText('税票单位', html);
        });
    } else {
        invoiceSetTypeText('打款人', '<input type="text" class="form-control input-sm input-inline" id="invoice_company" name="invoice_company" placeholder="请输入持卡人名。">');
    }
}
</script>
