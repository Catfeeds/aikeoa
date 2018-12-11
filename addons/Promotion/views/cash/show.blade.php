<div class="panel">

    <form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
        <div class="table-responsive">
            <table class="table table-form">
                <tr>
                    <td width="15%" align="right">所属促销</td>
                    <td align="left">
                        {{$row->promotion->number}}
                    </td>
                </tr>

                <tr>
                    <td align="right">兑现日期</td>
                    <td align="left">
                        @date($row->date)
                    </td>
                </tr>

                <tr>
                    <td align="right">兑现金额</td>
                    <td align="left">
                        {{$row->money}}
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
