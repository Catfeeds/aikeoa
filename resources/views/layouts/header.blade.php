<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{$setting['title']}} - Powered By {{$setting['powered']}}</title>
    <script type="text/javascript" src="{{$public_url}}/common"></script>
    <link href="{{$asset_url}}/dist/app.min.css" rel="stylesheet" type="text/css" />
    <script src="{{$asset_url}}/dist/app.min.js" type="text/javascript"></script>
    <script src="{{$asset_url}}/vendor/layer/layer.js" type="text/javascript"></script>
    <script src="{{$asset_url}}/vendor/datepicker/datepicker.js"></script>

    <!--[if lt IE 8]>
    <script type="text/javascript">
        window.location.href = "{{url("index/api/unsupportedBrowser")}}";
    </script>
    <![endif]-->

    <!--[if lt IE 9]>
        <script src="{{$asset_url}}/libs/html5shiv.min.js"></script>
        <script src="{{$asset_url}}/libs/respond.min.js"></script>
    <![endif]-->

</head>
<body class="frame-{{auth()->user()->theme ?: 'blue'}}">