@include('layouts/map')

<div class="panel">

    <!--
    <div class="wrapper">
        <form id="myform" name="myform" action="{{url()}}" method="get">
            @include('select')
            <button type="submit" class="btn btn-default btn-sm">搜索</button>
        </form>
    </div>
    -->

    <div class="wrapper">
        @include('market/query')
    </div>

    <form method="post" id="myform" name="myform">

    <div class="table-responsive">

        <table class="table m-b-none table-hover">
            <tr>
                <th align="center" width="60">序号</th>
                <th align="center" width="60">编号</th>
                <th align="center" width="180">区域</th>
                <th align="left" width="180">客户</th>
                <th align="left" width="120">客户销售</th>
                <th align="left" width="120">店名</th>
                <th align="left" width="100">门店价值</th>
                <th align="left" width="80">条码数</th>
                <th align="left" width="220">问题说明</th>
                <th align="center" width="140">创建者</th>
                <th align="center" width="140">时间</th>
                <th align="center" width="120"></th>
            </tr>
            @if($rows)
            @foreach($rows as $n => $v)
            <tr>
            <td align="center">{{$n+1}}</td>
            <td align="center">{{$v['id']}}</td>
            <td align="center">{{get_user($v['add_user_id'], 'nickname')}}</td>
            <td align="left">{{$v['company_name']}}</td>
            <td align="left">{{$v['salesman']}}</td>
            <td align="left">{{$v['title']}}</td>
            <td align="left">{{$v['category_name']}}</td>
            <td align="left">{{$v['bcxc'] + $v['bcxfc'] + $v['bclj'] + $v['bcpc'] + $v['bczl']}}</td>
            <td align="left">{{$v['remark']}}</td>
            <td align="center">{{$v['nickname']}}</td>
            <td align="center">@datetime($v['add_time'])</td>
            <td align="center">
                <a class="option" href="javascript:;" onclick='viewBox("","详细信息","{{url('view',['id'=>$v['id']])}}");'>查看</a>
                
                @if(isset($access['print']))
                <a class="option" target="_blank" href="{{url('print',['id'=>$v['id']])}}">报告</a>
                @endif

                <a class="option" onclick="app.confirm('{{url('delete',['id'=>$v['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
            </td>
            </tr>
            @endforeach
            @endif
        </table>

    </div>
    </form>

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