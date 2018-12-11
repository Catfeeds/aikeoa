<div class="panel">

<div class="wrapper">

    <form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

        @if(isset($access['add']))
            <a href="{{url('add')}}" class="btn btn-sm btn-info"><i class="icon icon-plus"></i> 新建</a>
        @endif

        <div class="form-group">
            <select id="warehouse_id" name="warehouse_id" class="form-control input-sm" data-toggle="redirect" rel="{{url('index',$query)}}">
                <option value="0">全部仓库</option>
                 @if($warehouse) @foreach($warehouse as $k => $v)
                    <option value="{{$v['id']}}" @if($v['id']==$query['warehouse_id']) selected="true" @endif >{{$v['layer_space']}}{{$v['title']}}</option>
                 @endforeach @endif
             </select>
        </div>

        <div class="form-group">
              <select id='category_id' name='category_id' class="form-control input-sm" data-toggle="redirect" rel="{{url('index',$query)}}">
              <option value="0">产品类别</option>
               @if($category) @foreach($category as $v)
                <option value="{{$v['id']}}" @if($query['category_id'] == $v['id']) selected="true" @endif >{{$v['layer_space']}}{{$v['name']}}</option>
               @endforeach @endif
            </select>
        </div>

        <div class="form-group">
            <select id='status' name='status' class="form-control input-sm" data-toggle="redirect" rel="{{url('index',$query)}}">
              <option value="0">状态</option>
              <option value="1" @if($query['status']==1) selected="true" @endif >启用</option>
              <option value="0" @if($query['status']==0) selected="true" @endif >停用</option>
            </select>
        </div>

        <div class="form-group">
            &nbsp;
            筛选
            <select class="form-control input-sm" id='search_key' name='search_key'>
                <option value="p.name" @if($query['search_key']=='p.name') selected="true" @endif >产品名称</option>
                <option value="p.stock_code" @if($query['search_key']=='p.stock_code') selected="true" @endif >存货代码</option>
            </select>

            <select class="form-control input-sm" id='search_condition' name='search_condition'>
                <option value="like" @if($query['search_condition']=='like') selected="true" @endif >包含</option>
                <option value="=" @if($query['search_condition']=='=') selected="true" @endif >等于</option>
            </select>

            <input type="text" class="form-control input-sm" name="search_value" value="{{$query['search_value']}}" />

            <button type="submit" class="btn btn-default btn-sm">过滤</button>
          
        </div>

    </form>
</div>

<form method="post" id="myform" name="myform">
<div class="table-responsive">
<table class="table m-b-none table-hover">
    <tr>
    <th align="center">排序</th>
    <th align="center">编号</th>
    <th align="left">名称/规格</th>
    <th align="left">代码/条码</th>
    <th>单位</th>
    <th align="right">销售价</th>
    <th align="right">销售(k/a)价</th>
    <th align="right">经销价</th>
    <th align="right">直营价</th>
    <th align="right">基点标准</th>
    <th align="right">重量</th>
    <th align="center"></th>
	</tr>
    @if($rows) @foreach($rows as $v)
  <tr>
    <td align="center">
        <input type="text" class="form-control input-sort" name="id[{{$v['id']}}]" value="{{$v['sort']}}" />
    </td>
    <td align="center">{{$v['id']}}</td>
    <td align="left" nowrap="true">

        @if($v['image'])
        <button type="button" class="option" data-toggle="dialog-image" data-url="{{$public_url}}/uploads/{{$v['image']}}" data-title="产品图片" title="点击查看产品图片"><i class="icon icon-picture"></i></button>
        @else
        <button type="button" class="option" data-toggle="dialog-image" data-url="{{$public_url}}/uploads/products/{{$v['id']}}.jpg" data-title="产品图片" title="点击查看产品图片"><i class="icon icon-picture"></i></button>
        @endif

        &nbsp;{{$v['name']}}
        <p>{{$v['spec']}}</p>
    </td>
    <td align="left">
        {{$v['stock_code']}}
        <p>{{$v['barcode']}}</p>
    </td>
    <td align="center">{{option('product.unit', $v['unit'])}}</td>
    <td align="right">{{$v['price1']}}</td>
    <td align="right">{{$v['price4']}}</td>
    <td align="right">{{$v['price2']}}</td>
    <td align="right">{{$v['price3']}}</td>
    <td align="right">{{$v['level_amount']}}</td>
    <td align="right">{{$v['weight']}}</td>
    <td align="center">
      <a class="option" href="{{url('add')}}?id={{$v['id']}}"> 编辑 </a>
      <a class="option" href="javascript:app.confirm('{{url('delete',['id'=>$v['id']])}}','确定要删除吗？');">删除</a>
    </td>
  </tr>
   @endforeach @endif
</table>
</div>
</form>

    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-1 hidden-xs">
            <button type="button" onclick="optionSort('#myform','{{URL::full()}}');" class="btn btn-primary btn-sm"><i class="icon icon-sort-by-order"></i> 排序</button>
        </div>
        <div class="col-sm-11 text-right text-center-xs">
            {{$rows->render()}}
        </div>
      </div>
    </footer>

</div>
