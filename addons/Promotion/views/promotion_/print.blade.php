<script type="text/javascript" src="{{$asset_url}}/js/grid.js"></script>
<style> 
td { vertical-align: middle !important; }
</style>
<style type="text/css">
@media screen and (max-width: 767px) {
    .table-responsive td, .table-responsive th {
        white-space: normal !important;
    }
}
</style>

<table class="table table-condensed" width="100%">
    <tr>
        <td align="right" width="15%" nowrap="true">促销客户:</td>
        <td align="left" width="35%">
            {{$form->customer('customer_id')}}
        </td>
        
        <td align="right" width="15%" nowrap="true">促销类型:</td>
        <td align="left" width="35%">
            {{$form->option('type_id', 'promotion.type')}}
        </td>
    </tr>
    <tr>
        <td align="right">促销区域:</td>
        <td align="left">
            {{$form->data['owner']}}
        </td>
        <td align="right">本年促销费比:</td>
        <td align="left">
            {{$form->text('cost')}} %
        </td>
    </tr>

    <tr>
        <td align="right">促销编号:</td>
        <td align="left">
            {{$form->text('number')}}
        </td>
        <td align="right"></td>
        <td align="left"></td>
    </tr>

</table>

{{$form->grid('promotion_data', ['product_name', 'percent'], true)}}

<table class="table table-condensed" width="100%">

    <tr>
        <td align="right" width="15%" nowrap="true">开始日期:</td>
        <td align="left" width="35%">
            {{$form->date('start_at')}}
        </td>
        <td align="right" width="15%" nowrap="true">结束日期:</td>
        <td align="left" width="35%">
            {{$form->date('end_at')}}
        </td>
    </tr>

    <tr>
        <td align="right">客户促销负责人:</td>
        <td align="left">
            {{$form->text('data_1')}}
        </td>
        <td align="right">客户促销负责人手机:</td>
        <td align="left">
            {{$form->text('data_2')}}
        </td>
    </tr>

    <tr>
        <td align="right">促销目标:</td>
        <td align="left">
            {{$form->textarea('data_3')}}
        </td>
        <td align="right">促销范围:</td>
        <td align="left">
            {{$form->textarea('data_4')}}
        </td>
    </tr>

    <tr>
        <td align="right">促销方法:</td>
        <td align="left">
            {{$form->textarea('data_5')}}
        </td>
        <td align="right">促销宣传:</td>
        <td align="left">
            {{$form->textarea('data_6')}}
        </td>
    </tr>

    <tr>
        <td align="right">陈列:</td>
        <td align="left">
            {{$form->text('data_7')}}
        </td>
        <td align="right">所需宣传物料:</td>
        <td align="left">
            {{$form->textarea('data_8')}}
        </td>
    </tr>

    <tr>
        <td align="right">销售人员促销执行追踪示范计划:</td>
        <td align="left">
            {{$form->text('data_9')}} (注明具体时间)
        </td>
        <td align="right">核销依据:</td>
        <td align="left">
            {{$form->text('data_19')}}
        </td>
    </tr>

    <tr>
        <td align="right">素材对接人:</td>
        <td align="left">
            {{$form->text('data_11')}}
        </td>
        <td align="right">素材对接人电话:</td>
        <td align="left">
            {{$form->text('data_12')}}
        </td>
    </tr>

    <tr>
        <td align="right">tg照片:</td>
        <td align="left">
            {{$form->text('data_13')}} 家&nbsp;&nbsp;{{$form->text('data_14')}} 张
        </td>
        <td align="right">端架照片:</td>
        <td align="left">
            {{$form->text('data_15')}} 家&nbsp;&nbsp;{{$form->text('data_16')}} 张
        </td>
    </tr>

    <tr>
        <td align="right">是否提供超市送货验收单:</td>
        <td align="left">
            {{$form->text('data_17')}}
        </td>
        <td align="right">兑现方式:</td>
        <td align="left">
            {{$form->select('data_18', ['1'=>'现配','2'=>'凭兑'])}}
        </td>
    </tr>

    <tr>
        <td align="right">区域经理支持意见:</td>
        <td align="left">
            {{$form->textarea('data_10')}}
        </td>
        <td align="right">单品备注:</td>
        <td align="left">
            {{$form->text('product_remark')}}
        </td>
    </tr>
</table>

