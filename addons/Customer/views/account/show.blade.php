        <div class="table-responsive">
            <table class="table table-form b-b">
                <tr>
                    <td width="15%" align="right">客户名称</td>
                    <td align="left">
                        {{$account->customer->user->nickname}}
                    </td>
                    <td width="15%" align="right">客户代码</td>
                    <td align="left">
                        {{$account->customer->user->username}}
                    </td>
                </tr>

                <tr>
                    <td align="right">单据编号</td>
                    <td align="left">
                        {{$account->sn}}
                    </td>
                    <td align="right">审核状态</td>
                    <td align="left">
                        @if($account->status == 1)
                            已审核
                        @else
                            等待客户审核
                        @endif
                    </td>
                </tr>

                <tr>
                    <td align="right">开始日期</td>
                    <td align="left">
                        {{$account->start_at}}
                    </td>
                    <td align="right">结束日期</td>
                    <td align="left">
                        {{$account->end_at}}
                    </td>
                </tr>
            </table>


        <table class="table b-t m-b-none table-hover">
        <tr>
            <th align="center">单据日期</th>
            <th align="left">单据编号</th>
            <th align="left">摘要</th>
            <th align="right">本期应收金额</th>
            <th align="right">本期收回金额</th>
            <th align="right">余额</th>
        </tr>

        @foreach($rows as $row)

        <tr>
            <td align="center">
                @if($row['date'] == '0000-00-00')
                @else
                {{$row['date']}}
                @endif
            </td>
            <td align="left">{{$row['sn']}}</td>
            <td align="left">{{$row['digest']}}</td>
            <td align="right">{{$row['jmoney']}}</td>
            <td align="right">{{$row['dmoney']}}</td>
            <td align="right">{{$row['balance']}}</td>
        </tr>
        @endforeach
        </table>

        </div>