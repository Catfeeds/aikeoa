<div class="panel m-b-none">

    <div class="panel-heading b-b b-light">
        <small class="text-muted">
            来自 : {{get_user($row['created_by'], 'nickname')}}
            &nbsp;创建时间 : @datetime($row['created_at'])
        </small>
    </div>

    <div class="panel-body">
        {{$row['content']}}
    </div>

    <!--
    <div class="panel-footer">
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    </div>
    -->

</div>