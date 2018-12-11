<script type="text/javascript">
$(function() {
	$('#calendarcolor')
	.simplecolorpicker({picker: true})
	.simplecolorpicker('selectColor','{{$calendar["calendarcolor"]}}');
});
</script>

<form method="post" role="form" class="form-horizontal" id="myform" name="myform">

	<div class="form-group">
  		<label class="col-sm-2 control-label">日程名称</label>
  		<div class="col-sm-10">
    		<input type="text" class="form-control input-sm" id="displayname" name="displayname" value="{{$calendar['displayname']}}" />
  		</div>
	</div>

	<div class="form-group">
  		<label class="col-sm-2 control-label">日程描述</label>
  		<div class="col-sm-10">
  			<textarea rows="2" class="form-control input-sm" name="description">{{$calendar['description']}}</textarea>
  		</div>
	</div>

	<div class="form-group m-b-none">
  		<label class="col-sm-2 control-label">日程颜色</label>
  		<div class="col-sm-10">

  			<div class="input-group m-t-xs">
    			<select id="calendarcolor" name="calendarcolor">
					<option data-border="#a32929" value="#cc3333">#cc3333</option>
					<option data-border="#b1365f" value="#dd4477">#dd4477</option>
					<option data-border="#7a367a" value="#994499">#994499</option>
					<option data-border="#5229a3" value="#6633cc">#6633cc</option>
					<option data-border="#29527a" value="#336699">#336699</option>
					<option data-border="#2952a3" value="#3366cc">#3366cc</option>
					<option data-border="#1b887a" value="#22aa99">#22aa99</option>
					<option data-border="#28754e" value="#329262">#329262</option>
					<option data-border="#0d7813" value="#109618">#109618</option>
					<option data-border="#528800" value="#66aa00">#66aa00</option>
					<option data-border="#88880e" value="#aaaa11">#aaaa11</option>
					<option data-border="#ab8b00" value="#d6ae00">#d6ae00</option>
					<option data-border="#be6d00" value="#ee8800">#ee8800</option>
					<option data-border="#b1440e" value="#dd5511">#dd5511</option>
					<option data-border="#865a5a" value="#a87070">#a87070</option>
					<option data-border="#705770" value="#8c6d8c">#8c6d8c</option>
					<option data-border="#4e5d6c" value="#627487">#627487</option>
					<option data-border="#5a6986" value="#7083a8">#7083a8</option>
					<option data-border="#4a716c" value="#5c8d87">#5c8d87</option>
					<option data-border="#6e6e41" value="#898951">#898951</option>
					<option data-border="#8d6f47" value="#b08b59">#b08b59</option>
					<option data-border="#666666" value="#888888">#888888</option>
	        	</select>
  			</div>
  		</div>
	</div>
	<input type="hidden" id="id" name="id" value="{{$calendar['id']}}" />
</form>
