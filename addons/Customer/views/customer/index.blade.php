<div class="panel">

    @include('tabs')

    <div class="wrapper">
        @include('customer/query')
    </div>

    <form method="post" id="myform" name="myform">

    <div class="table-responsive">

        <table class="table m-b-none b-t table-hover">
            <thead>
            <tr>
                <th align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="center">登录帐号</th>
                <th align="left">客户名称</th>
                <th align="center">客户代码</th>
                <th align="left">客户地区</th>
                <th>客户类型</th>
                <th>客户公司</th>
                <th>关联客户</th>
                <th>客户区域</th>
                <th>{{url_order($search,'user.created_at','创建时间')}}</th>
                <th>{{url_order($search,'customer.id','ID')}}</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
            @if($rows)
            @foreach($rows as $row)
            <tr>
                <td align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"><i></i>
                    </label>
                </td>
                <td align="center">{{$row->user->username}}</td>
                <td>{{$row->user->nickname}}</td>
                <td align="center">{{$row->code}}</td>
                <td>
                    {{$region[$row->user->province_id]}}
                    {{$region[$row->user->city_id]}}
                    {{$region[$row->user->county_id]}}
                </td>
                <td align="center">{{$types[$row->user->post]['name']}}</td>

                <td align="center">{{option('customer.company', $row->company_id)}}</td>

                <td align="center">
                    {{get_user($row->link_customer_id, 'nickname')}}
                </td>

                <td align="center">{{$row->circle->name}}</td>
                <td align="center">@datetime($row->user->created_at)</td>
                <td align="center">{{$row->id}}</td>
                <td align="center">
                    @if(isset($access['add']))
                        <a class="option" href="{{url('contract/add',['customer_id'=>$row['id']])}}"> 合同 </a>
                    @else
                        <a class="option" href="{{url('contract/view',['customer_id'=>$row['id']])}}"> 合同 </a>
                    @endif
                    <a class="option" href="{{url('view',['id'=>$row['id']])}}"> 查看 </a>
                    <a class="option" href="{{url('add',['id'=>$row['id']])}}"> 编辑 </a>
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