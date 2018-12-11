<script type="text/javascript" src="{{$asset_url}}/js/grid.js"></script>
<style> 
td { vertical-align: middle !important; }
@media screen and (max-width: 767px) {
    .table-responsive td, .table-responsive th {
        white-space: normal !important;
    }
}
</style>

<table class="table table-condensed" width="100%">
    <tr>
        <td align="right" width="15%" nowrap="true">客户:</td>
        <td align="left" width="35%">
            {{$form->customer('customer_id', 0)}}
        </td>
        <td align="right" width="15%">客户负责人:</td>
        <td align="left" width="35%">
            {{$form->data['owner']}}
        </td>
    </tr>
    <tr>
        <td align="right">进店类型:</td>
        <td align="left">
            {{$form->option('type', 'approach.type')}}
        </td>
        <td align="right">批复编号:</td>
        <td align="left">
            {{$form->text('sn')}}
        </td>
    </tr>

    <tr>
        <td align="left" colspan="4">
            零售全称：{{$form->text('data_1')}}，
            对方采购：{{$form->text('data_2')}}，
            联络：{{$form->text('data_3')}}，
            所属零售区域：{{$form->text('data_4')}}，
            收银台数量：{{$form->text('data_68')}}
        </td>
    </tr>

    <tr>
        <td align="left" colspan="4">
            业态以及进店 - 连锁便利{{$form->text('data_5')}}
            家，本品进{{$form->text('data_6')}}
            家，连锁超市{{$form->text('data_7')}}
            家 ，本品进{{$form->text('data_8')}}
            家，大型卖场{{$form->text('data_9')}}
            家，本品进{{$form->text('data_10')}}
            家
        </td>
    </tr>

    <tr>
        <td align="left" colspan="4">
            经销商配送：{{$form->text('data_11')}}，经销商维护：专职导购{{$form->text('data_12')}}
            个，专职理货{{$form->text('data_13')}}
            个。	经销商共计{{$form->text('data_14')}}
            码已销售。
        </td>
    </tr>

</table>

<div class="red m-xs" style="text-align: center">提示：超6家的用附件上传</div>
{{$form->grid('approach_address', ['name', 'address'])}}

<table class="table table-condensed" width="100%">
<tr>
    <td>
        进店类别：{{$form->option('data_15', 'approach.category')}}
        &nbsp;
        进店类型：{{$form->option('data_60', 'approach.type1')}}
        &nbsp;
        说明：{{$form->text('data_16')}} 若新条码进老店，原条码情况。
    </td>
</tr>
<tr id="approach_data_60_box" style="display:none;">
    <td>
        原有门店家数: {{$form->text('data_61')}}
        &nbsp;
        原有条码情况：
        品牌辣酱{{$form->text('data_62')}} 码, 
        油辣子类{{$form->text('data_63')}} 码, 
        下饭小菜{{$form->text('data_64')}} 码, 
        下饭瓶菜{{$form->text('data_65')}} 码, 
        下饭泡菜{{$form->text('data_66')}} 码, 
        袋装佐料{{$form->text('data_67')}} 码
    </td>
</tr>
</table>

<div class="padder-xs">进店单品明细</div>
{{$form->grid('approach_data', ['product_name', 'barcode', 'offer', 'price', 'audit', 'status'])}}

<table class="table table-condensed" width="100%">

    <tr>
        <td align="left" colspan="4">①经销商承担的金额低于(实际进店金额*经销商承担比例)则视为虚报费用.②实际进店额指经销商实际支出的条码费用,不包括进店所产生的客情以及其他费用. ③发生虚报视为经销商放弃川南支持.本表内容以董事办批复为准，贵司收到回复两日内无书面疑义视为同意。</td>
    </tr>

    <tr>
        <td align="left" colspan="4">
            条码费用标准：{{$form->text('data_17')}}
            元/店/码，进店{{$form->text('data_18')}}
            家，{{$form->text('data_19')}}
            码，费用{{$form->text('data_20')}}
            元。经销商承担{{$form->text('data_21')}}
            %，既{{$form->text('data_22')}}
            元。申请川南支持{{$form->text('data_23')}}
            %，即{{$form->text('data_24')}}
            元。
        </td>
    </tr>
