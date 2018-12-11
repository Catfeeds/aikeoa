<div class="panel">

    @include('tabs')

    <div class="wrapper">
        @include('material/query')
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
                <th align="left">促销编号</th>
                <th align="left">客户名称</th>
                <th align="left">门店名称</th>
                <th align="left">位置</th>
                <!--
                <th align="center">状态</th>
                -->
                <th>{{url_order($search,'promotion.created_at','创建时间')}}</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
            @if($rows)
            @foreach($rows as $row)
            <tr>
                <td align="center">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-row" type="checkbox" name="id[]" value="{{$row['id']}}"><i></i>
                    </label>
                </td>
                <td align="left">
                    <a class="option" href="{{url('promotion/show', ['id' => $row['promotion_id']])}}"> {{$row->promotion->number}} </a>
                </td>

                <td>{{$row->customer_name}} - {{$row->contact_name}}</td>
                <td>{{$row['name']}}</td>
                <td>{{$row['location']}}</td>
                <!--
                <td align="center">
                    <span class="label label-{{$status[$row['status']]['color']}}">{{$status[$row['status']]['name']}}</span>
                </td>
                -->
                <td align="center">@datetime($row['created_at'])</td>
                <td align="center">
                    <a class="option" href="javascript:viewBox('show','查看','{{url('show', ['id'=>$row->id])}}');"> 查看 </a>
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