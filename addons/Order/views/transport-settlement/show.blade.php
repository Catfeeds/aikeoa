<div class="panel">

    <table class="table table-form">
        <tr>
            <th align="center" colspan="8">
                <h4>{{$setting['print_title']}}物流运费结算单</h4>
            </th>
        </tr>
        <tr>
            <th align="right">物流供应商</th>
            <td align="left">{{$logistics['name']}}</td>
            <th align="right">地址</th>
            <td align="left">{{$logistics['address']}}</td>
            <th align="right">业务电话</th>
            <td align="left">{{$logistics['business_phone']}}</td>
            <th align="right">财务电话</th>
            <td align="left">{{$logistics['finance_phone']}}</td>
        </tr>
        <tr>
            <td align="center" colspan="9">
                <div class="table-responsive">
                    <table class="table table-bordered b-t m-b-none table-hover">
                        <tr>
                            <th align="center">订单号</th>
                            <th align="left">客户</th>
                            <th align="center">实发件数</th>
                            <th align="center">实发吨位</th>
                            <th align="center">运价</th>
                            <th align="center">运费</th>
                            <th align="center">送货费</th>
                            <th align="center">运费金额</th>
                            <th align="center">赔偿金额</th>
                            <th align="center">应结金额</th>
                            <th align="center">实结金额</th>
                            <th align="center">实发时间</th>
                            <th align="center">收到回执时间</th>
                        </tr>
                        <?php
                            $total_money = 0;
                        ?>
                        @if($rows)
                        @foreach($rows as $row)
                        <?php
                            // 重量(吨)
                            $weight = $row->datas->sum('weight') / 1000;
                            // 运费
                            $freight_money = $row['freight_price'] * $weight;
                            $sum_money = $freight_money + $row['freight_money'] + $row['delivery_money'];
                            $sum_money = $sum_money - $row['compensate_money'];
                            $total_money += $sum_money;
                        ?>
                        <tr>
                            <td align="center">{{$row['number']}}</td>
                            <td align="left">{{$row['customer_name']}}</td>
                            <td align="right">{{$row->datas->sum('fact_amount')}}</td>
                            <td align="right">@number($weight, 2)</td>

                            <td align="right">{{$row['freight_price']}}</td>
                            <td align="right">@number($freight_money, 2)</td>
                            <td align="right">@number($row['freight_money'], 2)</td>
                            <td align="right">@number($row['delivery_money'], 2)</td>
                            <td align="right">@number($row['compensate_money'], 2)</td>
                            <td align="right">@number($sum_money, 2)</td>
                            <td align="right"></td>
                            <td align="center">@datetime($row['delivery_time'])</td>
                            <td align="center"></td>
                        </tr>
                        @endforeach
                        @endif
                        <tr>
                            <td align="center">合计</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right">@number($total_money, 2)</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left" colspan="9">
                大写金额：
            </td>
        </tr>
        <tr>
            <td align="center" colspan="9">
                物流商确认（以上无疑义）
                并请与我公司财务部门（028）38229888联系开具发票事宜
                我公司会在发票到达我司2个工作日办理，若未在2个工作日办理请电话总经理13890323001
            </td>
        </tr>
    </table>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-form">
            <tr>
                <td>
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <a target="_blank" href="{{url('print', ['id' => $settlement['id']])}}" class="btn btn-black"><i
                            class="fa fa-print"></i> 打印</a>
                </td>
            </tr>
        </table>
    </div>
</div>