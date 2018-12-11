<div class="panel m-b-none">

    <form class="form-horizontal" method="post" action="{{url()}}" id="myform" name="myform">
        <div class="table-responsive">
            <table class="table table-form m-b-none">
                <thaed>
                <tr>
                    <th align="center">兑现日期</th>
                    <th align="right">兑现金额</th>
                    <th>兑现描述</th>
                </tr>
                </thaed>
                @if($rows)
                @foreach($rows as $row)
                <tr>
                    <td align="center">@date($row->date)</td>
                    <td align="right">{{$row->money}}</td>
                    <td align="left">{{$row->description}}</td>
                </tr>
                @endforeach
                @endif
            </table>
        </form>
    </div>
</div>
