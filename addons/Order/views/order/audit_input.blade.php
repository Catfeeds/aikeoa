<style type="text/css">
.step_box { display:none; }
</style>

<form id="auditform" name="auditform" action="{{url()}}" method="post">
<table class="table table-form m-b-none">

@if($flow['forms'])
@foreach($flow['forms'] as $k => $v)
<tr>
    <td align="right" width="15%">@if($v['required']==1)<span style="color:red;">*</span> @endif {{$v['title']}}</td>
    <td>
        {{eval('?>'.$v['text'])}}
    </td>
</tr>
@endforeach
@endif

<tr>
    <td align="right" width="15%"><span style="color:red;">*</span>审核意见</th>
    <td><textarea class="form-control input-sm" id="content" name="flow[content][text]"></textarea></td>
</tr>

 @if(Auth::user()->role->name == 'finance')
<tr>
    <td align="right">订单备注</td>
    <td><textarea class="form-control input-sm" id="description" name="order[description]">{{$order['description']}}</textarea></td>
</tr>
<tr>
    <td align="right">随货同行</td>
    <td><textarea class="form-control input-sm" id="goods" name="order[goods]">{{$order['goods']}}</textarea></td>
</tr>
 @endif
<tr>
    <td align="right">审核类型</td>
    <td align="left">
        @if($flow['flow_step_state']=='next')
            <label class="radio-inline" style="color:green;font-weight:bold;"><input type="radio" name="order[flow_step_state]" id="step_state_next" onclick="stepBox('next');" value="next" checked>正常</label>
        @endif
        <label class="radio-inline" style="color:orange;font-weight:bold;"><input type="radio" name="order[flow_step_state]" id="step_state_last" onclick="stepBox('last');" value="last">退回</label>
        <label class="radio-inline" style="color:red;font-weight:bold;"><input type="radio" name="order[flow_step_state]" id="step_state_deny" onclick="stepBox('deny');" value="deny">拒绝</label>

        @if($flow['flow_step_state']=='end')
            <label class="radio-inline" style="font-weight:bold;"><input type="radio" name="order[flow_step_state]" id="step_state_end" onclick="stepBox('end');" value="end" checked>结束</label>
        @endif
    </td>
</tr>

<tr>
    <td align="right">分支选项</td>
    <td align="left">

        <span id="step_box_{{$flow['flow_step_state']}}" class="step_box" style="display: @if($flow['flow_step_state'] == 'next' || $flow['flow_step_state'] == 'end') block @else none; @endif ">
            <select class="form-control input-sm" id="flow_step_id" name="order[{{$flow['flow_step_state']}}][flow_step_id]">
                @if($steps['next'])
                @foreach($steps['next'] as $k => $v)
                    <option value="{{$k}}" @if(($materielCount>0&&$k==14)||($materielCount<=0&&$k==4)) selected @endif >{{$v}}</option>
                @endforeach
                @endif
            </select>
        </span>

        <span id="step_box_last" class="step_box" style="display:none;">
            <select class="form-control input-sm" id="flow_step_id" name="order[last][flow_step_id]">
                @if($steps['last'])
                @foreach($steps['last'] as $k => $v)
                    <option value="{{$k}}">{{$v}}</option>
                @endforeach
                @endif
            </select>
        </span>

        <span id="step_box_deny" class="step_box" style="display:none;">
            无
        </span>

    </td>
</tr>

<tr>
    <td></td>
    <td>
        <div class="alert alert-warning m-b-xs" role="alert">
            审核必须输入审核信息，而且审核信息不能修改，请仔细检查后提交。
        </div>
        <label class="checkbox-inline">
            <input name="sms" id="sms" type="checkbox" checked="true" value="true"> 短信提醒
        </label>
        <input type="hidden" name="order[id]" id="order_id" value="{{$order['id']}}" />
    </td>
</tr>
</table>

</form>

<script type="text/javascript">
function stepBox(type)
{
    $('.step_box').css('display','none');
    $('#step_box_'+type).css('display','block');
}
</script>
