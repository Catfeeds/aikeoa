<style type="text/css">
table.list td {border-left:1px solid #D8E8F6;}
table.list th {border-left:1px solid #D6E4F1;}
td,th {white-space:nowrap;}
</style>

<table class="tlist">
    <tr>
        <td align="left">
            <form id="myform" name="myform" action="{{url()}}" method="get">
              @include('plan/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
        </td>
    </tr>
</table>

<table class="list tab">
    <tr>
        <th style="white-space:nowrap;" class="odd left">产品名称</th>
        <th style="white-space:nowrap;" class="odd center">实时库存</th>
         @if($date) @foreach($date as $v)
            <th style="white-space:nowrap;" colspan="2" class="odd center">{{$v}}</th>
         @endforeach @endif
        <th style="white-space:nowrap;" colspan="2" class="odd center">时间段汇总</th>
    </tr>

    <tr>
        <th colspan="2"></th>
        <td align="center">PO发货</td>
        <td align="center">OM入库</td>
         @if($date) @foreach($date as $v)
            <td align="center">PO发货</td>
            <td align="center">OM入库</td>
         @endforeach @endif
    </tr>

    <tr>
        <th style="white-space:nowrap;" align="center">合计数据</th>
        <td align="right"><strong>{{$products['total']}}</strong></td>

         @if($date) @foreach($date as $v)
            <th align="right">{{$delivery[$v]['total']}}</th>
            <th align="right">{{$res[$v]['total']}}</th>
         @endforeach @endif

        <td align="right"><strong>{{$delivery['total']}}</strong></td>
        <td align="right"><strong>{{$res['total']}}</strong></td>
    </tr>

    {{:unset($products['total'])}}

     @if($products) @foreach($products as $k => $v)
    <tr>
        <td align="top">{{$v['name']}} @if($v['spec']) {{$v['spec']}} @endif </td>
        <td align="right"><strong>{{$inventory[$k]}}</strong></td>

         @if($date) @foreach($date as $v2)
            <td onclick="remark('{{$v2}}-{{$v['name']}}',this);" align="right" title="时间: {{$v2}}, 产品: {{$v['name']}}[{{$v['spec']}}]">
                
                {{(int)$delivery[$v['id']][$v2]}}

                <span class="remark-customer" style="display:none;">
                 @if($customer[$k][$v2]) @foreach($customer[$k][$v2] as $k3 => $v3)
                    <p style="padding-bottom:6px;border-bottom:solid 1px #ccc;">
                        <span style="float:right;">{{$v3}}</span>{{$customer['name'][$k3]}}
                    </p>
                 @endforeach @endif
                </span>

            </td>
            <td align="right" title="时间: {{$v2}}, 产品: {{$v['name']}}[{{$v['spec']}}]">{{(int)$res[$v['id']][$v2]}}</td>
         @endforeach @endif

        <td align="right">{{(int)$delivery[$v['id']]['total']}}</td>
        <td align="right"><strong>{{(int)$res[$v['id']]['total']}}</strong></td>
    
    </tr>
     @endforeach @endif
</table>

<script type="text/javascript">
function remark(title, obj) {
    var input = obj.getElementsByTagName('input')[0];
    var span = obj.getElementsByTagName('span')[0];
    var textarea = span.innerHTML;

    var title = '发货计划';
    var v = obj.id.split(',');
    $.messager.alert(title, textarea,'',function(r) {

    });
}
</script>