<table class="table table-condensed" width="100%">

    <tr>
        <th align="left" colspan="4">上级销售意见</th>
    </tr>
    <tr>
        <td align="right" width="15%" nowrap="true">审核意见:</td>
        <td align="left">
            {{$form->textarea('data_34')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="4">市场助理初审</th>
    </tr>
    
    <tr>
        <td align="right" width="15%" nowrap="true">初审意见:</td>
        <td align="left" width="85%">
            {{$form->text('data_20')}}
        </td>
        </tr>
    <tr>
        <td align="right" width="15%"></td>
        <td align="left" width="85%">
        </td>
    </tr>

    <tr>
        <th align="left" colspan="4">总经理批复</th>
    </tr>
    <tr>
        <td align="right">是否写促销总结:</td>
        <td align="left">
            {{$form->select('data_31', ['1'=>'是','0'=>'否'])}}
        </td>
    </tr>
    <tr>
        <td align="right">批复意见:</td>
        <td align="left">
            {{$form->textarea('data_21')}}
        </td></tr>
    <tr>
        <td align="right"></td>
        <td align="left"></td>
    </tr>

    <tr>
        <th align="left" colspan="4">市场助理备案</th>
    </tr>
    <tr>
        <td align="right">预估费用:</td>
        <td align="left">
            pl: {{$form->text('data_amount_pl')}}&nbsp;&nbsp;
            ql: {{$form->text('data_amount_ql')}}&nbsp;&nbsp; 
            tc: {{$form->text('data_amount_tc')}}&nbsp;&nbsp;
            pc: {{$form->text('data_amount_pc')}}&nbsp;&nbsp; 
            ppc: {{$form->text('data_amount_ppc')}}&nbsp;&nbsp;
            hz: {{$form->text('data_amount_hz')}}&nbsp;&nbsp;
            合计: {{$form->text('data_amount')}}
        </td></tr>
    <tr>
         <td align="right">备案意见:</td>
        <td align="left">
            {{$form->textarea('data_23')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="4">市场助理核销素材</th>
    </tr>

    <tr>
        <td align="right">素材:</td>
        <td align="left">
            {{$form->select('material_id', ['0'=>'未提供','1'=>'不合格','2'=>'合格'])}}
        </td></tr>
    <tr>
        <td align="right" nowrap="true">核销素材意见:</td>
        <td align="left">
            {{$form->textarea('data_25')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">销售写促销总结</th>
    </tr>
    <tr>
        <td align="right">促销总结:</td>
        <td align="left">
            {{$form->textarea('data_32')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="2">总经理审核促销总结</th>
    </tr>
    <tr>
        <td align="right">审核促销总结意见:</td>
        <td align="left">
            {{$form->textarea('data_33')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="4">销售会计核销确认</th>
    </tr>

    <tr>
        <td align="right" nowrap="true">促销费用计算:</td>
        <td align="left">
            pl: {{$form->text('data_amount1_pl')}}&nbsp;&nbsp;
            ql: {{$form->text('data_amount1_ql')}}&nbsp;&nbsp; 
            tc: {{$form->text('data_amount1_tc')}}&nbsp;&nbsp;
            pc: {{$form->text('data_amount1_pc')}}&nbsp;&nbsp; 
            ppc: {{$form->text('data_amount1_ppc')}}&nbsp;&nbsp;
            hz: {{$form->text('data_amount1_hz')}}&nbsp;&nbsp;
            合计: {{$form->text('data_amount1')}}
        </td></tr>
    <tr>
        <td align="right">兑现:</td>
        <td align="left">
            {{$form->textarea('data_24')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="4">订单助理生成订单</th>
    </tr>
    <tr>
        <td align="right">兑现订单号:</td>
        <td align="left">
            {{$form->text('data_26')}}
        </td></tr>
    <tr>
        <td align="right">订单意见:</td>
        <td align="left">
            {{$form->textarea('data_27')}}
        </td>
    </tr>

    <tr>
        <th align="left" colspan="4">订单助理确认兑现</th>
    </tr>
    <tr>
        <td align="right">发货订单号:</td>
        <td align="left">
            {{$form->text('data_28')}}
        </td></tr>
    <tr>
        <td align="right">兑现意见:</td>
        <td align="left">
            {{$form->textarea('data_29')}}
        </td>
    </tr>

</table>

{{$form->hidden('id')}}

{{$form->js()}}