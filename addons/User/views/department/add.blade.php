<div class="panel">

<form method="post" action="{{url()}}" id="myform" name="myform">

<table class="table table-form">
    <tr>
        <td align="right" width="15%">上级部门</td>
        <td align="left">
          <select class="form-control input-inline input-sm" name="parent_id" id="parent_id">
              <option value="0"> - </option>
               @foreach(get_department() as $v)
                  <option value="{{$v['id']}}" @if($res->parent_id==$v['id']) selected @endif >{{$v['layer_space']}}{{$v['title']}}</option>
               @endforeach
          </select>
        </td>
    </tr>

    <tr>
        <td align="right">部门名称 <span style="color:red;">*</span></td>
        <td><input type="text" id="title" name="title" value="{{$res['title']}}" class="form-control input-inline input-sm" /></td>
    </tr>

    <tr>
        <td align="right">部门主管</td>
        <td>
            {{Dialog::user('user','manager',$res['manager'], 0, 0)}}
        </td>
    </tr>

    <tr>
        <td align="right">上级主管</td>
        <td>
            {{Dialog::user('user','leader',$res['leader'], 0, 0)}}
        </td>
    </tr>

    <tr>
        <td align="right">上级分管</td>
        <td>
            {{Dialog::user('user','superior',$res['superior'], 0, 0)}}
        </td>
    </tr>

    <tr>
        <td align="right">部门电话</td>
        <td><input type="text" id="tel" name="tel" class="form-control input-inline input-sm" value="{{$res['tel']}}" /></td>
    </tr>

    <tr>
        <td align="right">部门传真</td>
        <td><input type="text" id="fax" name="fax" class="form-control input-inline input-sm" value="{{$res['fax']}}" /></td>
    </tr>

    <tr>
        <td align="right">职能描述</td>
        <td><textarea type="text" id="description" name="description" class="form-control input-sm" rows="3">{{$res['description']}}</textarea></td>
    </tr>

    <tr>
        <td align="right"></td>
        <td>
            <input type="hidden" name="id" value="{{$res['id']}}" />
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
            <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
        </td>
    </tr>

</table>

</form>
