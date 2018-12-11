<div class="panel">

    <form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
        <div class="table-responsive">
            <table class="table table-form">
                <tr>
                    <td width="15%" align="right">所属客户</td>
                    <td align="left">
                        {{$row->customer->user->nickname}}
                    </td>
                </tr>

                <tr>
                    <td align="right">回款日期</td>
                    <td align="left">
                        @date($row->pay_date)
                    </td>
                </tr>

                <tr>
                    <td align="right">回款金额</td>
                    <td align="left">
                        {{$row->pay_money}}
                    </td>
                </tr>

                <tr>
                    <td align="right">描述</td>
                    <td align="left">
                        {{$row->description}}
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
