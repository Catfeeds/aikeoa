<div class="panel">

    <div class="panel-body">

        <form enctype="multipart/form-data" class="form-horizontal" action="{{url()}}" method="post">
            
            <div class="form-group">
                <label class="col-sm-2 control-label">文件</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" id="userfile" name="userfile">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </div>
            </div>

        </form>
    </div>
</div>
