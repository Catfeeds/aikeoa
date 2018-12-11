<div class="panel">

    <div class="wrapper">
        @include('customer/invoice_query')
    </div>

    <div class="table-responsive">
        <table class="table m-b-none b-t table-hover">
            <thead>
            <tr>
                <th align="center">客户代码</th>
                <th align="left">客户名称</th>
                <th>客户类型</th>
                <th>负责人</th>
                <th>开票类型</th>
                <th>创建时间</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
            @if($rows)
            @foreach($rows as $row)
            <tr>
                <td align="center">{{$row->user['username']}}</td>
                <td>{{$row->user['nickname']}}</td>
                <td align="center">{{$types[$row->user['post']]['name']}}</td>
                <td align="center">{{get_user($row->user['salesman_id'], 'nickname')}}</td>
                <td align="center">{{option('customer.invoice', $row->invoice_type)}}</td>
                <td align="center">@datetime($row->user['created_at'])</td>
                
                <td align="center">
                    <a class="option" href="{{url('invoice_edit', ['id'=>$row['id']])}}"> 编辑 </a>
                </td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-4 hidden-xs">
                
            </div>
            <div class="col-sm-8 text-right text-center-xs">
                {{$rows->render()}}
            </div>
        </div>
    </div>
</div>