@include('order.print.query')

<table>
    <tr>
        <td class="title" colspan="2">{{$setting['print_title']}}{{$title}}</td>
    </tr>
    <tr>
        <td width="50%" align="left">订单日期: {{$order['add_time'] > 0 ? date("Y-m-d H:i:s",$order['add_time']) : ""}}</td>
        <td width="50%" align="left">回执日期: {{date('Y-m-d H:i:s')}}</td>
    </tr>
    <tr>
        <td align="left">客户简称: {{$customer['nickname']}}</td>
        <td align="left">客户代码: {{$customer['username']}}</td>
    </tr>
    <tr>
        <td align="left">联 系 人: {{$customer['fullname']}}</td>
        <td align="left">移动电话: {{$customer['mobile']}}</td>
    </tr>
    <tr>
        <td align="left">公司地址: {{$customer['address']}}</td>
        <td align="left">联系电话: {{$customer['tel']}}</td>
    </tr>
    <tr>
        <td align="left">仓库地址: {{$customer['warehouse_address']}}</td>
        <td align="left">公司传真: {{$customer['fax']}}</td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <strong style="font-size:16px;">请按"备注栏"所描述的项目码与销售会计传真给您的兑现管理表核对所赠产品的费用金额。</strong>
        </td>
    </tr>
</table>

<table>
    <tr>
        <th width="60">序号</th>
        <th width="90">类型</th>
        <th>名称</th>
        <th width="100">规格</th>
        <th width="100">条码</th>
        <th width="50">单位</th>
        <th width="70">价格</th>
        <th width="70">数量</th>
        <th width="80">金额</th>
        <th width="80">运费</th>
        <th width="60">重量</th>
        <th width="60">支持金额</th>
        <th width="120">备注</th>
    </tr>

<?php
$ttm = $ttp = $ttw = $i = $freight_money_total = 0;
?>

@if($orderinfo)
@foreach($orderinfo as $v)

    <?php
    $productType = $orderType[$v['type']];

    $i++;

    $money = $remark_money = 0;

    $_money  = $v['amount'] * $v['price'];
    $_weight = $v['amount'] * $v['weight'];

    if ($customer->freight_type == 0) {
        $freight_money = $v['amount'] * $v['freight'];
        $freight_money_total += $freight_money;
    }

    if ($productType['type'] == 1) {
        $money = $_money;
    } else {
        $remark_money = $_money;
    }

    // 勾选了强行收费的产品不包含在内
    if ($v['force_charge'] == 0) {

        // 免物料费用的客户
        if ($customer->sp_materiel == 1 && $v['advert'] == 1) {
            $money = $fact_money = 0;
        }
    }
    /*
    if($customer->sp_materiel == 1 && $v['advert'] == 1) {
        $money = $remark_money = 0;
    }
    */
?>

<tr>
    <td align="center" id="{{$v['sort']}}">{{$i}}</td>
    <td align="center" style="white-space:nowrap;">{{$productType['title']}}</td>
    <td align="left" style="white-space:nowrap;">{{$v['name']}}</td>
    <td align="center">{{$v['spec']}}</td>
    <td align="center">{{$v['barcode']}}</td>
    <td align="center">{{option('product.unit', $v['unit'])}}</td>
    <td align="right">{{$v['price']}}</td>
    <td align="right">{{$v['amount']}}</td>
    <td align="right">{{number_format($money, 2)}}</td>
    <td align="right">{{number_format($freight_money, 2)}}</td>
    <td align="right">{{number_format($_weight, 2)}}</td>
    <td align="right">{{number_format($remark_money, 2)}}</td>
    <td align="left">{{$v['content']}}</td>
</tr>

<?php

$ttm += $v['amount'];

if ($productType['type'] == 1) {
    $ttp += $money;
} else {
    $ttp1 += $remark_money;
}

$ttw += $_weight;

?>

@endforeach 
@endif

<tr>
<td align="center">合计</td>
<td colspan="6"></td>
<td align="right">{{number_format($ttm,2)}}</td>
<td align="right">{{number_format($ttp,2)}}</td>
<td align="right">{{number_format($freight_money_total,2)}}</td>
<td align="right">{{number_format($ttw,2)}}</td>
<td align="right">{{number_format($ttp1,2)}}</td>
<td align="right"></td>
</tr>
</table>

<table>
<tr>
    <td colspan="3" align="center">
        请将货款汇入以下任意一个开户行、账号，并将汇款凭证传真至我司以便及时发货(028-38291666)
    </td>
</tr>

<tr>

    <td>
        <strong style="font-size:14px;">
            ①&nbsp;&nbsp;收款人: 四川省川南酿造有限公司<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行: 工商银行眉山市分行营业部<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帐&nbsp;&nbsp;&nbsp;号: 2313 3991 1910 0244 382
        </strong>
    </td>

    <td>
        <strong style="font-size:14px;">
            ②&nbsp;&nbsp;收款人: 四川省川南酿造有限公司<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行: 中国农业发展银行眉山市分行营业室<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帐&nbsp;&nbsp;&nbsp;号: 2035 1149 9001 0000 0199 391
        </strong>
    </td>
    
</tr>

<tr>

<td>
        <strong style="font-size:14px;">
            ③&nbsp;&nbsp;收款人：四川省川南酿造有限公司<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行：中国建设银行眉山财富中心支行<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帐&nbsp;&nbsp;&nbsp;号: 5100 1697 2680 5250 2839
        </strong>
    </td>
    <td></td>
    <!--
    <td>
        <strong style="font-size:14px;">
            ④&nbsp;&nbsp;收款人：管锐<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行：工商银行眉山分行<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帐&nbsp;&nbsp;&nbsp;号 : 6222 0823 1300 0725 012
        </strong>
    </td>
    -->
</tr>

<!--
<tr>
    <td>
        <strong style="font-size:14px;">
            ⑤&nbsp;&nbsp;收款人: 管锐<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行: 建设银行眉山苏提路分行<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帐&nbsp;&nbsp;&nbsp;号:  6236 6836 5000 0066 688
        </strong>
    </td>
    <td>
        <strong style="font-size:14px;">
            ⑥&nbsp;&nbsp;收款人：管锐<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行：农业银行眉山分行<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帐&nbsp;&nbsp;&nbsp;号: 6228 4840 9889 1599 078
        </strong>
    </td>
</tr>
-->
</table>

<table>
<tr>
    <td>制单人：<input type="text" class="text" value="向楠" /></td>
    <td>备注：<input type="text" class="text" style="width:300px;" value="" /></td>
</tr>

<tr>
    <td colspan="2" style="text-align:center;">以上货品我公司已列入生产计划，为确保您的货品及时发出，请在3日内打款并传真至(028)38418888</td>
</tr>

<tr>
    <td>1、订单查询(028)38291888</td>
    <td>1、入场、促销查询(028)38290688</td>
</tr>

<tr>
    <td>3、财务查询(028)38401188</td>
    <td>4、发货查询(028)38290888/18990389168</td>
</tr>

<tr>
    <td>5、投诉电话 18980373001</td>
    <td>@实际发货情况详见(发货清单)</td>
</tr>

<tr>
    <td colspan="2" style="text-align:center;">若汽运由贵公司代垫运费<!--，贵公司承担快运补贴<input class="text" type="text" style="width: 60px;" value="1" />元/件--></td>
</tr>

</table>