<table class="table m-b-none">
<thead>
    <tr>
        <th align="left" colspan="3">
            已读 <span class="badge bg-info">{{(int)$rows['total'][1]}}</span>
            &nbsp;&nbsp;
            未读 <span class="badge">{{(int)$rows['total'][0]}}</span>
        </th>
    </tr>
    <tr>
        <th align="left">
            阅读人
        </th>
         <th align="center">
            部门
        </th>
        <th align="center">
            时间
        </th>
    </tr>
</thead>

    @if($rows['data'])
    @foreach($rows['data'] as $row)
    <tr>
        <td align="left">
            {{$row['nickname']}}
        </td>
        <td align="center">
        {{$row['department_name']}}
        </td>
        <td align="center">
            @datetime($row['created_at'])
        </td>
    </tr>
     @endforeach @endif
</table>