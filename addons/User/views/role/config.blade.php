 <div class="panel">

    <!--
     <div class="panel-heading tabs-box">
        <ul class="nav nav-tabs">
            <li class="active">
                <a class="text-sm" href="{{url('')}}"> <i class="fa fa-unlock-alt"></i> 权限配置 </a>
            </li>
        </ul>
    </div>
    -->
    <div class="panel-heading text-base b-b">
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-dark" id="role-form-close"><i class="fa fa-times"></i> 关闭</button>
        </div>
        <button type="button" class="btn btn-sm btn-success" id="role-form-submit"><i class="fa fa-check-circle"></i> 提交</button>
    </div>

    <div class="padder padder-t padder-r-n">
        <form id="myfilter" role="form" class="form-inline" name="myfilter" action="{{url()}}" method="get">
            
            <div class="form-group">
                <label>授权角色</label>
                <select class="form-control input-sm" data-toggle="redirect" id='role_id' name='role_id' rel="{{url($atcion, $query)}}">
                    @foreach($roles as $key => $role)
                        <option value="{{$role->id}}" @if($query['role_id'] == $role->id) selected @endif>{{$role->layer_space}}{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
            <span class="hidden-xs">&nbsp;</span>
            <div class="form-group">
                <label>克隆角色</label>
                <select class="form-control input-sm" data-toggle="redirect" id='clone_id' name='clone_id' rel="{{url($atcion, $query)}}">
                    <option value="">无</option>
                    @foreach($roles as $key => $role)
                        <option value="{{$role->id}}" @if($query['clone_id'] == $role->id) selected @endif>{{$role->layer_space}}{{$role->name}}</option>
                    @endforeach
                </select>
            </div>

        </form>
    </div>

    <div class="wrapper">
    
        <div class="row">
    
            <div class="col-sm-2 m-b padder-r-n">

                <div class="panel panel-info">
                    <div class="panel-heading b-b b-light">
                        <div class="h5 m-t-xs m-b-xs"><i class="fa fa-cubes"></i> 模块列表</div>
                    </div>
                    <div class="list-group">
                        @if(count($modules))
                        @foreach($modules as $menuKey => $menuValue)
                            <a class="list-group-item" onclick="module('{{$menuKey}}');" href="javascript:;">
                                <!--<span class="badge">{{$menuValue['version']}}</span>-->
                                <span class="h5">{{$menuValue['name']}}</span>
                            </a>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-10">

                <form method="post" action="{{url('config')}}" class="form-inline" id="role-form-edit" name="role_form_edit">
                        
                    @if(count($modules))
                        @foreach($modules as $menuKey => $menuValue)

                        <div class="modules" style="display:none;" id="{{$menuKey}}">

                            <div class="m-t-none alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                {{$menuValue['description']}}
                            </div>

                            @if(count($menuValue['controllers']))
                            @foreach($menuValue['controllers'] as $groupKey => $groupValue)
                            
                            <div class="panel m-b-sm b-a">

                                <div class="panel-heading b-b b-light">
                                    <div>{{$groupValue['name']}} <span class="label label-primary">{{$groupKey}}</span></div>
                                </div>
                                
                                <div class="panel-body">
                                    <div class="row">
                                    @if(count($groupValue['actions']))
                                        
                                        @foreach($groupValue['actions'] as $childKey => $childValue)

                                        <div class="col-md-3 col-sm-6 wrapper-xs">
                                            {{'';$selected = $assets[$menuKey][$groupKey.'.'.$childKey]}}

                                            <label title="{{$childKey}}" class="checkbox-inline"><input type="checkbox" name="assets[{{$menuKey}}][{{$groupKey}}.{{$childKey}}][action]" value="1" @if(isset($selected)) checked @endif>{{$childValue['name']}}</label>
                                            <input type="hidden" name="assets[{{$menuKey}}][{{$groupKey}}.{{$childKey}}][id]" value="1">

                                                @if($childValue['access'])
                                                <select class="form-control input-sm" name="assets[{{$menuKey}}][{{$groupKey}}.{{$childKey}}][access]">
                                                    @foreach($menuValue['access'] as $access_key => $access)
                                                    <option @if($selected == $access_key) selected @endif value="{{$access_key}}">{{$access}}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                    </div>
                                </div>
                            </div>

                            @endforeach
                            @endif

                            <div class="clearfix"></div>
                        </div>
                        
                        @endforeach
                        
                    @endif

                    <input type="hidden" name="role_id" value="{{$query['role_id']}}">
                    <input type="hidden" name="key" id="key" value="{{$query['key']}}">
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    ajaxSubmit('role', function(res) {
        if (res.status) {
            $.toastr('success', res.data);
        } else {
            $.toastr('error', res.data);
        }
    });

    var e = $('#role-form-edit');
    var key = e.find('#key').val();
    if(key == '') {
        var list = e.find('.modules');
        key = list.eq(0).attr('id');
        e.find('#key').val(key);
    }
    e.find('#'+key).fadeIn();

});

function module(key) {
    var e = $('#role-form-edit');
    e.find('#key').val(key);
    e.find('.modules').hide();
    e.find('#'+key).fadeIn();
}
</script>