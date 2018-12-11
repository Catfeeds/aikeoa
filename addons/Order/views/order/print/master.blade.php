<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{$setting['print_title']}}</title>
<link rel="stylesheet" href="{{$asset_url}}/dist/app.min.css" type="text/css" />
<script src="{{$asset_url}}/dist/app.min.js"></script>
<script src="{{$asset_url}}/vendor/datepicker/datepicker.js"></script>

<style type="text/css" media="print">
table,html,body,iframe,pre,div,form,img,ul,ol,li,dl,dt,tr,td,dd { border:0;margin:0;padding:0; }
h1,h2,h3,h4,h5,h6 { margin:0; padding:0; }
p { margin:0; padding:2px 0; }
body { width:100%; }
a { display:none; }
.select { display:none; }
</style>

<style type="text/css" media="screen">
body { width:80%; }
.orderprint { padding:10px; }
</style>

<style type="text/css">
body {
    margin:0 auto;
}
.clear {clear:both;}

input { vertical-align:middle; }
input.text {
    margin:3px 1px;
    padding:0 3px;
    border:0;
    border-bottom:solid 1px #000000;
    width: 120px;
    vertical-align:middle;
}

.title {text-align: center; font-size: 18px; font-weight: bold; }

table {
    border-top:1px solid #000;
    border-right:1px solid #000;
    border-collapse:collapse;
    margin:12px auto;
    color:#000;
    width: 100%;
}

table .left { text-align:left; }
table .center { text-align:center; }
table .right { text-align:right; }

table p { text-align:center; }
table img { border:0; }
td {
    color:#678197;
    border: 1px solid #000;
    padding:6px 3px;
    color:#000;
    vertical-align:middle;
    font-weight:normal;
    overflow:hidden;
    text-overflow:ellipsis;
}

th {
    font-weight:bold;
    color: #000;
    border:1px solid #000;
    padding:6px 3px;
    white-space:nowrap;
}

table .h4 a { color: #94A5AE; padding:2px 0 0 18px; }
table .h4 a:hover { color: #33CCFF; }
table .color { color: #FF9900; }

.orderprint {
    margin:10px auto 0 auto;
    font:14px '微软雅黑','黑体', 'Lucida Grande', Verdana, sans-serif;
    background:#fff;
}
.orderprint h3 {
    padding-top:12px;
    font-size:15px;
    text-align:center;
}
.orderprint p {
    text-align:left;
    padding-top:6px;
}

.zhang {
	position:absolute;
	z-index: 9;
	top: -140px;
	right:20px;
}
.select { vertical-align:middle; font-size:12px; }
</style>

<!--
<style type="text/css">
@media print {
    @page {
        margin: 10mm;
        size: 210mm 297mm;
        marks: none;
    }
}
</style>
-->

</head>

<body>
    <div class="orderprint">

        {{$content}}

        <div class="select">
            <a class="btn btn-info btn-sm" href="javascript:window.print();">打印</a>
            <a class="btn btn-info btn-sm" href="javascript:window.close();">关闭</a>
        </div>

    </div>

</body>
</head>
</html>
