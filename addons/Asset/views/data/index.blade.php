<div class="panel">

    @include('tabs')

    <div class="wrapper">
        @include('data/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
		<table class="table table table-hover m-b-none b-t table-hover">
			<thead>
			<tr>
                <th align="center">类别</th>
                <th align="left">品牌</th>
                <th align="left">型号</th>
                <th align="left">识别码</th>
                <th align="center">类别负责人</th>
                <th align="center">当前使用人</th>
                <th align="center">状态</th>
                <th align="center">ID</th>
                <th align="center"></th>
			</tr>
			</thead>
			@if($rows)
            @foreach($rows as $row)
            <tr>
                <td align="center">{{$assets[$row->asset_id]['name']}}</td>
                <td align="left">{{$row->name}}</td>
                <td align="left">{{$row->model}}</td>
                <td align="left">{{$row->number}}</td>
                <td align="center">{{get_user($assets[$row->asset_id]->user_id, 'nickname')}}</td>
                <td align="center">
                    @if($row->status == 2)
                        类别管理员
                    @else
                        {{get_user($row['use_user_id'], 'nickname')}}
                    @endif
                </td>
                <td align="center">
                    @if($row->status == 0)
                        <span class="red">{{$status[$row->status]['name']}}</span>
                    @else
                        <span class="green">{{$status[$row->status]['name']}}</span>
                    @endif
                </td>
                <td align="center">{{$row->id}}</td>
                <td align="center">
                        <a class="option" href="{{url('view',['id'=>$row->id])}}">查看</a>
                    @if(isset($access['edit']))
                    <a class="option" href="{{url('create',['id'=>$row->id])}}"> 编辑 </a>
                    @endif
                    @if(isset($access['delete']))

                            @if($row->deleted == 1)

                            <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row->id,'status'=>0])}}','确定要恢复吗？');" href="javascript:;">恢复</a>

                                @if(isset($access['destroy']))
                                    <a class="option" onclick="app.confirm('{{url('destroy',['id'=>$row->id])}}','确定要删除吗？');" href="javascript:;">删除</a>
                                @endif

                            @else
                                <a class="option" onclick="app.confirm('{{url('delete',['id'=>$row->id,'status'=>1])}}','确定要回收吗？');" href="javascript:;">回收</a>
                            @endif

                    @endif
                    </td>
                </td>
            </tr>
            @endforeach
            @endif
		</table>
    </div>
    </form>

    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-1 hidden-xs">
        </div>
        <div class="col-sm-11 text-right text-center-xs">
            {{$rows->render()}}
        </div>
      </div>
    </footer>
</div>