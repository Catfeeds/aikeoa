<div class="panel">
    <div class="wrapper">
        @include('contribute/query')
    </div>

    <form method="post" id="myform" name="myform">
    <div class="table-responsive">
        <table class="table m-b-none table-hover">
            <tr>
                <th align="left">
                    <label class="i-checks i-checks-sm m-b-none">
                        <input class="select-all" type="checkbox"><i></i>
                    </label>
                </th>
                <th align="left">项目</th>
                <th align="center">日期</th>
                <th align="center">所属客户</th>
                <th align="center">客户销售人员</th>
                <th align="center">描述</th>
                <th align="center"></th>
            </tr>
            @if($rows)
                @foreach($rows as $row)
                <tr>
                    <td valign="center">
                        <label class="i-checks i-checks-sm m-b-none">
                            <input class="select-row" type="checkbox" name="id[]" value="{{$row->id}}"@if($row->system == 1) disabled @endif><i></i>
                        </label>
                    </td>
                    <td align="left">{{$row->subject}}</td>
                    <td align="center">{{$row->date}}</td>
                    <td align="center">{{$row->customer->user->nickname}}</td>
                    <td align="center">{{$row->contact->user->nickname}}</td>
                    <td align="center">{{$row->description}}</td>
                    <td align="center">
                        @if(isset($access['create']))
                            <a class="option" href="{{url('create',['id'=>$row->id])}}"> 编辑 </a>
                        @else
                            <a class="option" href="{{url('view',['id'=>$row->id])}}"> 查看 </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
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