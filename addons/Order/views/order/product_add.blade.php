<style>
.content-body {
    margin: 0;
}
.panel {
    padding-bottom: 0;
    margin: 0; 
}
</style>

<div class="panel">

<table class="table">
    <tr>
      <td align="left">
        <form id="myquery" name="myquery" action="{{url()}}" method="get">
        <select class="form-control input-sm input-inline" id='category_id' name='category_id' data-toggle="redirect" rel="{{url('',$query)}}">
            <option value="0">产品类别</option>
            @if($categorys)
            @foreach($categorys as $category)
                @if($category['selected'] == true)
                    <option value="{{$category['id']}}" @if($query['category_id'] == $category['id']) selected @endif>{{$category['layer_space']}}{{$category['name']}}</option>
                @endif
            @endforeach
            @endif
        </select>

        &nbsp;

        <select class="form-control input-sm input-inline" id='search_key' name='search_key'>
        	<option value="p.name" @if($query['search_key']=='p.name') selected @endif>产品名称</option>
        	<option value="p.barcode" @if($query['search_key']=='p.barcode') selected @endif>产品条码</option>
            <option value="p.stock_code" @if($query['search_key']=='p.stock_code') selected @endif>产品代码</option>
        </select>

        <select class="form-control input-sm input-inline" id='search_condition' name='search_condition'>
        	<option value="like" @if($query['search_condition']=='like') selected @endif>包含</option>
        	<option value="=" @if($query['search_condition']=='=') selected @endif>等于</option>
        </select>

        <input type="text" class="form-control input-sm input-inline" name="search_value" value="{{$query['search_value']}}" />

        <input type="hidden" name="order_id" value="{{$query['order_id']}}" />
        <input type="hidden" name="customer_id" value="{{$query['customer_id']}}" />
        <button type="submit" class="btn btn-default btn-sm">搜索</button>
      </form>
      </td>
  </tr>
</table>

<form id="myform" name="myform" action="{{url('',$query)}}" method="post">
<table class="table table-hover table-bordered m-b-none">
    <tr>
        <th align="center" width="80"></th>
        <th align="left">名称 / 规格 / 条码</th>
        <th align="center" width="100">单价</th>
        <th align="center" width="100">折扣率(%)</th>
        <th align="center" width="100">订单数量</th>
        <th align="center" width="100">类型</th>
        <th align="center" width="100"></th>
	</tr>
    {{:$i = 0}}
     @if($rows)
     @foreach($rows as $k => $v)
    <tr>
        <td align="center">
            {{goodsImage($v)}}
        </td>
        <td align="left">
            {{$v['name']}} - {{$v['spec']}}
            <div>{{$v['barcode']}}</div>
        </td>
        <td align="center">
            <input type="text" class="form-control input-sm input-inline product_{{$v['id']}}" data-id="{{$v['id']}}" style="@if($priceItem[$v['id']] > 0) color:red @endif" id="price_{{$v['id']}}" size="8" name="product[{{$i}}][price]" @if(Auth::user()->role->name == 'customer') readonly="readonly" @endif value="{{$v['price']}}" />
        </td>
        
        <td align="center">
            <input type="text" class="form-control input-sm input-inline product_{{$v['id']}}" data-id="{{$v['id']}}" style="@if($priceItem[$v['id']] > 0) color:red @endif" id="rate_{{$v['id']}}" size="8" name="product[{{$i}}][discount_rate]" @if(Auth::user()->role->name == 'customer') readonly="readonly" @endif value="{{$v['rate']}}" />
        </td>
        <td align="center" style="white-space:nowrap;">
            <input type="text" class="form-control input-sm input-inline product_{{$v['id']}}" id="amount_{{$v['id']}}" size="8" name="product[{{$i}}][amount]" />
        </td>

        <td align="center">
            <select class="form-control input-sm input-inline product_{{$v['id']}}" id="type_{{$v['id']}}" name='product[{{$i}}][type]' @if(Auth::user()->role->name == 'customer') disabled @endif>
                 @if($orderType) @foreach($orderType as $k2 => $v2)
                     @if($v2['state'] == 1)
                         @if($v2['parent_id'] == 0)
                             @if($n > 0) </optgroup> @endif
                            <optgroup label="{{$v2['title']}}">
                         @endif
                         @if($v2['parent_id'] > 0)
                            <option value="{{$k2}}">{{$v2['title']}}</option>
                         @endif

                     @endif
                 @endforeach @endif
            </select>
        </td>
        <td align="center">
            <a onclick="update({{$v['id']}});" class="btn btn-info btn-xs" href="javascript:void(0);">添加</a>
            <input type="hidden" class="product_{{$v['id']}}" id="product_{{$v['id']}}" name="product[{{$i}}][product_id]" value="{{$v['id']}}" />
        </td>
    </tr>
    {{:$i++}}
 @endforeach 
 @endif

</table>

</form>

</div>

<script type="text/javascript">

var frame = window.top.frames['tab_iframe_' + window.top.tabActiveId];

// 弹窗回调保存事件
function iframeSave() {
    updateAll();
}

// 弹窗回调取消事件
function iframeCancel() {
    // window.parent.frames["main"].win.dialog('close');
}

// 产品添加
function updateAll()
{
    var myform = {product:[{amount:0}]};
    var formData = $('#myform').serializeArray();
    var res = {};
    $.each(formData, function(k, v) {
        res[v.name] = v.value;
    });
    $.post("{{url('', $query)}}", res, function(result) {
        if (result == 1) {
            // 添加成功，刷新订单列表页
            frame.reloadStore();
            frame.$.toastr('success', '恭喜你，产品添加成功。');
        } else {
            frame.$.toastr('error', result);
        }
    });
}

//产品添加
function update(id)
{
    var formData = $('.product_'+id).serializeArray();
    var res = {};
    $.each(formData, function(k, v) {
        res[v.name] = v.value;
    });
    
    $.post("{{url('', $query)}}", res, function(result) {
        if (result == 1) {
            frame.reloadStore();
            //刷新框架
            frame.$.toastr('success', '恭喜你，产品添加成功。');
        } else {
            frame.$.toastr('error', result);
        }
    });
}
</script>
