<script type="text/javascript" src="{{$asset_url}}/js/grid.js"></script>
<style type="text/css">
@media screen and (max-width: 767px) {
    .table-responsive td, .table-responsive th {
        white-space: normal !important;
    }
}
</style>

<form method="post" id="myform" name="myform">

    <div class="panel">
        <div class="panel-heading text-base">
            <i class="fa fa-file-text"></i> 工作主题
        </div>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th width="15%" align="right">创建人</th>
                    <td width="35%">{{$form->user('created_by')}}</td>

                    <th width="15%" align="right">创建时间</th>
                    <td width="35%">@datetime($form->data['created_at'])</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="panel">

        <div class="panel-heading text-base">
            <i class="fa fa-list-alt"></i> 工作表单
        </div>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td style="overflow:hidden;border:0;background:url('{{$asset_url}}/images/form_sheetbg.png');">
                        <div style="width:960px;margin:0 auto;padding:15px;">
                            <div class="shadow">
                                <span class="z corner_41"></span>
                                <span class="y corner_12"></span>

                                <div style="margin:10px;" class="form-wrapper">

                                    <table class="table" width="100%">
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

                                    
                                    {{$form->grid('promotion_data', ['product_name', 'percent'])}}

                                    <table class="table" width="100%">

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
                                            <td align="right">直接销售建议:</td>
                                            <td align="left">
                                                {{$form->textarea('data_10')}}
                                            </td>
                                            <td align="right">单品备注:</td>
                                            <td align="left">
                                                {{$form->text('product_remark')}}
                                            </td>
                                        </tr>

                                    </table>

                                    <table class="table" width="100%">
                                        <tr>
                                            <th align="left" colspan="2">上级销售意见</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                意见内容：{{$form->textarea('data_34')}}
                                                &nbsp;处理时间：{{$form->auto('data_35')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">市场助理初审</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                初审意见：{{$form->text('data_20')}}
                                                &nbsp;处理时间：{{$form->auto('data_36')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th align="left" colspan="2">总经理批复</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                <p style="text-align:center;color:red;">注意：总经理批复内容为"确认"，为确认上述直接销售建议的内容。
                                                </p>
                                                <p>
                                                是否写促销总结：{{$form->select('data_31', ['0'=>'否','1'=>'是'])}}
                                                </p>
                                                批复意见：{{$form->textarea('data_21')}}
                                                &nbsp;处理时间：{{$form->auto('data_37')}}
                                            </td>
                                        </tr>
        
                                        <tr>
                                            <th align="left" colspan="2">市场助理备案</th>
                                        </tr>

                                        <tr>
                                            <td align="left" colspan="2">
                                                <p>预估费用：
                                                pl: {{$form->text('data_amount_pl')}}&nbsp;&nbsp; ql: {{$form->text('data_amount_ql')}}&nbsp;&nbsp; tc: {{$form->text('data_amount_tc')}}&nbsp;&nbsp;
                                                pc: {{$form->text('data_amount_pc')}}&nbsp;&nbsp; ppc: {{$form->text('data_amount_ppc')}}&nbsp;&nbsp;
                                                hz: {{$form->text('data_amount_hz')}}&nbsp;&nbsp; 合计: {{$form->text('data_amount')}}
                                                </p>
                                                备案意见：{{$form->textarea('data_23')}}
                                                &nbsp;处理时间：{{$form->auto('data_38')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">市场助理核销素材</th>
                                        </tr>

                                        <tr>
                                            <td align="left" colspan="2">
                                                <p>
                                                素材：{{$form->option('material_id', 'promotion.material')}}
                                                </p>
                                                核销意见：{{$form->textarea('data_25')}}
                                                &nbsp;处理时间：{{$form->auto('data_39')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">销售会计核销确认</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                <p>
                                                促销费用计算：pl: {{$form->text('data_amount1_pl')}}&nbsp;&nbsp; ql: {{$form->text('data_amount1_ql')}}&nbsp;&nbsp; tc: {{$form->text('data_amount1_tc')}}&nbsp;&nbsp;
                                                pc: {{$form->text('data_amount1_pc')}}&nbsp;&nbsp; ppc: {{$form->text('data_amount1_ppc')}}&nbsp;&nbsp;
                                                hz: {{$form->text('data_amount1_hz')}}&nbsp;&nbsp; 合计: {{$form->text('data_amount1')}}
                                                </p>
                                                兑现意见：{{$form->textarea('data_24')}}
                                                &nbsp;处理时间：{{$form->auto('data_40')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">订单助理生成订单</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                <p>
                                                兑现订单号：{{$form->text('data_26')}}
                                                </p>
                                                订单意见：{{$form->textarea('data_27')}}
                                                &nbsp;处理时间：{{$form->auto('data_41')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">订单助理确认兑现</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                <p>
                                                发货订单号：{{$form->text('data_28')}}
                                                </p>
                                                兑现意见：{{$form->textarea('data_29')}}
                                                &nbsp;处理时间：{{$form->auto('data_42')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">销售写促销总结</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                促销总结：{{$form->textarea('data_32')}}
                                                &nbsp;处理时间：{{$form->auto('data_43')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th align="left" colspan="2">总经理审核促销总结</th>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="2">
                                                总结意见：{{$form->textarea('data_33')}}
                                                &nbsp;处理时间：{{$form->auto('data_44')}}
                                            </td>
                                        </tr>

                                    </table>
                                    <!--
                                    {{$form->hidden('id')}}
                                    {{$form->referer()}}
                                    -->
                                    {{$form->footer()}}

                                </div>
                                <span class="z corner_34"></span>
                                <span class="y corner_23"></span>

                    </td>
                </tr>
            </table>
            </div>
            </div>

            <div class="panel">
                <div class="panel-heading text-base">
                    <i class="icon icon-paperclip"></i> 公共附件区
                </div>
                <div class="panel-body b-t">
                    @if($form->_option('attachment')) @include('attachment/create') @else @include('attachment/view') @endif
                </div>
            </div>

            @include('promotion/create/footer')

        </div>

</form>
{{$form->js()}}