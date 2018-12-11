<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{$setting['title']}}</title>
<link rel="stylesheet" href="{{$asset_url}}/dist/app.min.css" type="text/css" />
<script src="{{$public_url}}/common"></script>
<script src="{{$asset_url}}/dist/app.min.js"></script>
<script src="{{$asset_url}}/vendor/datepicker/datepicker.js"></script>
<style type="text/css">

body {
    background: -webkit-linear-gradient(right, #198fb4, #0b6fab);
  background: -o-linear-gradient(right, #198fb4, #0b6fab);
  background: linear-gradient(to left, #198fb4, #0b6fab);
  background-color: #3586b7;
}

.table {
    border: 1px solid #58585C;
    color: #000;
    width: 100%;
    margin-bottom: 10px;
}

.table .left { text-align:left; }
.table .center { text-align:center; }
.table .right { text-align:right; }

.table p { text-align:center; }
.table img { border:0; }
.table td {
    border: 1px solid #58585C;
    padding:6px 3px;
    color: #000;
    vertical-align: middle;
    font-weight: normal;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table th {
    font-weight: bold;
    color: #000;
    border: 1px solid #58585C;
    padding: 6px 3px;
    white-space: nowrap;
}

.table>tbody>tr>td, 
.table>tbody>tr>th, 
.table>tfoot>tr>td, 
.table>tfoot>tr>th, 
.table>thead>tr>td, 
.table>thead>tr>th {
    border-top: 1px solid #58585C;
}

.table>thead>tr>th {
    border-bottom: 1px solid #58585C;
}

.title {
    text-align: center;
    font-size: 18px;
    font-weight: bold;
}

.main-container {
    margin:0 auto;
    width: 880px;
    font:14px '微软雅黑','黑体', 'Lucida Grande', Verdana, sans-serif;
    background: #fff;
}

.main-container h3 {
    padding-top:12px;
    font-size:15px;
    text-align:center;
}

.main-container p {
    text-align:left;
    padding-top:6px;
}

.panel {
    width: 1000px;
    margin: 15px auto;
    border-radius: 0;
    border-color: #eee;
}

.table.no-border,
.table.no-border td,
.table.no-border th {
    border: 0;
}

@media screen {
    .main {
        width: 1000px;
        margin: 15px auto;
        padding: 50px 0;
        background-color: #fff;
        border: solid 1px #eee;
        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }
}

@media print {
    .panel {
        display: none; 
    }
    .main {
        margin: 0 auto;
        border: 0;
    }
    @page {
        margin: 10mm;
        size: A4;
   }
}

.text-muted {
    color: #333;
}
.b-t {
    border-top: 1px solid #333;
 }
 .panel-form-show {
    border: 1px solid #333;
    border-top: 0;
    margin: 0;
 }
.panel-form-show .row {
    margin-left: 0;
    margin-right: 0;
}
.panel-form-show .control-text,
.panel-form-show .control-label {
    border-top: 1px solid #333;
    font-weight: 400;
    padding: 6px;
    color: #333;
}

@media print {
   .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
        float: left;
   }
   .col-sm-12 {
        width: 100%;
   }
   .col-sm-11 {
        width: 91.66666667%;
   }
   .col-sm-10 {
        width: 83.33333333%;
   }
   .col-sm-9 {
        width: 75%;
   }
   .col-sm-8 {
        width: 66.66666667%;
   }
   .col-sm-7 {
        width: 58.33333333%;
   }
   .col-sm-6 {
        width: 50%;
   }
   .col-sm-5 {
        width: 41.66666667%;
   }
   .col-sm-4 {
        width: 33.33333333%;
   }
   .col-sm-3 {
        width: 25%;
   }
   .col-sm-2 {
        width: 16.66666667%;
   }
   .col-sm-1 {
        width: 8.33333333%;
   }
}

</style>
</head>

<body>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="pull-right">
                <a class="btn btn-default" href="javascript:window.print();"><i class="icon icon-print"></i> 打印</a>
                <a class="btn btn-default" href="javascript:window.close();"><i class="icon icon-remove"></i> 关闭</a>
            </div>
            <h4><i class="icon icon-note"></i> 打印预览</h4>
        </div>
    </div>

    <div class="main">

        <div class="main-container">

            {{$content}}

        </div>

    </div>

</body>
</html>
