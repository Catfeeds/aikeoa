<div class="panel">

    <div class="panel-heading b-b b-light text-center">
        <h3 class="m-xs m-l-none">
            {{$res['title']}}
        </h3>
        <small class="text-muted">
            发布人: {{get_user($res['created_by'], 'nickname')}}
            &nbsp;
            发布时间: @datetime($res['created_at'])
        </small>
    </div>

    <div class="panel-body text-base">
        {{$res['content']}}
        @include('attachment/view')
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                @if(isset($access['reader']))
                <button type="button" onclick="viewBox('reader', '阅读记录', '{{url('reader',['id' => $res['id']])}}')" class="btn btn-success"><i class="icon icon-eye-open"></i> 阅读记录</button>
                @endif
            </div>
        </div>
    </div>
</div>


