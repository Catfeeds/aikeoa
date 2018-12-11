@if($attach['main'])

    <span class="btn btn-xs btn-info">
        附件 <span class="badge">{{count($attach['main'])}}</span>
    </span>

    <div class="uploadify-queue">
        @foreach($attach['main'] as $_attach)
        <div class="uploadify-queue-item">
            <span class="file-name">
                <span class="text-muted icon icon-paperclip"></span> <a download="aaaa" href="{{url('index/attachment/download',['id'=>$_attach['id']])}}">{{$_attach['name']}}</a>
            </span> 
            <span class="file-size">&nbsp;({{human_filesize($_attach['size'])}})</span>
            &nbsp;
            @if(in_array(strtolower($_attach['type']), ['pdf']))
                <a href="{{URL::to('uploads').'/'.$_attach['path']}}" class="btn btn-xs btn-default" target="_blank">预览</a>
            @elseif(in_array(strtolower($_attach['type']), ['jpg','png','gif','bmp']))
                <button type="button" class="option" data-toggle="dialog-image" data-url="{{URL::to('uploads').'/'.$_attach['path']}}" data-title="附件预览" class="btn btn-xs btn-default">预览</button>
            @else
                <a class="btn btn-xs btn-default" href="{{url('index/attachment/download',['id'=>$_attach['id']])}}">下载</a>
            @endif
            <div class="clear"></div>
        </div>
        @endforeach
    </div>

@elseif($attachList['view'])

    <span class="btn btn-xs btn-info">
        附件 <span class="badge">{{count($attachList['view'])}}</span>
    </span>

    <div class="uploadify-queue">
        @foreach($attachList['view'] as $k => $v)
        <div class="uploadify-queue-item">
            <span class="file-name">
                <span class="text-muted icon icon-paperclip"></span> <a href="{{url('file/attachment/file',['model'=>$attachList['model'],'id'=>$v['id']])}}">{{$v['title']}}</a>
            </span> 
            <span class="file-size">&nbsp;({{human_filesize($v['size'])}})</span>
            &nbsp;
            @if(in_array(strtolower($v['type']), ['jpg','png','gif','bmp']))
                <button type="button" class="option" data-toggle="dialog-image" data-url="{{URL::to('uploads').'/'.$v['path'].'/'.$v['name']}}" data-title="附件预览" class="btn btn-xs btn-default">预览</button>
            @else
                <a class="btn btn-xs btn-default" href="{{url('file/attachment/file',['model'=>$attachList['model'], 'id'=>$v['id']])}}">下载</a>
            @endif
            <div class="clear"></div>
        </div>
        @endforeach
    </div>

@endif