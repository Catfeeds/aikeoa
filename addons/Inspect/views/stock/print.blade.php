<table class="table" style="width:1024px;height:768px;margin:20px auto;border: 1px solid #ddd;">

    <tr>
        <td align="left" style="vertical-align:middle;">
            客户：{{$main['company_name']}}，客户销售：{{get_user($main['add_user_id'], 'nickname', false)}}
        </td>
    </tr>

    <tr>
        <td align="left" style="vertical-align:middle;">
            问题说明：{{$main['remark']}}
        </td>
    </tr>

    <tr>
        <td align="left" style="height:600px;vertical-align:middle;">

            <?php $i = 1; ?>
            @if($attachments['view'])
            @foreach($attachments['view'] as $v)

                @if($i < 2)

                    @if($i == 0)
                        <div style="float:left;text-align:center;" width="30%"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" height="550" width="309" style="margin-left:15px;" /><br>陈列照片{{$i}}</div>
                    @else
                        <div style="float:left;text-align:center;" width="30%"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" height="550" width="309" style="margin-left:25px;margin-right:25px;" /><br>陈列照片{{$i}}</div>
                    @endif

                    <?php $i++; ?>

                @endif
                
            @endforeach
            @endif
        </td>
    </tr>

    <tr>
        <td align="left" style="vertical-align:middle;">
            <table class="table table-bordered m-b-none">
                <tr>
                    <th align="center">产品名称</th>
                    <th align="center" title="正常数量">总数量</th>
                    <th align="center" title="问题数量">超3个月生产日期数量</th>
                    <th align="center">上次发货数量</th>
                    <th align="center">上次发货时间</th>
                    <th align="center">说明</th>
                    <th align="center">产品ID</th>
                </tr>
                @foreach($rows as $v)
                @if($v['name'])
                <tr>
                    <td align="left">{{$v['name']}} [{{$v['spec']}}]</td>
                    <td align="right">{{$v['amount']}}</td>
                    <td align="right">{{$v['amount2']}}</td>
                    <td align="right">{{$orderDatas[$v['product_id']]['fact_amount']}}</td>
                    <td align="center">@datetime($orderDatas[$v['product_id']]['delivery_time'])</td>
                    <td align="right">{{$v['remark']}}</td>
                    <td align="center">{{$v['product_id']}}</td>
                </tr>
                @endif
                @endforeach
            </table>
        </td>
    </tr>

</table>