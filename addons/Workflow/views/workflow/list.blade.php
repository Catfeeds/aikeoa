<div class="panel">

    <div class="wrapper b-b text-md">流程列表</div>

    <div class="panel-body">

<div class="row">

     @if($categorys)
        @foreach($categorys as $k => $cat)
        <div class="col-md-4 col-sm-6 m-b">
            <div class="panel panel-info">
                <div class="panel-heading text-base">
                    <i class="fa fa-file-text-o"></i> {{$cat['title']}}
                </div>
                <table class="table table-hover m-b-none">
                    @if($rows[$cat['id']])
                    @foreach($rows[$cat['id']] as $row)
                    <tr>
                        <td><a class="pull-right" href="{{url('form/view', ['id' => $row['id']])}}"><i class="fa fa-eye"></i> 预览</a> <a onclick="workStart({{$row['id']}});" href="javascript:;">{{$row['title']}}</a></td>
                    </tr>
                    @endforeach
                    @endif
                </table>
            </div>
        </div>
        @endforeach
    @endif
</div>
</div>
</div>
<style>
.col-md-4,.col-sm-6 {
    padding-left: 10px;
    padding-right: 10px;
}
</style>

<script type="text/javascript">
function workStart(id)
{
    $('#work-box').__dialog({
        title: '新建工作',
        url:'{{url("add")}}?id='+id,
        dialogClass:'modal-md',
        buttons:[{
            text: '确定',
            'class': 'btn-primary',
            click: function() {
                var myform = $('#myform').serialize();
                $.post('{{url("add")}}', myform, function(res) {
                    if (res.status) {
                        window.location.href= '{{url("edit")}}?process_id='+res.data.process_id;
                    } else {
                        $.toastr('error', res.data);
                    }
                },'json');
            }
        },{
            text: '取消',
            'class': 'btn-default',
            click: function() {
                $(this).dialog('close');
            }
        }]
    });
}
</script>
