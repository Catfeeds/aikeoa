<div class="panel">
    <form class="form-horizontal" method="post" action="{{url()}}" id="window-form" name="window-form">
        <div class="table-responsive">
            <table class="table table-form">
                <tr>
                    <td width="15%" align="right">所属客户</td>
                    <td align="left">
                        {{Dialog::user('customer', 'customer_id', '', 1, 0)}}
                    </td>
                </tr>
                <tr>
                    <td align="right">开始日期</td>
                    <td align="left">
                        <?php $m = date('Y-m-01'); ?>
                        <input class="form-control input-inline input-sm" data-toggle="date" type="text" name="start_at" id="start_at" placeholder="开始日期" value="{{$m}}">
                    </td>
                </tr>
                <tr>
                    <td align="right">结束日期</td>
                    <td align="left">
                        <input class="form-control input-inline input-sm" data-toggle="date" type="text" id="end_at" placeholder="开始日期" name="end_at" value="{{date('Y-m-d', strtotime("$m +1 month -1 day"))}}">
                    </td>
                </tr>
                <tr>
                    <td align="right">描述</td>
                    <td align="left">
                        <textarea class="form-control" name="description" id="description"></textarea>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
