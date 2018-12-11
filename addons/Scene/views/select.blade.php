<select id='category_id' name='category_id' class="form-control input-sm input-inline" data-toggle="redirect" rel="{{url('',$query)}}">
  <option value="0">现场类别</option>
   @if($categorys)
   @foreach($categorys as $k => $v)
    <option value="{{$v['id']}}" @if($query['category_id']==$v['id']) selected="true" @endif >{{$v['title']}}</option>
   @endforeach
   @endif
</select>

&nbsp;上传日期
<input type="text" name="sdate" class="form-control input-sm input-inline" data-toggle="date" size="13" id="sdate" value="{{$query['sdate']}}" readonly>
-
<input type="text" name="edate" class="form-control input-sm input-inline" data-toggle="date" size="13" id="edate" value="{{$query['edate']}}" readonly>

&nbsp;查找
<select id='search_key' class="form-control input-sm input-inline" name='search_key'>
    <option value="scene.title" @if($query['search_key']=='scene.title') selected="true" @endif >主题</option>
    <option value="scene.id" @if($query['search_key']=='scene.id') selected="true" @endif >编号</option>
    <option value="user.nickname" @if($query['search_key']=='user.nickname') selected="true" @endif >提交人</option>
</select>

<select id='search_condition' class="form-control input-sm input-inline" name='search_condition'>
    <option value="like" @if($query['search_condition']=='like') selected="true" @endif >包含</option>
    <option value="=" @if($query['search_condition']=='=') selected="true" @endif >等于</option>
</select>
<input type="text" class="form-control input-sm input-inline" size="15" name="search_value" value="{{$query['search_value']}}" />
