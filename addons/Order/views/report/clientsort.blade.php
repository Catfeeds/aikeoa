<div class="panel">

    <div class="wrapper b-b b-light">
        @if(Auth::user()->role->name != 'customer')
        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            @include('data/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
        @endif
    </div>

<table class="table">
<tr>
<th colspan="2"><strong>({{$month4}} + {{$month3}}) - ({{$month2}} + {{$month1}}) / ({{$month2}} + {{$month1}})</strong></th>
<th colspan="3"><strong>({{$month4}} + {{$month3}}) - ({{$month2}} + {{$month1}}) / ({{$month2}} + {{$month1}})</strong></th>
</tr>

{{:$keys = array_keys($money1)}}
@if($money2)
@foreach($money2 as $k => $v)

<tr>
    <td align="center" width="60"><strong style="@if($v < 0) color:red; @endif">{{$v*100}}%</strong></td>
    <td style="white-space:nowrap;" align="left">{{$rows['a'][$k]['nickname']}}</td>

    {{:$key = array_shift($keys)}}
    <td align="center" width="60"><strong style="@if($money1[$key] < 0) color:red; @endif">{{$money1[$key] *100}}%</strong></td>
    <td style="white-space:nowrap;" align="left">{{$rows['a'][$key]['nickname']}}</td>
    <td align="center" width="100"><a href="javascript:;" class="option" onclick="remind('{{$rows['a'][$key]['nickname']}}','{{$rows['a'][$key]['mobile']}}','{{$money1[$key] *100}}%');">提醒</a></td>
</tr>
@endforeach 
@endif

</table>

</div>

<script type="text/javascript">
function remind(nickname, mobile, percent) {
    var text = '盛华系统提醒：'+nickname+'，您最近两个月的销售下降了'+percent+'，请您关注，市场有任何困难均可与总经理崔迎联系：13890323001。';
    var data = {mobile:mobile,text:text};
    $.post('{{url()}}', data, function(res) {
        $.messager.alert('短信提醒',res.data,'info');
    },'json');
}
</script>
