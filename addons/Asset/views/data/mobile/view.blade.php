<div class="panel">

<div class="table-responsive">
<table class="table table-form">

<tr>
    <td width="15%" align="right">品牌</td>
    <td>
        {{$row->name}}
    </td>
    <td align="right">型号</td>
    <td>{{$row->model}}</td>
</tr>

<tr>
    <td align="right">识别码</td>
    <td>{{$row->number}}</td>
    <td align="right">使用年限</td>
    <td>{{$row->age_limit}}</td>
</tr>

<tr>
    <td align="right">采购日期</td>
    <td>{{$row->buy_date}}</td>
    <td align="right">首次使用日期</td>
    <td>{{$row->use_date}}</td>
</tr>

<tr>
    <td align="right">当前使用人</td>
    <td><span class="label bg-success">
    @if($row->status == 2)
    	类别管理员
    @else
    	{{get_user($row->use_user_id, 'nickname')}}
    @endif
    </span></td>
    <td align="right">资产类别</td>
    <td>
        @if($assets)
            @foreach($assets as $asset)
                @if($row->asset_id == $asset->id) {{$asset->name}} @endif
            @endforeach
        @endif
    </td>
</tr>

<tr>
    <td align="right">状态</td>
    <td>
        @foreach($status as $_key => $_status)
            @if($row->status == $_status['id']) {{$_status['name']}} @endif
        @endforeach
    </td>
    <td align="right">详细说明</td>
    <td>
        {{$row->description}}
    </td>
</tr>

</table>

<table class="table b-t">
    <thead>
        <tr>
            <th align="center">变更方式</th>
            <th align="center">日期</th>
            <th align="center">使用人</th>
            <th align="left">备注</th>
        </tr>
    </thead>
    @if($logs)
    @foreach($logs as $log)
    <tr>
        <td align="center">
        <?php $types = [1=>'初次使用',2=>'变更使用',3=>'交回管理']; ?>
        {{$types[$log->type]}}
        </td>
        <td align="center">{{$log->start_date}}</td>
        <td align="center">{{get_user($log->user_id, 'nickname')}}</td>
        <td align="left">{{$log->description}}</td>
    </tr>
    @endforeach
    @endif
</table>
</div>

</div>