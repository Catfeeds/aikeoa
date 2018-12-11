<div class="table-responsive">
<table class="table m-b-none">
<thead>
    <tr>
        <th align="center">序号</th>
        <th align="left">步骤</th>
        <th align="center">办理人</th>
        <th align="center">办理类型</th>
        <th align="center">办理效率</th>
        <th align="center">办理时间</th>
        <th align="center">办理意见</th>
    </tr>
</thead>
    @foreach($rows as $i => $row)
    <tr>
        <td align="center">
            {{$i + 1}}
        </td>
        <td align="left">
            {{$row['step_name']}}
        </td>
        <td align="center">
            {{get_user($row[user_id],'nickname')}}
        </td>
        <td align="center">
            @if($row[step_status] == 'draft')
                <span class="label label-danger">待审</span>
            @elseif($row[step_status] == 'back')
                <span class="label label-warning">退回</span>
            @else
                <span class="label label-success">审批</span>
            @endif

        </td>
        <td align="center">
        <?php 
            $start = $row['created_at'];
            if ($i) {
                $s = Carbon\Carbon::createFromTimeStamp($start);
                $e = Carbon\Carbon::createFromTimeStamp($end);
                echo $s->diffInHours($e).'小时';
            } else {
                echo '';
            }
            $end = $start;
        ?>
        </td>
        <td align="center">
            @datetime($row[created_at])
        </td>
        <td align="left">
            {{$row[description]}}
        </td>
    </tr>
@endforeach
</table>
</div>