<style type="text/css">
html { background:#fff; overflow:hidden; }
.tab-content { height:430px; overflow-y:auto; }
</style>

<script type="text/javascript">
$(function() {
    // 百度地图API功能
	var map = new BMap.Map("mapinfo");
	var point = new BMap.Point({{$main['lng']}},{{$main['lat']}});
	map.centerAndZoom(point, 15);
    // 创建标注
	var marker = new BMap.Marker(point);
    // 将标注添加到地图中
	map.addOverlay(marker);
    // 鼠标滑轮缩放
    map.enableScrollWheelZoom();
});
</script>


<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs m-t padder" role="tablist">
        <li role="presentation" class="active"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">库存商品</a></li>
        <li role="presentation"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">位置信息</a></li>
        <li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">库存照片</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="tab1">
            <div id="mapinfo" style="height:430px;width:100%;">加载百度地图...</div>
        </div>
        <div role="tabpanel" class="tab-pane active" id="tab2">
            <table class="table table-bordered no-border m-b-none">
                <tr>
                    <th align="center">产品名称</th>
                    <th align="center" title="正常数量">总数量</th>
                    <th align="center" title="问题数量">超3个月生产日期数量</th>
                    <th align="center">上次发货数量</th>
                    <th align="center">上次发货时间</th>
                    <th align="center">说明</th>
                    <th align="center">产品ID</th>
                </tr>
                @if($rows)
                @foreach($rows as $v)
                @if($v['name'])
                <tr>
                    <td align="left">{{$v['name']}} [{{$v['spec']}}]</td>
                    <td align="right">{{$v['amount']}}</td>
                    <td align="right">{{$v['amount2']}}</td>
                    <td align="right">{{$orderDatas[$v['product_id']]['fact_amount']}}</td>
                    <td align="center">@datetime($orderDatas[$v['product_id']]['delivery_time'])</td>
                    <td align="right">{{$v['remark']}}</td>
                    <td align="center">{{$v['product_id']}}</td>
                </tr>
                @endif
                @endforeach 
                @endif
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab3">

            <table class="table">
                <tr>
                    <th align="left">图片列表</th>
                </tr>
                @if($attachments['view'])
                @foreach($attachments['view'] as $v)
                <tr>
                    <td align="left"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" /></td>
                </tr> 
                @endforeach
                @endif
            </table>
        </div>
    </div>
</div>