<div class="panel">

<form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url()}}" id="myform" name="myform">
<div class="table-responsive">

<table class="table table-form">
<tr>
    <td align="right" width="15%">产品仓库</td>
    <td align="left">
      <select class="form-control input-inline input-sm" name="warehouse_id">
          @if($warehouse) @foreach($warehouse as $k => $v)
         <option value="{{$k}}" @if($k==$res['warehouse_id']) selected="true" @endif >{{$v['layer_space']}}{{$v['title']}}</option>
          @endforeach @endif
      </select>
    </td>
    <td align="right">产品类别</td>
    <td align="left">
      <select class="form-control input-inline input-sm" name="category_id">
         <option value="0"> - </option>
          @if($category) @foreach($category as $v)
         <option value="{{$v['id']}}" @if($v['id']==$res['category_id']) selected="true" @endif >{{$v['layer_space']}}{{$v['name']}}</option>
          @endforeach @endif
      </select>
    </td>
</tr>

<tr>
    <td align="right">产品名称</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="name" value="{{$res['name']}}">
    </td>
    <td align="right">产品代码</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="stock_code" value="{{$res['stock_code']}}">
    </td>
</tr>

<tr>
    <td align="right">产品条码</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="barcode" value="{{$res['barcode']}}">
    </td>

    <td align="right">产品重量</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="weight" value="{{$res['weight']}}">
    </td>
</tr>

<tr>
    <td align="right">销售价</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="price1" value="{{$res['price1']}}">
    </td>
    <td align="right">销售(k/a)价</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="price4" value="{{$res['price4']}}">
    </td>
</tr>

<tr>
    <td align="right">经销价</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="price2" value="{{$res['price2']}}">
    </td>
    <td align="right">直营价</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="price3" value="{{$res['price3']}}">
    </td>
</tr>

<tr>
    <td align="right">产品规格</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="spec" value="{{$res['spec']}}">
    </td>
    <td align="right">产品排序</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="sort" value="{{$res['sort']}}">
    </td>
</tr>

<tr>
    <td align="right">基点数量</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="level_amount" value="{{$res['level_amount']}}">
    </td>
    <td align="right">安全库存</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="stock_amount" value="{{$res['stock_amount']}}">
    </td>
</tr>

<tr>
    <td align="right">运费单价</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="freight" value="{{$res['freight']}}">
    </td>
</tr>

<tr>
    <td align="right">产品图片</td>
    <td align="left">
        <input type="file" name="image" id="image">
    </td>
    <td align="right">授权方式</td>
    <td align="left">
    <select class="form-control input-inline input-sm" id='authority' name='authority'>
          <option value='0' @if($res['authority']==0) selected @endif>合同受限</option>
          <option value='1' @if($res['authority']==1) selected @endif>不受限制</option>
    </select>
    <span class="help-inline">合同受限，会根据客户合同限制访问产品，不受限制表示不受约束。</span>
</td>
</tr>

<tr>
    <td align="right">产品状态</td>
    <td align="left">
        <select class="form-control input-inline input-sm" id='status' name='status'>
              <option value='1' @if($res['status']==1) selected @endif>启用</option>
              <option value='0' @if($res['status']==0) selected @endif>停用</option>
        </select>
    </td>
    <td align="right">产品单位</td>
    <td align="left">
    <select class="form-control input-inline input-sm" id='unit' name='unit'>
        @foreach(option('product.unit') as $k => $v)
            <option value='{{$v['id']}}' @if($res['unit'] == $v['id']) selected @endif>{{$v['name']}}</option>
        @endforeach
    </select>
    </td>
</tr>

<tr>
    <td align="right">产品描述</td>
    <td align="left" colspan="3">
        <textarea class="form-control" rows="2" type="text" name="description" id="description">{{$res['description']}}</textarea>
    </td>
</tr>

<tr>
    <td align="left" colspan="4">
        <input type="hidden" id="id" name="id" value="{{$res['id']}}">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
        <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
    </td>
</tr>

</table>

</div>

</form>

</div>