<div class="panel">

<form class="form-horizontal" method="post" action="{{url()}}" id="window-form" name="window-form">
    <div class="table-responsive">
            <table class="table table-form">

                <tr>
                    <td width="15%" align="right">名字</td>
                    <td width="35%" align="left">
                        <input class="form-control input-inline input-sm" type="text" id="nickname" name="user[nickname]" value="{{old('user.nickname', $row->user->nickname)}}">
                    </td>
                    <td width="15%" align="right">所属客户</td>
                    <td width="35%" align="left">
                        {{Dialog::user('customer','contact[customer_id]', old('contact.customer_id', $row->customer_id), 0, 0)}}
                    </td>
                </tr>

                <tr>
                    <td align="right">性别</td>
                    <td align="left">
                        <select class="form-control input-inline input-sm" name="user[gender]" id="gender">
                            <option value=''> - </option>
                            @foreach(option('user.gender') as $gender)
                                <option value='{{$gender['id']}}' @if(old('user.gender', $row->user->gender) == $gender['id']) selected @endif>{{$gender['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td align="right">职位</td>
                    <td align="left">
                        <select class="form-control input-inline input-sm" id='post' name='user[post]'>
                            @foreach(option('contact.post') as $_post)
                                <option value='{{$_post['id']}}' @if(old('user.post', $row->user->post) == $_post['id']) selected @endif>{{$_post['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="right">手机</td>
                    <td align="left">
                        <input class="form-control input-inline input-sm" type="text" id="mobile" name="user[mobile]" value="{{old('user.mobile',$row->user->mobile)}}">
                    </td>
                    <td align="right">微信</td>
                    <td align="left">
                        <input class="form-control input-inline input-sm" type="text" id="weixin" name="contact[weixin]" value="{{old('contact.weixin', $row->weixin)}}">
                    </td>
                </tr>

                <tr>
                    <td align="right">生日</td>
                    <td align="left">
                        <input class="form-control input-inline input-sm" data-toggle="date" type="text" name="user[birthday]" id="birthday" placeholder="国历" value="{{old('user.birthday', $row->user->birthday)}}">
                    </td>

                    <td align="right">类型</td>
                    <td align="left">
                        <select class="form-control input-inline input-sm" id='type' name='contact[type]'>
                            @foreach(option('contact.type') as $_type)
                                <option value='{{$_type['id']}}' @if(old('contact.type', $row->type) == $_type['id']) selected @endif>{{$_type['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="right">描述</td>
                    <td align="left" colspan="3">
                        <textarea class="form-control" name="contact[description]" id="description">{{old('contact.description', $row->description)}}</textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" value="{{$row->id}}">
        </form>
    </div>
</div>
