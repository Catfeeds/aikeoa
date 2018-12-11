<div class="panel">
    <div class="wrapper">
        @include('contact/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table m-b-none b-t table-hover">
            <thead>
            <tr>
                <th align="left">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="left">姓名</th>
                <th>手机</th>
                <th>生日</th>
                <th>职位</th>
                <th>类型</th>
                <th align="center">{{url_order($search,'customer_contact.customer_id','所属客户')}}</th>
                <th align="center">{{url_order($search,'customer_contact.id','编号')}}</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
            @if($rows)
                @foreach($rows as $row)
                <tr>
                    <td align="left">
                        <label class="i-checks i-checks-sm m-b-none">
                            <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"><i></i>
                        </label>
                    </td>
                    <td align="left">{{$row->user->nickname}}</td>
                    <td align="center">{{$row->user->mobile}}</td>
                    <td align="center">{{$row->user->birthday}}</td>
                    <td align="center">{{option('contact.post', $row->user->post)}}</td>
                    <td align="center">{{option('contact.type', $row->type)}}</td>
                    <td align="center">{{$row->customer->user->nickname}}</td>
                    <td align="center">{{$row->id}}</td>
                    <td align="center">
                        <button type="button" class="option" data-toggle="dialog-view" data-title="查看" data-url="{{url('show', ['id'=>$row->id])}}">查看</button>
                        @if(isset($access['create']))
                        <button type="button" class="option" data-toggle="dialog-form" data-title="编辑" data-url="{{url('create', ['id'=>$row->id])}}" data-id="window-form">编辑</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    </form>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-1 hidden-xs">
            </div>
            <div class="col-sm-11 text-right text-center-xs">
                {{$rows->render()}}
            </div>
        </div>
    </div>
</div>