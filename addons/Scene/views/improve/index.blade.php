<div class="panel">

<table class="table">
	<tr>
		<td>
            <div class="category-tag">
             @if($categorys) 
             @foreach($categorys as $category)
                <a @if($category['id']==$category_id)  class="selected" @endif  href="{{url()}}?category_id={{$category['id']}}">{{$category['name']}}</a>
             @endforeach 
             @endif
            </div>
        </td>
	</tr>
</table>


<div class="padder">
        <div class="row">
            @if($rows)
            @foreach($rows as $row)
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <button type="button" class="option" data-toggle="dialog-image" data-url="{{$public_url}}/uploads/{{$row['path']}}/{{$row['attach_file']}}" data-title="查看"><img src="{{$public_url}}/uploads/{{$row['path']}}/{{$row['attach_file']}}" style="height:200px;"></button>
                    <div class="caption">
                        <h5>{{$row['title']}}</h5>
                        <p class="text-muted">
                        <span class="pull-right">
                            @if(isset($access['add']))
                                <a class="btn btn-xs btn-default" href="{{url('add',['id'=>$row['id']])}}">编辑</a>
                            @endif
                            @if(isset($access['delete']))
                                <a class="btn btn-xs btn-danger" onclick="app.confirm('{{url('delete',['id'=>$row['id']])}}','确定要删除吗？');" href="javascript:;">删除</a>
                            @endif
                        </span>
                        {{$row['content']}}</p>
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