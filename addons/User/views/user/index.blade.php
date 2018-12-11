<div class="panel">

    @include('tabs')

    <div class="wrapper">
        @include('user/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
    <table class="table table-hover m-b-none b-t table-hover">
        <thead>
            <tr>
                <th align="center">
                    <input type="checkbox" class="select-all">
                </th>
                <th align="left">姓名</th>
                <th align="left">{{url_order($search,'username','账号')}}</th>
                <th>部门</th>
                <th>角色</th>
                <th>手机</th>
                <th>生日</th>
                @if(is_admin())
                	<th>密码</th>
                @endif
                <th align="center">{{url_order($search,'id','ID')}}</th>
            	<th align="center"></th>
            </tr>
        </thead>

        <tbody>
        @if($rows)
        @foreach($rows as $v)
        <tr>
            <td align="center"><input type="checkbox" class="select-row" value="{{$v['id']}}" name="id[]"></td>
            <td align="left">
                {{$v['nickname']}}
            </td>
            <td align="left">
                {{$v['username']}}
            </td>
            <td align="center">
                {{$v->department->title}}
            </td>
            <td align="center">
                {{$v->role->title}}
            </td>
            <td align="center">{{$v['mobile']}}</td>
            <td align="center">{{$v['birthday']}}</td>
             @if(is_admin())
            	<td align="center" nowrap="true">{{$v['password_text']}}</td>
             @endif
             <td align="center">{{$v['id']}}</td>
            <td align="center">
                <div class="btn-group">
                    <a class="option" href="{{url('view',['id'=>$v['id']])}}"> 查看 </a>
                    <a class="option" href="{{url('add',['id'=>$v['id']])}}"> 编辑 </a>
                </div>
            </td>
        </tr>
         @endforeach
         @endif
         </tbody>
    </table>
    </div>
    </form>

    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-1 hidden-xs">
        </div>
        <div class="col-sm-11 text-right text-center-xs">
            {{$rows->render()}}
        </div>
      </div>
    </footer>
</div>