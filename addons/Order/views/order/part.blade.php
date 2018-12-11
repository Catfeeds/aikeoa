<style>
.tab-content {
    background-color: #fff;
    padding: 5px;
}
.tab-content .table {
    margin-bottom: 0;
}
.nav-tabs {
    margin-top: 10px;
    padding-left: 10px;
}
</style>

<div class="panel">

<form id="myform" name="myform" action="{{url()}}" method="post">

<table class="table table-form m-b-none b-b">
    <tr>
        <th align="right" width="15%">订单号</th>
        <td>
            {{$order['number']}}
        </td>
    </tr>
</table>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#goods_list" title="goods" aria-controls="goods_list" role="tab" data-toggle="tab">产品明细</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="goods_list">
        <table class="table table-bordered">
                <tr>
                <th align="center" width="40"><input type="checkbox" class="select-all"></th>
                <th align="center" width="60">序号</th>
                <th align="center" width="220">产品名称</th>
                <th align="center" width="80">产品规格</th>
                <th align="center" width="80">订单数量</th>
                <th align="center" width="80">实发数量</th>
                <th></th>
                </tr>
            <tr>
            @foreach($datas as $n => $data)
            <tr>
                <td align="center"><input type="checkbox" class="select-row" name="goods_ids[]" value="{{$data['id']}}" /></td>
                <td align="center">{{$n+1}}</td>
                <td align="left">{{$data['name']}}</td>
                <td align="left">{{$data['spec']}}</td>
                <td align="right">{{$data['amount']}}</td>
                <td align="right">{{$data['fact_amount']}}</td>
                <td></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<div class="panel-footer">
<input type="hidden" name="order_id" value='{{$order['id']}}' />
<button type="button" onclick="history.back();" class="btn btn-default">返回</button>
<button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
</div>

</form>

        
</div>