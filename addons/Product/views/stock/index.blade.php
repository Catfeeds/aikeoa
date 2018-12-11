<div class="panel">

    @include('tabs')

  <div class="wrapper">
      @include('stock/query')
  </div>

  <form method="post" id="myform" name="myform">
    <div class="table-responsive">
    <table class="table b-t m-b-none table-hover">
    <thead>
    <tr>
    <th align="center">
        <input class="select-all" type="checkbox">
    </th>
    <th align="left">单号</th>
    <th>类型</th>
    <th align="center">日期</th>
    <th align="right">数量</th>
    <th>创建者</th>
    <th>创建时间</th>
    <th></th>
	</tr>
</thead>

  @foreach($rows as $v)
  <tr>
    <td align="center">
        <input class="select-row" type="checkbox" name="id[]" value="{{$v['id']}}">
    </td>
    <td align="left">{{$v['number']}}</td>
    <td align="center">{{$v->type1->title}}</td>
    <td align="center">{{$v['date']}}</td>
    <td align="right">{{$v['quantity']}}</td>
     <td align="center">{{get_user($v['add_user_id'], 'nickname')}}</td>
    <td align="center">@datetime($v['add_time'])</td>
    <td align="center">
      <a class="option" href="javascript:app.confirm('{{url('export',['id'=>$v['id']])}}','确定导出记录到用友吗?');">导入用友({{$v['export']}})</a>
      <a class="option" href="javascript:;" onclick='viewBox("","明细 [ {{$v['number']}} ]","{{url('view',['id'=>$v['id'],'type'=>$v['type']])}}");'>查看</a>
    </td>
  </tr>
  @endforeach 
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