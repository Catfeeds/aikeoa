<div class="panel">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th align="center">类别</th>
                <th align="center">类别管理员</th>
                @foreach(get_department() as $department)
                <th align="center">{{$department->title}}</th>
                @endforeach
                <th align="center">统计</th>
            </tr>
        </thead>
        <tbody>
        @if($assets)
        @foreach($assets as $asset)
        <tr>
            <td align="center" nowrap>{{$asset->name}}</td>
            <td align="center" nowrap>{{$data[$asset->id]['count_b']}}</td>
            @foreach(get_department() as $department)
                <td align="center">{{$data[$asset->id][$department->id]['count']}}</td>
            @endforeach
            <td align="center">{{$data[$asset->id]['count_a']}}</td>
        </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</div>