<div class="panel">

<form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url()}}" id="myform" name="myform">
<div class="table-responsive">

<table class="table table-form">
<tr>
    <td align="right" width="15%">产品仓库</td>
    <td align="left">
      <select class="form-control input-sm" name="warehouse_id">
          @if($warehouse) @foreach($warehouse as $k => $v)
         <option value="{{$k}}" @if($k==$res['warehouse_id']) selected="true" @endif >{{$v['layer_space']}}{{$v['title']}}</option>
          @endforeach @endif
      </select>
    </td>
    <td align="right">产品类别</td>
    <td align="left">
      <select class="form-control input-sm" name="category_id">
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
    <input type="text" class="form-control input-sm" id="name" onblur="app.pinyin('name','code', 'all');" name="name" value="{{$res['name']}}">
    </td>

        <td align="right">产品规格</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="spec" value="{{$res['spec']}}">
    </td>

</tr>

<tr>
    <td align="right">产品条码</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="barcode" value="{{$res['barcode']}}">
    </td>

    <td align="right">产品重量(kg)</td>
    <td align="left">
    <input type="text" class="form-control input-sm" name="weight" value="{{$res['weight']}}">
    </td>
</tr>

<tr>
    @foreach($types as $type)
        @if($loop->index % 2 == 0)
            </tr><tr>
        @endif
        <td align="right">{{$type['name']}}价</td>
        <td align="left">
            <input type="text" class="form-control input-sm" name="price{{$type['id']}}" value="{{$res['price'.$type['id']]}}">
        </td>
    @endforeach
</tr>

<tr>
    <td align="right">所属公司</td>
    <td align="left">
        <select class="form-control input-sm" name="company_id" id="company_id">
            <option value=""> - </option>
            @foreach(option('customer.company') as $company)
                <option value="{{$company['id']}}" @if($res['company_id'] == $company['id']) selected @endif>{{$company['name']}}</option>
            @endforeach
        </select>
    </td>
    <td align="right">存货编码</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="stock_number" value="{{$res['stock_number']}}">
    </td>
</tr>

<tr>
    <td align="right">外箱尺寸</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="carton_size" value="{{$res['carton_size']}}">
    </td>
    <td align="right">外箱条码</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="carton_barcode" value="{{$res['carton_barcode']}}">
    </td>
</tr>

<tr>
    <td align="right">最低库存</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="stock_amount" value="{{$res['stock_amount']}}">
    </td>
</tr>

<tr>
    <td align="right">运费单价</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="freight" value="{{$res['freight']}}">
    </td>

    <td align="right">产品排序</td>
    <td align="left">
        <input type="text" class="form-control input-sm" name="sort" value="{{$res['sort']}}">
    </td>
</tr>

<tr>
    <td align="right">助记码</td>
    <td align="left">
        <input type="text" class="form-control input-sm" id="code" name="code" value="{{$res['code']}}">
    </td>

    <td align="right"></td>
    <td align="left">
        <label class="checkbox-inline">
            <input type="checkbox" name="material" id="material" value="1" @if(old('material', $res['material']) == 1) checked @endif> 物料
            <a href="javascript:;" data-container="body" data-toggle="tooltip" title="" data-original-title="产品是物料请勾选。"><i class="icon icon-info-sign"></i></a>
        </label>
        <label class="checkbox-inline">
            <input type="checkbox" name="force_charge" id="force_charge" value="1" @if(old('force_charge', $res['force_charge']) == 1) checked @endif> 强行计算金额
            <a href="javascript:;" data-container="body" data-toggle="tooltip" title="" data-original-title="强行计算费用，如果客户资料设置了物料不收费请勾选此项。"><i class="icon icon-info-sign"></i></a>
        </label>
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
          <option value='0' @if($res['authority']==0) selected @endif>授权显示</option>
          <option value='1' @if($res['authority']==1) selected @endif>直接显示</option>
    </select>
    <a href="javascript:;" data-container="body" data-toggle="tooltip" title="" data-original-title="授权显示，会根据客户合同限制显示产品，直接显示示不受约束。"><i class="icon icon-info-sign"></i></a>
</td>
</tr>

<tr>
    <td align="right">产品状态</td>
    <td align="left">
        <select class="form-control input-inline input-sm" id='status' name='status'>
              <option value='1' @if($res['status'] == '1') selected @endif>启用</option>
              <option value='0' @if($res['status'] == '0') selected @endif>停用</option>
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