</table>

<table class="table" width="100%">
    <tr>
        <th align="left" colspan="2">客户助理初审:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            初审意见: {{$form->text('data_25')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">销售人员建议:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            ①同意以上{{$form->text('data_26')}}
            支条码进店{{$form->text('data_27')}}
            家, 我司支持实际条码总费用的{{$form->text('data_28')}}
            %,并不高于{{$form->text('data_29')}}
            元费用支持。
            ②以上条码已进行初审。审批意见：{{$form->text('data_30')}}
            &nbsp;审批时间：{{$form->auto('data_31')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">客户助理审核:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            <!--
            <p>
            该店条码费用其他参照：{{$form->textarea('data_32')}}
            </p>
            -->
            该客户累计条码费用支持：{{$form->text('data_33')}}
            &nbsp;审核时间：{{$form->auto('data_34')}}
        </td>
    </tr>
    
    <tr>
        <th align="left" colspan="2">总经理批复:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            双方的约定，贵公司在本批复确认后，按上述价格、具体单品要求,本品{{$form->text('data_35')}}
            支SKU进此申请系统进(单)店{{$form->text('data_36')}}
            家后，我司给予实际进店费用{{$form->text('data_37')}}
            %但并不高于{{$form->text('data_38')}}
            元的条码费用支持，具体根据事实发生并由贵公司根据我公司的要求邮寄相关资料经审核后给予货补兑现.(每次兑现比例详见双方的合同条款);若实际进店费用低于上述的申请标准,经销商应在费用核销前书面形式提出,我司将做支持金额调整,未有说明,则为经销商虚报费用.若经销商在2个月内未进店,(以货架陈列为准),该申请作废。
            <br>批复内容：{{$form->text('data_39')}}
            &nbsp;批复时间：{{$form->auto('data_40')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">董事办初审:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            初审意见：{{$form->text('data_41')}}
            &nbsp;审批时间：{{$form->auto('data_44')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">董事办批复:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            批复意见：{{$form->text('data_42')}}
            &nbsp;审批时间：{{$form->auto('data_43')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">客户经理复核:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            复核意见：{{$form->text('data_56')}}
            &nbsp;复核时间：{{$form->auto('data_57')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">客户助理备案:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            客户助理打印传真客户。并确认客户收到传真。
            <br>备案意见：{{$form->text('data_45')}}
            &nbsp;备案时间：{{$form->auto('data_46')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">客户助理核销:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            <p>
            核销素材：
            {{$form->checkbox('data_50')}}
            </p>
            <p>
            收到素材日期：{{$form->date('data_51')}}
            &nbsp;收到方式：{{$form->option('data_52', 'approach.express')}}
            &nbsp;素材审核：{{$form->option('data_55', 'approach.material')}}
            </p>
            核销意见：{{$form->text('data_53')}}
            &nbsp;核销时间：{{$form->auto('data_54')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">销售会计备案:</th>
    </tr>
    <tr>
        <td align="left" colspan="2">
            兑现方式：{{$form->option('data_47', 'approach.cash')}}
            &nbsp;备案金额：{{$form->text('data_48')}}
            &nbsp;备案时间：{{$form->auto('data_49')}}
        </td>
    </tr>

</table>

{{$form->hidden('id')}}
{{$form->js()}}

<script type="text/javascript">
$(function() {
    approach_data_60_box("{{$form->data['approach_data_60']}}");
    $("#approach_data_60").on('change', function() {
        var v = $(this).val();
        approach_data_60_box(v);
    });
});

function approach_data_60_box(v) {
    if(v > 1) {
        $('#approach_data_60_box').show();
    } else {
        $('#approach_data_60_box').hide();
    }
}
</script>