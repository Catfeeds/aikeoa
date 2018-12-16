<style>
body {
    background-color: #f0f3f4;
}
</style>
<div class="panel b-a">
<form class="form-horizontal form-controller" method="post" action="{{url()}}" id="{{$haeder['table']}}-form-edit" name="{{$haeder['table']}}_form_edit">
    <div class="panel-heading text-base b-b">
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check-circle"></i> 提交</button>
    </div>
    {{$tpl}}
</form>
</div>
<script>
var table = '{{$haeder["table"]}}';
$(function() {
    ajaxSubmit('#' + table + '-form-edit', function(res) {
        if (res.status) {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
            parent.$.toastr('success', res.data);
        } else {
            parent.$.toastr('error', res.data);
        }
    });
});
</script>