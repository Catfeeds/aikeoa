<div class="panel">

    <div class="wrapper">
    @if(isset($access['add']))
        <a href="{{url('add')}}" class="btn btn-info btn-sm"><i class="icon icon-plus"></i> 新建</a>
    @endif
    </div>

    <div class="table-responsive">
    <table class="table m-b-none table-hover">
            <tr>
                <th align="left">文件名</th>
                <th align="right">文件大小</th>
                <th align="center">更新时间</th>
                <th align="center"></th>
            </tr>
            <tr>
                <td align="left"><a title="点击下载" href="http://www.shenghuafood.com/uploads/download/2015/09/150920.zip"><i class="icon icon-download"></i> 网络销售的网页版面150920版本</a></td>
                <td align="right">299.00MB</td>
                <td align="center">2015-09-20 14:26</td>
                <td align='center'>
                </td>
            </tr>
            @if($rows)
            @foreach($rows as $k => $v)
            <tr>
                <td align="left"><a title="点击下载" href="{{url('down',['id'=>$v['id']])}}"><i class="icon icon-download"></i> {{$v['title']}}</a></td>
                <td align="right">{{human_filesize($v['size'])}}</td>
                <td align="center">@datetime($v['add_time'])</td>
                <td align='center'>
                    <a class="option" href="{{url('down',['id'=>$v['id']])}}">下载</a>
                    <a class="option" onclick="app.confirm('{{url('delete',['id'=>$v['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
                </td>
            </tr>
            @endforeach
            @endif
    </table>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-1 hidden-xs">
            </div>
            <div class="col-sm-11 text-right text-center-xs">
                {{$rows->render()}}
            </div>
        </div>
    </div>
</div>