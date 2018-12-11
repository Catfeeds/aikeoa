<table class="table m-b-none">
<tr>
    <th colspan="2"><strong>({{$month4}} + {{$month3}}) - ({{$month2}} + {{$month1}}) / ({{$month2}} + {{$month1}})</strong></th>
    <th colspan="2"><strong>({{$month4}} + {{$month3}}) - ({{$month2}} + {{$month1}}) / ({{$month2}} + {{$month1}})</strong></th>
</tr>

@foreach($rows['a'] as $k => $v)
<tr>
    <td align="center" width="20"><strong style="@if($v[1] < 0) color:red; @endif">{{$v[1] * 100}}%</strong></td>
    <td style="white-space:nowrap;" align="left">{{$v[2]}}<span style="color:#999;">@if($v[3]) - {{$v[3]}} @endif</span></td>
    <td align="center" width="20"><strong style="@if($rows['b'][$k][1] < 0) color:red; @endif">{{$rows['b'][$k][1] *100}}%</strong></td>
    <td style="white-space:nowrap;" align="left">{{$rows['b'][$k][2]}}<span style="color:#999;">@if($rows['b'][$k][3]) - {{$rows['b'][$k][3]}} @endif</span></td>
</tr>
@endforeach
</table>
