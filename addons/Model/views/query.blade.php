<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="wrapper">
        
        <div class="pull-right">
            @if(Request::controller() == 'step' && $access['move'])
                <button type="button" data-toggle="dialog-form" data-title="工作移交" data-url="{{url('move',['model_id'=>$model_id])}}" data-id="moveform" class="btn btn-sm btn-default">工作移交</button>
            @endif
        </div>
        
        模型
        <select class="form-control input-sm input-inline" name="model_id" id="model_id" url="{{url()}}">
            @foreach($models as $_model)
                <option @if($_model['id'] == $model_id) selected @endif value="{{$_model['id']}}">{{$_model['layer_space']}}{{$_model['name']}}</option>
            @endforeach
        </select>

        <a class="btn btn-default btn-sm @if(Request::controller() == 'model') active @endif" href="{{url('model/index')}}"><i class="fa fa-reply"></i> 模型管理</a>

        <a class="btn btn-info btn-sm" href="{{url('create',['parent_id'=>$model_id,'model_id'=>$model_id])}}"><i class="icon icon-plus"></i> 新建</a>

        <a class="btn btn-default btn-sm @if(Request::controller() == 'field') active @endif" href="{{url('field/index',['model_id'=>$model_id])}}">字段管理</a>

        @if($model['is_flow'] == 1)
            <a class="btn btn-default btn-sm @if(Request::controller() == 'step') active @endif" href="{{url('step/index',['model_id'=>$model_id])}}">流程管理</a>
        @endif

        <div class="btn-group">
            <a href="{{url('template/index',['model_id'=>$model_id])}}" class="btn btn-sm btn-default @if(Request::controller() == 'template') active @endif">视图管理</a>
        </div>

        <a href="{{url('permission/index',['model_id'=>$model_id])}}" class="btn btn-sm btn-default @if(Request::controller() == 'permission') active @endif">权限设置</a>
        
    </div>
    <script type="text/javascript">
    $("#model_id").change(function() {
        self.location = $(this).attr('url') + '?model_id=' + this.value; 
    });
    </script>

</form>