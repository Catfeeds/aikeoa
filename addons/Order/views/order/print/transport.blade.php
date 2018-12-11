@include('order.print.query')

<table>
<tr>
    <th colspan="4" class="info center" style="font-size:18px;text-align:center;">{{$setting['print_title']}}{{$title}}</th>
</tr>

<tr>
    <td width="50%" align="left">订 单 号: {{$order['number']}}</td>
    <td width="50%" align="left">订单日期: {{$order['add_time'] > 0 ? date("Y-m-d H:i:s",$order['add_time']) : ""}}</td>
</tr>

<tr>
    <td align="left">客户简称: {{$client['nickname']}}</td>
    <td align="left">客户代码: {{$client['username']}}</td>
</tr>

<tr>
    <td align="left">负责人手机: {{$client['fullname']}}</td>
    <td align="left">客户负责人: {{$client['mobile']}}</td>
</tr>

<tr>
    <td align="left">公司地址: {{$client['address']}}</td>
    <td align="left">联系电话: {{$client['tel']}}</td>
</tr>

<tr>
    <td align="left">公司传真: {{$client['fax']}}</td>
    <td align="left">销售员: {{get_user($client['salesman_id'], 'nickname')}}</td>
</tr>
<tr>
    <td align="center" colspan="2">
        <strong style="font-size:16px;">请按"备注栏"所描述的项目码与销售会计传真给您的兑现管理表核对所赠产品的费用金额。</strong>
        <div>请注意：产品条码前7位已经隐藏[6915993]。</div>
    </td>
</tr>

</table>

<table>
	<tr>
        <th width="80">序号</th>
        <th width="80">类型</th>
        <th>名称</th>
        <th width="160">条码</th>
		<th width="120">规格</th>
        <th width="100">单价</th>
        <th width="80">数量</th>
		<th width="100">金额</th>
        <th width="100">支持金额</th>
        <th width="120">备注</th>
	</tr>

