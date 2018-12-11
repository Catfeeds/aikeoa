<div class="panel">

    <div class="panel-body">

        <form class="form-horizontal" method="post" action="{{url('store')}}" id="myform" name="myform">

			<div class="form-group">
            	<label class="col-sm-2 control-label">缩略图宽高</label>
            	<div class="col-sm-10">
					<input class="form-control input-inline input-sm" name="data[upload_max]" type="text" value="{{$setting['upload_max']}}"> x
					<input class="form-control input-inline input-sm" name="data[upload_max]" type="text" value="{{$setting['upload_max']}}"> px
				</div>
			</div>

            <div class="form-group">
                <label class="col-sm-2 control-label">水印功能</label>
                <div class="col-sm-10">
                	<label class="i-checks i-checks-sm"><input name="data[image_mark]" class="image_mark" type="radio" value=""@if($setting['image_mark'] == '') checked @endif><i></i> 关闭水印</label>
                	<label class="i-checks i-checks-sm"><input name="data[image_mark]" class="image_mark" type="radio" value="image"@if($setting['image_mark'] == 'image') checked @endif><i></i> 图片水印</label>
                	<label class="i-checks i-checks-sm"><input name="data[image_mark]" class="image_mark" type="radio" value="text"@if($setting['image_mark'] == 'text') checked @endif><i></i> 文字水印</label>
                </div>
            </div>

            <div id="1212">

	            <div id="mark-image" class="mark-class" style="display:none;">

		            <div class="form-group">
		                <label class="col-sm-2 control-label">1212</label>
		                <div class="col-sm-10">
		                    <input class="form-control input-sm" type="text" id="mail_port" name="data[mail_port]" value="{{$setting['mail_port']}}">
		                </div>
		            </div>

	            </div>

	            <div id="mark-text" class="mark-class" style="display:none;">

		            <div class="form-group">
		                <label class="col-sm-2 control-label">水印透明度</label>
		                <div class="col-sm-10">
		                    <input class="form-control input-inline input-sm" name="data[image_mark_alpha]" type="text" value="{{$setting['image_mark_alpha']}}">
							<span class="help-inline">限JPG图片，填写范围(0-100)</span>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="col-sm-2 control-label">水印图片</label>
		                <div class="col-sm-10">
		                    <input class="form-control input-inline input-sm" name="data[image_mark_file]" type="text" value="{{$setting['image_mark_file']}}">
							<span class="help-inline">PNG格式图片，水印图片目录：/extensions/watermark/</span>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="col-sm-2 control-label">水印字体</label>
		                <div class="col-sm-10">
		                    <select id="image_mark_font" class="form-control input-inline input-sm" name="data[image_mark_font]">
								@foreach($fonts as $font)
									<option @if($setting['image_mark_font'] == $font)selected="selected"@endif value="{{$font}}">{{$font}}</option>
								@endforeach
							</select>
							<span class="help-inline">存放在 public/fonts 目录下的 TTF 字体文件，支持中文字体。如使用中文 TTF 字体请使用包含完整中文汉字的字体文件</span>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="col-sm-2 control-label">水印文字</label>
		                <div class="col-sm-10">
		                    <input class="form-control input-inline input-sm" name="data[image_mark_text]" type="text" value="{{$setting['image_mark_text']}}">
							<span class="help-inline"></span>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="col-sm-2 control-label">水印文字大小</label>
		                <div class="col-sm-10">
		                    <input class="form-control input-inline input-sm" name="data[image_mark_text_size]" type="text" value="{{$setting['image_mark_text_size']}}">
							<span class="help-inline">单位像素，默认14</span>
		                </div>
		            </div>


	           	</div>


	<tr class="x-line">
		<td align="right">水印图片</td>
		<td align="left">
			<input class="input-text" style="width:200px" name="data[image_mark_file]" type="text" value="{{$setting['image_mark_file']}}">
			<span class="help-inline">PNG格式图片，水印图片目录：/extensions/watermark/</span>
		</td>
	</tr>

	<tr class="x-line">
		<td align="right">水印字体</td>
		<td align="left">
			<select id="image_mark_font" name="data[image_mark_font]">
				@foreach($fonts as $font)
					<option @if($setting['image_mark_font'] == $font)selected="selected"@endif value="{{$font}}">{{$font}}</option>
				@endforeach
			</select>
			<span class="help-inline">存放在 public/fonts 目录下的 TTF 字体文件，支持中文字体。如使用中文 TTF 字体请使用包含完整中文汉字的字体文件</span>
		</td>
	</tr>

	<tr class="x-line">
		<td align="right">水印文字</td>
		<td align="left">
			<input class="input-text" style="width:200px" name="data[image_mark_text]" type="text" value="{{$setting['image_mark_text']}}">
			<span class="help-inline"></span>
		</td>
	</tr>

	<tr class="x-line">
		<td align="right">水印文字大小</td>
		<td align="left">
			<input class="input-text" style="width:50px" name="data[image_mark_text_size]" type="text" value="{{$setting['image_mark_text_size']}}">
			<span class="help-inline">单位像素，默认14</span>
		</td>
	</tr>

	<tr class="x-line">
		<td align="right">水印位置</td>
		<td align="left">
			<div>
				<input name="data[image_mark_pos]" id="mark_pos_1" type="radio" value="1"@if($setting['image_mark_pos'] == 1) checked="checked"@endif><label for="mark_pos_1"> 顶左</label>
				<input name="data[image_mark_pos]" id="mark_pos_2" type="radio" value="2"@if($setting['image_mark_pos'] == 2) checked="checked"@endif><label for="mark_pos_2"> 顶中</label>
				<input name="data[image_mark_pos]" id="mark_pos_3" type="radio" value="3"@if($setting['image_mark_pos'] == 3) checked="checked"@endif><label for="mark_pos_3"> 顶右</label>
			</div>
			<div>
				<input name="data[image_mark_pos]" id="mark_pos_4" type="radio" value="1"@if($setting['image_mark_pos'] == 4) checked="checked"@endif><label for="mark_pos_4"> 中左</label>
				<input name="data[image_mark_pos]" id="mark_pos_5" type="radio" value="2"@if($setting['image_mark_pos'] == 5) checked="checked"@endif><label for="mark_pos_5"> 中中</label>
				<input name="data[image_mark_pos]" id="mark_pos_6" type="radio" value="3"@if($setting['image_mark_pos'] == 6) checked="checked"@endif><label for="mark_pos_6"> 中右</label>
			</div>
			<div>
				<input name="data[image_mark_pos]" id="mark_pos_7" type="radio" value="7"@if($setting['image_mark_pos'] == 7) checked="checked"@endif><label for="mark_pos_7"> 底左</label>
				<input name="data[image_mark_pos]" id="mark_pos_8" type="radio" value="8"@if($setting['image_mark_pos'] == 8) checked="checked"@endif><label for="mark_pos_8"> 底中</label>
				<input name="data[image_mark_pos]" id="mark_pos_9" type="radio" value="9"@if($setting['image_mark_pos'] == 9) checked="checked"@endif><label for="mark_pos_9"> 底右</label>
			</div>
			<span class="help-inline"></span>
		</td>
	</tr>


	        </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">发信人邮件地址</label>
                <div class="col-sm-10">
                    <input class="form-control input-sm" type="text" id="mail_from" name="data[mail_from]" value="{{$setting['mail_from']}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP验证用户名</label>
                <div class="col-sm-10">
                    <input class="form-control input-sm" type="text" id="mail_username" name="data[mail_username]" value="{{$setting['mail_username']}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP验证密码</label>
                <div class="col-sm-10">
                    <input class="form-control input-sm" type="text" id="mail_password" name="data[mail_password]" value="{{$setting['mail_password']}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <label class="i-checks i-checks-sm"><input name="data[mail_encryption]" id="mail_encryption" type="checkbox" value="ssl"@if($setting['mail_encryption'] == 'ssl') checked @endif><i></i> 使用 SSL</label>
                    <span class="help-inline"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button type="submit" class="btn btn-info">提交</button>
                    <a href="{{url('mail_test')}}" class="btn btn-danger">测试邮件</a>
                </div>
            </div>

        </form>
    </div>
</div>

<input name="tab" type="hidden" value="{{$_action}}">
<button type="button" onclick="history.back();" class="btn btn-default">返回</button>
<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>

</form>

<script type="text/javascript">
$(function() {
    $('.image_mark').on('click', function() {

        var checked = $(this).prop('checked');
        if(checked) {
        	$('.mark-class').hide();
        	var val = $(this).val();
        	if(val) {
        		$('#mark-'+val).show();
        	}
        }
    });
})
</script>
