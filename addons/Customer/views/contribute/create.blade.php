<div class="panel">

<form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
<div class="table-responsive">
<table class="table table-form">
    <tr>
        <td width="15%" align="right">主题</td>
        <td align="left">
            <input class="form-control input-sm" type="text" id="subject" name="subject" value="{{old('subject', $row->subject)}}">
        </td>
    </tr>
    <tr>
        <td align="right">客户联系人</td>
        <td align="left">
            {{Dialog::user('customer_contact','contact_id', old('contact_id', $row->contact_id), 0, 0)}}
        </td>
    </tr>
    <tr>
        <td align="right">日期</td>
        <td align="left">
            <input data-toggle="date" class="form-control input-inline input-sm" type="text" name="date" id="date" value="{{old('date', $row->date)}}">
        </td>
    </tr>
    <tr>
        <td align="right">描述</td>
        <td align="left">
            <textarea class="form-control" name="description" id="description">{{old('description', $row->description)}}</textarea>
        </td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="left">
            <input type="hidden" name="id" value="{{$row->id}}">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>     
        </td>
    </tr>
</table>
</div>
</form>

</div>
