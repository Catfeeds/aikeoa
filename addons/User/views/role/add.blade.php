<div class="panel">

    <form method="post" class="form-horizontal" action="{{url()}}" id="myform" name="myform">

        <table class="table m-b-none table-form">

            <tr>
                <td align="right" width="10%">上级角色</td>
                <td align="left">
                    <select class="form-control input-sm" id='parent_id' name='parent_id'>
                        <option value=''> - </option>
                        @foreach($roles as $role)
                            <option value="{{$role['id']}}" @if($row->parent_id == $role['id']) selected @endif>{{$role['layer_space']}}{{$role['title']}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">角色名称</td>
                <td align="left">
                    <input type="text" id="title" name="title" class="form-control input-sm" value="{{$row->title}}">
                </td>
            </tr>        

            <tr>
                <td align="right">角色标签</td>
                <td align="left">
                    <input type="text" id="name" name="name" value="{{$row->name}}" class="form-control input-sm" @if($row->id > 0) readonly @endif>
                </td>
            </tr>

            <tr>
                <td align="right">角色排序</td>
                <td align="left">
                    <input type="text" id="sort" name="sort" class="form-control input-sm" value="{{$row->sort}}">
                </td>
            </tr>

            <tr>
                <td align="left" colspan="2">
                    <input type="hidden" name="id" value="{{$row->id}}">
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                </td>
            </tr>
        </table>
    </form>
</div>
