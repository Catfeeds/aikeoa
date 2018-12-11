<style type="text/css">
html { overflow:hidden; }
.tab-content { height:430px; overflow-y:auto; }
</style>

<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs m-t padder" role="tablist">
        <li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">位置信息</a></li>
        <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">现场照片</a></li>
        <li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">巡店情况</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab1">
            <div id="mapinfo" style="height:430px;width:100%;">加载百度地图...</div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab2">
            <table class="table">
                <tr>
                    <th align="left" width="60">陈列照片</th>
                </tr>
                @if($attachments['view'])
                @foreach($attachments['view'] as $v)
                <tr>
                    <td align="left"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" /></td>
                </tr>
                @endforeach 
                @endif
                <tr>
                    <th align="left" width="60">特陈照片</th>
                </tr>
                @if($attachments2['view'])
                @foreach($attachments2['view'] as $v)
                <tr>
                    <td align="left"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" /></td>
                </tr>
                @endforeach 
                @endif
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab3">
            {{$main['remark']}}
        </div>
    </div>
</div>

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