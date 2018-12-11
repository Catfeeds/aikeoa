
<div class="panel">
<div class="table-responsive">
<table class="table table-form">
	<tr>
	    <th colspan="4" align="left">
	    	基本资料
		    <span class="pull-right">
		    	<a class="btn btn-xs btn-info" href="{{url('add',array('id'=>$customer['id']))}}"><i class="fa fa-pencil"></i> 编辑</a>
		    </span>
	    </th>
	</tr>

	<tr>
	    <td align="right" width="10%">客户名称</td>
	    <td width="40%">
	        {{$row['nickname']}}
	    </td>
	    <td align="right" width="10%">登录账号</td>
	    <td width="40%">
	        {{$row['username']}}
	    </td>
	</tr>

	<tr>
		<td align="right">客户代码</td>
		<td>{{$row['code']}}</td>
		<td align="right">所属公司</td>
		<td>{{option('customer.company', $row['company_id'])}}</td>
	</tr>

	<tr>
		<td align="right">客户类型</td>
		<td>
			@if($types) @foreach($types as $k => $v)
				@if($row['post'] == $v['id']) {{$v['name']}} @endif
			@endforeach @endif
		</td>
	    <td align="right">负责人</td>
	    <td>{{$row['fullname']}}</td>
	</tr>

	<tr>
	    <td align="right">负责人手机</td>
	    <td>{{$row['mobile']}}</td>
	    <td align="right">邮箱地址</td>
	    <td align="left">
	        {{$row['email']}}
	    </td>
	</tr>

	<tr>
	    <td align="right">公司电话</td>
	    <td>{{$row['tel']}}</td>
	    <td align="right">公司传真</td>
	    <td>{{$row['fax']}}</td>
	</tr>

	<tr>
	    <td align="right">公司地址</td>
	    <td>
	        {{$row['address']}}
	    </td>

		<td align="right">收货地址</td>
	    <td>{{$customer['warehouse_address']}}</td>
	</tr>

	<tr>
	    <th colspan="4" align="left">客户资料</th>
	</tr>

	<tr>
	    <td align="right">销售负责人</td>
	    <td>
	        {{$customer['saleman_person']}}
	    </td>
	    
	    <td align="right">销售负责人手机</td>
	    <td>
	        {{$customer['saleman_phone']}}
	    </td>
	</tr>

	<tr>
	    <td align="right">订单负责人</td>
	    <td>
	        {{$customer['order_person']}}
	    </td>

	    <td align="right">订单负责人手机</td>
	    <td>
	        {{$customer['order_phone']}}
	    </td>
	</tr>

	<tr>

	    <td align="right">财务负责人</td>
	    <td>
	        {{$customer['finance_person']}}
	    </td>

	    <td align="right">财务负责人手机</td>
	    <td>
	        {{$customer['finance_phone']}}
	    </td>
	</tr>

	<tr>
	    <td align="right">后勤负责人</td>
	    <td>
	        {{$customer['logistics_person']}}
	    </td>


	    <td align="right">后勤负责人手机</td>
	    <td>
	        {{$customer['logistics_phone']}}
	    </td>

	</tr>

	<tr>
	    <td align="right">收货人</td>
	    <td>{{$customer['warehouse_contact']}}</td>

	    <td align="right">收货人手机</td>
	    <td>{{$customer['warehouse_mobile']}}</td>
	</tr>

	<tr>
	    <td align="right">收货电话</td>
	    <td>{{$customer['warehouse_tel']}}</td>

	    <td align="right">客户地区</td>
	    <td align="left">
	    	{{get_region($row['province_id'])}}省
	    	&nbsp;
	    	{{get_region($row['city_id'])}}市
	    	&nbsp;
	    	{{get_region($row['county_id'])}}县
	    </td>
	</tr>

	<tr>
	    <td align="right">绑定IP</td>
	    <td align="left">
	        {{$row['autd_ip']}}
	    </td>

	    <td align="right">安全密钥</td>
	    <td align="left">
	        <strong id="secret">{{$row['autd_secret']}}</strong>
	    </td>
	</tr>

	<tr>
		<td align="right">其他选项</td>
	    <td>
	         @if($row['id'] > 0 && $row['status'] == 0) 禁用账户 @endif
	        &nbsp;&nbsp;
	         @if($row['autd_otp']==1) 安全登录 @endif
	    </td>
	    <td></td>
	    <td></td>
	</tr>
</table>
</div>
</div>

<div class="panel">
<table class="table table-form">
	<tr>
	    <th colspan="4" align="left">
	    	银行资料
		   	<span class="pull-right">
		    	<a class="btn btn-xs btn-info" href="{{url('bank/add',array('customer_id'=>$row['id']))}}"><i class="icon icon-plus"></i> 新增</a>
		    </sapn>
	    </th>
	</tr>
	<tr>
	    <th align="left">单位</th>
	    <th align="left" width="200">税号</th>
	    <th align="center" width="100">状态</th>
	    <th align="center" width="150"></th>
	</tr>
	 @if($client_bank) @foreach($client_bank as $k => $bank)
	<tr>
	    <td>{{$bank['tax_name']}}</td>
	    <td>{{$bank['tax_number']}}</td>
	    <td align="center"> @if($bank['status']==1) 启用 @else 禁用 @endif </td>
	    <td align="center">
	    	<a class="btn btn-xs btn-info" href="{{url('bank/edit',array('customer_id'=>$row['id'],'id'=>$bank['id']))}}">编辑</a>
	    	<a class="btn btn-xs btn-info" onclick="app.confirm('{{url('bank/delete',array('customer_id'=>$row['id'],'id'=>$bank['id']))}}','确定要删除吗？');" href="javascript:;">删除</a>
	    </td>
	</tr>
     @endforeach @endif
</table>
</div>
