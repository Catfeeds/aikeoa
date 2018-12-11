<div class="panel">

<div class="panel-heading tabs-box">
    <ul class="nav nav-tabs">
        <li class="@if($query['tab'] == 'receive') active @endif">
            <a class="text-sm" href="{{url('index',['tab'=>'receive'])}}">收件箱</a>
        </li>
        <li class="@if($query['tab'] == 'send') active @endif">
            <a class="text-sm" href="{{url('index',['tab'=>'send'])}}">发件箱</a>
        </li>
    </ul>
</div>

<div class="wrapper">

    <form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">
    
    @if(isset($access['add']))
        <a href="{{url('add')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
    @endif

    @if($query['tab'] == 'send' && isset($access['delete']))
        <button type="button" onclick="optionDelete('#myform','{{url('delete')}}');" class="btn btn-sm btn-danger">删除</button>
    @endif

    @include('message/select')

    </form>

</div>

@if($query['tab'] == 'send')
    @include('message/index/send')
@else
    @include('message/index/receive')
@endif

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