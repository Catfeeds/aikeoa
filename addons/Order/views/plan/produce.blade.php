<style type="text/css">
.mini-textarea {border-width:0px; padding:0px;}
table.list td {border-left:1px solid #D8E8F6;}
table.list th {border-left:1px solid #D6E4F1;}
</style>

<table class="tlist">
    <tr>
        <td align="left">
            <form id="myquery" name="myquery" action="{{url()}}" method="get">
            @include('plan/select')
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
            </form>
        </td>
    </tr>
</table>

<style type="text/css">
    th, td {white-space:nowrap;}
</style>

<form id="myform" name="myform" onsubmit="return false" method="post">
<table class="list tab">
    <tr class="odd">
        <th>产品名称</th>
        <th align="center">当前库存</th>
        <th align="center">需求计划汇总</th>
        <th align="center">需求总差</th>
        <th align="center">可发差量</th>
         @if($date) @foreach($date as $v)
            <th class="odd center" colspan="3">{{$v}}</th>
         @endforeach @endif
    </tr>

    <tr>
        <th colspan="5"></th>
        @if($date)
        @foreach($date as $v)
            <td colspan="3" align="center">

                {{:$main = $produce['main'][$v]}}

                @if(($main['state'] == false && Auth::user()->role_id == 28) || is_admin())
                    <button class="btn btn-primary btn-sm" onclick="save({{$main['id']}});">提交</button>
                @endif

                @if(($main['state'] == false && Auth::user()->role_id == 28) || is_admin())
                    <button class="btn btn-default btn-sm" onclick="state({{$main['id']}},1);">审核</button>
                @endif

                @if(($main['state'] == true && Auth::user()->role_id == 22) || is_admin())
                    <button class="btn btn-default btn-sm" onclick="state({{$main['id']}},0);">反审</button>
                @endif
            </td>
        @endforeach 
        @endif
    </tr>

    <tr>
        <th colspan="4"></th>
        <td align="center">打款 - 库存</td>
         @if($date) @foreach($date as $v)
            <td align="center">OP计划</td>
            <td align="center">PM计划</td>
            <td align="center">OM入库</td>
         @endforeach @endif
    </tr>
      
    <tr>
        <th align="left">数据汇总</th>
        <td align="right"><strong>{{$products['total']}}</strong></td>

        <td align="right">{{$order['total']}}</td>

        <td align="right">
            {{:$k = $products['total'] - $order['total']}}
            <span style="color: @if($k > 0) red @else green @endif ;">{{$diff_total}}</span>
        </td>
        <td align="right">
            {{:$k = (int)$plan['pay']['total'] - $products['total']}}
            <strong style="color: @if($k > 0) red @else green @endif ;">{{$k}}</strong>
        </td>
        @if($date)
        @foreach($date as $v)
            <th align="right">{{(int)$plan[$v]['total']}}</th>
            <th align="right">{{(int)$produce[$v]['total']}}</th>
            <th align="right">{{(int)$warehouse[$v]['total']}}</th>
        @endforeach 
        @endif

    </tr>

    {{:array_pop($products)}}
    @if($products)
    @foreach($products as $k => $v)
    <tr>
        <td align="top">{{$v['name']}} @if($v['spec'])  - {{$v['spec']}} @endif </td>
        <td align="right"><strong>{{$inventory[$k]}}</strong></td>

        <td align="right">{{(int)$order[$k]['total']}}</td>

        <td align="right">
            {{:$order_amount = (int)$inventory[$k] - $order[$k]['total']}}
            <span style="color: @if($order_amount < 0) red @else green @endif ;">{{$order_amount}}</span>
        </td>

        <td align="right">
            {{:$pay_amount = (int)$plan['pay'][$k] - $inventory[$k]}}
            <strong style="color: @if($pay_amount > 0) red @else green @endif ;">{{$pay_amount}}</strong>
        </td>

        @if($date)
        @foreach($date as $v2)
        <td align="right" onclick="remark('{{$v2}}-{{$v['name']}}', this,' @if(in_array(Auth::user()->role_id, $remarkRole)) edit @else view @endif ');" id="{{$k}},{{strtotime($v2)}}" title="时间: {{$v2}}, 产品: {{$v['name']}}[{{$v['spec']}}]">
            <input type="hidden" value="{{$remark[$k][$v2]}}">
             @if($remark[$k][$v2]) <a href="javascript:;" title="{{$remark[$k][$v2]}}"><img style="vertical-align:middle;" src="{{$asset_url}}/images/icons/02461219.gif" /></a> @endif
            
            {{(int)$plan[$k][$v2]}}

            <span class="remark-customer" style="display:none;">
                @if($customer[$k][$v2])
                @foreach($customer[$k][$v2] as $k3 => $v3)
                <p style="padding-bottom:6px;border-bottom:solid 1px #ccc;">
                    <span style="float:right;">{{$v3}}</span>{{$customer['name'][$k3]}}
                </p>
                @endforeach 
                @endif
            </span>
        </td>

        <td style="color:green;" align="right" title="时间: {{$v2}}, 产品: {{$v['name']}}[{{$v['spec']}}]">
            @if(($produce['main'][$v2]['state']==FALSE && Auth::user()->role_id == 28) || is_admin())
                <input type="text" class="input-text" size="5" name="produce[{{$produce['main'][$v2]['id']}}][{{$k}}]" id="produce_{{$k}}_{{$v2}}" value="{{$produce[$k][$v2]}}"/>
            @else
                {{$produce[$k][$v2]}}
            @endif
        </td>

        <td align="right">
            {{(int)$warehouse[$k][$v2]}}
        </td>
        
        @endforeach
        @endif
    </tr>
    @endforeach 
    @endif
</table>

<script type="text/javascript">
function save(produce_id) 
{
    var myform = $('#myform').serialize();
    $.post("{{url('produce_add')}}?produce_id="+produce_id, myform, function(r) {
        $.messager.alert('生产计划',r.data,null,function() {
            location.reload();
        });
    });
}

function state(produce_id, state) 
{
    $.post("{{url('produce_state')}}",{produce_id:produce_id,state:state}, function(r) {
        if(r.status) {
            $.messager.alert('生产计划',r.data,null,function() {
                location.reload();
            });
        }
    },'JSON');
}

function remark(title, obj, type) 
{
    var input = obj.getElementsByTagName('input')[0];
    var span = obj.getElementsByTagName('span')[0];
    var textarea = span.innerHTML;

    if(type == 'edit') {
        textarea += '<textarea class="input-text" autocomplete="off" style="width:260px;height:80px;">'+input.value+'</textarea>';
    } else {
        textarea += '<br />'+input.value.replace(/\n/g,"<br />")+'<br /><br />';
    }
    
    var title = '计划备注';
    var v = obj.id.split(',');
    $.messager.alert(title, textarea,'',function(r) {

        if(type == 'edit') {
            var text = $('.textarea').val();
            var query = {'value':text,'product_id':v[0],'add_time':v[1]};
            $.post("{{url()}}", query, function(r) {
                input.value = text;
            });
        }
    });
}

$(function() {
    var cols = 4;
    var obj = $("input");
    var num = $("input").length;
    $("input").bind("keydown",function(event)
    {
        var key = event.keyCode;
        var n = obj.index(this);
        switch(key)
        {
        case 37://左
        if(n > 0) {
            obj.eq(n-1).focus();
        }
        break;
        case 38://上
        if(n >= cols) {
            obj.eq(n-cols).focus();
        }
        break;
        case 39://右
        if(n < num-1) {
            obj.eq(n+1).focus();
        }
        break;
        case 40://下
        if((n+cols) < num) {
            obj.eq(n+cols).focus();
        }
        break;
           //default: return false;
       }
    });
});
</script>
