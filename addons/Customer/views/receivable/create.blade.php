<div class="panel">
    <form class="form-horizontal" method="post" action="{{url()}}" id="window-form" name="window-form">
        <div class="table-responsive">
            <table class="table table-form">
                <tr>
                    <td width="15%" align="right">所属客户</td>
                    <td align="left">
                        {{Dialog::user('customer','customer_id', old('customer_id', $row->customer_id), 0, 0)}}
                    </td>
                </tr>
                <tr>
                    <td align="right">回款日期</td>
                    <td align="left">
                        <input class="form-control input-inline input-sm" data-toggle="date" type="text" name="pay_date" id="pay_date" placeholder="回款日期" value="{{old('pay_date', format_date($row->pay_date))}}">
                    </td>
                </tr>
                <tr>
                    <td align="right">回款金额</td>
                    <td align="left">
                        <input class="form-control input-inline input-sm" type="text" id="pay_money" name="pay_money" value="{{old('pay_money', $row->pay_money)}}">
                    </td>
                </tr>
                <tr>
                    <td align="right">描述</td>
                    <td align="left">
                        <textarea class="form-control" name="description" id="description">{{old('description', $row->description)}}</textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" value="{{$row->id}}">
        </form>
    </div>
</div>
