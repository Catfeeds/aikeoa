<div class="form-group">
    <label>授权角色</label>
    <select class="form-control input-sm" data-toggle="redirect" id='role_id' name='role_id' rel="{{url($atcion, $query)}}">
        @foreach($roles as $key => $role)
            <option value="{{$role->id}}" @if($query['role_id'] == $role->id) selected @endif>{{$role->layer_space}}{{$role->name}}</option>
        @endforeach
    </select>
</div>
&nbsp;
<div class="form-group">
    <label>克隆角色</label>
    <select class="form-control input-sm" data-toggle="redirect" id='clone_id' name='clone_id' rel="{{url($atcion, $query)}}">
        <option value="">无</option>
        @foreach($roles as $key => $role)
            <option value="{{$role->id}}" @if($query['clone_id'] == $role->id) selected @endif>{{$role->layer_space}}{{$role->name}}</option>
        @endforeach
    </select>
</div>