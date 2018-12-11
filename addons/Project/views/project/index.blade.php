<div class="panel">

    <div class="wrapper">
        @include('project/query')
    </div>

    <div class="padder b-t">
        <div class="row m-t">
            @if($rows)
            @foreach($rows as $row)
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="thumbnail" style="{{$upload_url}}/{{$row->image}}">
                    <div class="caption">
                        <h4 class="m-t-none">
                            <span class="pull-right text-muted text-xs hinted" title="项目拥有者">{{get_user($row->user_id, 'nickname', false)}}</span>
                            @if($row->tasks->count())
                                <span class="text-base badge bg-danger">{{$row->tasks->count()}}</span>
                            @endif
                            <a class="m-t-sm" href="{{url('task/index', ['project_id' => $row->id])}}">
                                {{$row->name}}
                            </a>
                        </h4>
                        <div class="text-muted">
                            <span class="pull-right">

                                <div class="btn-group">
                                @if(authorize_action('edit', $row['created_by']))
                                    <a class="btn btn-xs btn-default hinted" title="编辑项目" href="{{url('edit',['id'=>$row->id])}}"><i class="fa fa-pencil"></i></a>
                                @endif
                                @if(authorize_action('delete', $row['created_by']))
                                    <a class="btn btn-xs btn-default hinted" title="删除项目" onclick="app.confirm('{{url('delete',['id'=>$row->id])}}','确定要删除吗？');" href="javascript:;"><i class="fa fa-remove"></i></a>
                                @endif
                                </div>

                            </span>
                            <span class="hinted" title="创建时间">@datetime($row->created_at)</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
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