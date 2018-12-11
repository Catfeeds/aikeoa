<div class="table-responsive">
    <table class="table b-t table-hover">
    <thead>
    <tr>
        <th align="center">
            <input type="checkbox" class="select-all">
        </th>
        <th align="left">主题</th>
        <th align="center">收件人</th>
        <th align="center">发送时间</th>
        <th align="center">希望回复时间</th>
        <th align="center">状态</th>
        <th align="center">ID</th>
        <th align="center"></th>
    </tr>
    </thead>
    @if($rows)
        @foreach($rows as $k => $v)
        <tr>
            <td align="center"><input type="checkbox" class="select-row" value="{{$v['id']}}" name="id[]"></td>
            <td align="left"><a href="{{url('view')}}?reply=0&id={{$v['id']}}">{{$v['title']}}</a></td>
            
            <td align="center">{{get_user($v['to_user_id'],'nickname')}}</td>
            
            <td align="center">@datetime($v['add_time'])</td>
            <td align="center">@datetime($v['hope_reply_time'])</td>
            <td align="center">
            @if($v['reply_time'] == 0)
                <span class="label label-danger">未回复</span>
            @else
                <span class="label label-success">已回复</span>
            @endif
            </td>
            <td align="center">{{$v['id']}}</td>
            <td align="center">
                @if($query['option'] == 'receive')
                    <a class="option" href="{{url('view',['reply'=>1,'id'=>$v['id']])}}">回复</a>
                @else
                    <a class="option" href="{{url('add',['id'=>$v['id']])}}">编辑</a>
                @endif
            </td>
        </tr>
        @endforeach
    @endif
    </table>
</div>