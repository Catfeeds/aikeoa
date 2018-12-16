<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{{$title}}</title>
<link href="{{$asset_url}}/dist/app.min.css" rel="stylesheet">
</head>
<body>

<div class="container w-auto-xs m-t-lg">

	<div class="panel panel-danger">
		<div class="panel-heading">          
			<span class="text-lt"><i class="fa fa-exclamation-circle"></i> 错误提示</span>
		</div>
		<div class="panel-body">
			<h4>{{$data}}</h4>
		</div>
		<div class="panel-footer">
			<a href="javascript:history.back();" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> 返回上一页 </a>
			<a href="javascript:goHome();" class="btn btn-sm btn-default"><i class="fa fa-home"></i> 返回首页 </a>
		</div>
	</div>
	<div class="m-t text-center">
		<small class="text-muted">© {{date('Y')}} {{$version}}</small>
	</div>
</div>
<script>
function goHome() {
	top.location.href='{{URL::to("/")}}';
}
</script>
</body>
</html>