{{:$ttm = $ttp = $ttp1 = $ttw = $i = 0}}

 @if($orderinfo) @foreach($orderinfo as $v)

     @if($v['advert'] == 1)
        {{:$warehouse_status = 1}}
     @endif

     @if($v['advert'] == 0)

        {{:$productType = $orderType[$v['type']]}}
        {{:$i++}}

        @if($productType['type'] == 1)
            {{:$money = (int)$v['fact_amount'] * $v['price']}}
        @else
            {{:$remark_money = (int)$v['fact_amount'] * $v['price']}}
        @endif

        <tr>
            <td align="center" id="{{$v['sort']}}">{{$i}}</td>
            <td align="center" style="white-space:nowrap;">{{$productType['title']}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
            <td align="center">{{substr($v['barcode'],7)}}</td>
            <td align="center">{{$v['spec']}}</td>
            <td align="right">{{$v['price']}}</td>
            <td align="right">{{$v['fact_amount']}}</td>
            <td align="right"> @if($productType['type']==1) {{number_format($v['fact_amount'] * $v['price'],2)}} @else 0.00 @endif </td>
            <td align="right"> @if($productType['type']==0) {{number_format($v['fact_amount'] * $v['price'],2)}} @else 0.00 @endif </td>
            <td align="left">{{$v['content']}}</td>
        </tr>

        {{:$ttm += $v['fact_amount']}}

        
         @if($productType['type']==1)
            {{:$ttp += $money}}
         @else
            {{:$ttp1 += $remark_money}}
         @endif

        {{:$ttw += $v['fact_amount'] * $v['weight']}}

     @endif

 @endforeach @endif

<tr>
    <td>合计</td>
    <td colspan="5"></td>
    <td align="right">{{number_format($ttm,2)}}</td>
    <td align="right">{{number_format($ttp,2)}}</td>
    <td align="right">{{number_format($ttp1,2)}}</td>
    <td align="right"></td>
</tr>

<tr>
    <td colspan="11">大写: {{str_rmb($ttp)}}</td>
</tr>
</table>

 @if($warehouse_status == 1)
<table>
    <tr>
        <th width="80">序号</th>
        <th width="80">类型</th>
        <th>名称</th>
        <th width="160">条码</th>
        <th width="120">规格</th>
        <th width="100">单价</th>
        <th width="80">数量</th>
        <th width="100">金额</th>
        <th width="100">支持金额</th>
        <th width="120">备注</th>
    </tr>

{{:$ttm1 = $ttp = $ttp1 = $ttw = $i = 0}}

 @if($orderinfo) @foreach($orderinfo as $v)

    @if($v['advert'] == 1)

        <?php

        $productType = $orderType[$v['type']];

        $i++;

        if ($productType['type'] == 1) {
            $money = $v['fact_amount'] * $v['price'];
        } else {
            $remark_money = $v['fact_amount'] * $v['price'];
        }
        
        // 勾选了强行收费的产品不包含在内
        if ($v['force_charge'] == 0) {
            // 免物料费用的客户
            if ($customer->sp_materiel == 1 && $v['advert'] == 1) {
                $money = $fact_money = 0;
            }
        }

        $ttm1 += $v['fact_amount'];

        if ($productType['type'] == 1) {
            $ttp += $money;
        } else {
            $ttp1 += $remark_money;
        }
        $ttw += $v['fact_amount'] * $v['weight'];

        ?>

        <tr>
            <td align="center" id="{{$v['sort']}}">{{$i}}</td>
            <td align="center" style="white-space:nowrap;">{{$productType['title']}}</td>
            <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
            <td align="center">{{$v['barcode']}}</td>
            <td align="center">{{$v['spec']}}</td>
            <td align="right">{{$v['price']}}</td>
            <td align="right">{{$v['fact_amount']}}</td>
            <td align="right">{{number_format($money, 2)}}</td>
            <td align="right">{{number_format($remark_money, 2)}}</td>
            <td align="left">{{$v['content']}}</td>
        </tr>

    @endif

@endforeach 
@endif

<tr>
    <td>合计</td>
    <td colspan="5"></td>
    <td align="right">{{number_format($ttm1,2)}}</td>
    <td align="right">{{number_format($ttp,2)}}</td>
    <td align="right">{{number_format($ttp1,2)}}</td>
    <td align="right"></td>
</tr>
<tr>
    <td colspan="11">大写: {{str_rmb($ttp)}}</td>
</tr>
</table>
 @endif

<table>
<tr>
    <td colspan="11">备注: {{$order['description']}}</td>
</tr>

<tr>
    <td colspan="11">随货同行: {{$order['goods']}}</td>
</tr>

<tr>
    <td colspan="11">发货查询: 028-38290888&nbsp;&nbsp;&nbsp;&nbsp;028-38588888</td>
</tr>

<tr>
    <td colspan="11">尊敬的客户，收到本传真后若有疑义，请致电: 028-38588888, 24小时内未回执视为认可。</td>
</tr>

<tr>
    <td colspan="5">投诉电话: 18980373001</td>
    <td colspan="6">最高投诉电话: 13778838001(短信)</td>
</tr>

<tr>
    <td colspan="5">制单人: 李培</td>
    <td colspan="6">审核人: 李雪萍</td>
</tr>

</table>

<div style="page-break-before:always;">

<table>
<tr>
    <td colspan="4" class="info center" style="font-size:32px;text-align:right;">{{$client['nickname']}}</td>
</tr>

<tr>
    <th colspan="4" class="info center" style="font-size:18px;text-align:center;">{{$setting['print_title']}}发货通知单 - 此单需回执，此单做为代垫运费回传依据</th>
</tr>
    
<tr>
    <td colspan="4" align="left">
    尊敬的客户: {{$client['nickname']}}
    </td>
</tr>

<tr>
    <td colspan="2" align="left">发货时间: {{$order['delivery_time'] > 0 ? date("Y-m-d H:i:s",$order['delivery_time']) : ""}}</td>
    <td colspan="2" align="left">预计到货时间: {{$transport['advance_arrival_time'] > 0 ? date("Y-m-d H:i:s",$transport['advance_arrival_time']) : ""}}</td>
</tr>

<tr>
    <td align="left">承运公司: {{$transport['carriage']}}</td>
    <td align="left">运输方式: {{$transport['manner']}}</td>
    <td align="left">货品数量: {{$ttm}}</td>
    <td align="left">配件数量: {{$transport['parts_quantity']}}</td>
</tr>

<tr>
    <td colspan="4" class="info left">承运司机电话：{{$transport['phone']}}</td>
</tr>

<tr>
    <td colspan="4" align="left">运费情况: {{$transport['freight']}}</td>
</tr>

<tr>
    <td colspan="4" align="left">货物终到方式: {{$transport['arrivalpattern']}}</td>
</tr>

<tr>
    <td colspan="4" align="center" style="font-size:18px;">请访问网站 [www.shenghuafood.com] 查询具体发货明细</td>
</tr>

</table>

<table style="position:relative;">
    <tr>
        <td style="width:12px;" align="left">特别说明</td>
        <td align="left" style="white-space:normal;">
        1、如您对此表有任何建议或疑问，欢迎致电:028-38290888；18990389168 邓泽民，为了我们能更方便即时的沟通欢迎将我们电话号码存入您或收货人手机。
        <br />
        2、收货时,请按我公司<发货单>点货验收,货物如有缺失或破损,请与承运方协调,在代垫运费中直接扣减;在未代垫运费的情况下如货物出现异常,请第一时间告之我司并向承运方索取有效的货物异常证明(贵司收货人与承运方双方签字认可的货物运单)回传至我司028-38418888并确认收到。
        <br />
        3、为了能更好地为您服务，请您在收到货品2日内填写此表并回传我公司,若您到货后未填写回传,我公司将不予以提供货品理赔、代垫费用核销等其他发货后的服务。
        </td>
    </tr>

    <tr>
        <td style="width:12px;" align="left">签发</td>
        <td align="left">
            我公司经办人签字: 邓泽民 &nbsp;&nbsp;(加盖公司印章)
            <img class="zhang" src="{{$asset_url}}/images/fahuozhang.gif" />
        </td>
    </tr>
    
    <tr>
        <td style="width:12px;" align="left">回执运单</td>
        <td align="left" style="font-size:16px;line-height:28px;">
                <div class="line">____________月____________日收到此批货物共计____________件，配件____________件。</div>
                <div class="line">运费情况：代垫运费<span style="font-size:30px;">□</span>&nbsp;&nbsp;&nbsp;&nbsp;金额：____________元&nbsp;&nbsp;&nbsp;&nbsp;未代垫运费<span style="font-size:30px;">□</span></div>
                <div class="line">货品情况：<strong>是否按单品顺序装货</strong><span style="font-size:30px;">□</span>&nbsp;&nbsp;&nbsp;&nbsp;完好<span style="font-size:30px;">□</span>&nbsp;&nbsp;&nbsp;&nbsp;缺少<span style="font-size:30px;">□</span>&nbsp;&nbsp;&nbsp;&nbsp;破损<span style="font-size:30px;">□</span></div>
                <div class="line">赔付与否：不需赔付<span style="font-size:30px;">□</span>&nbsp;&nbsp;&nbsp;&nbsp;要求赔付<span style="font-size:30px;">□</span></div>
                <div class="line">赔付要求:</div>
                <div class="line">您对我公司此次配送服务是否满意：满意<span style="font-size:30px;">□</span>&nbsp;&nbsp;&nbsp;&nbsp;不满意<span style="font-size:30px;">□</span></div>
                贵司经办人签字(加盖贵公司印章):
        </td>
    </tr>

    <tr>
        <td colspan="5" class="info center">请您完整填写此表回传到我司028-38418888 并电话028-38291888 38290888确认收到.   谢谢合作!</td>
    </tr>

</table>
</div>