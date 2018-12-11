<div class="select">
    <form id="myform" name="myform" action="{{$query}}" method="get">
        产品仓库:
        <select id='warehouse_id' name='warehouse_id' data-toggle="redirect" rel="{{$query}}">
            <option value="0">产品仓库</option>
             @if($warehouses)
             @foreach($warehouses as $k => $v)
                <option value="{{$k}}" @if($select['select']['warehouse_id']==$k) selected @endif>{{$v['layer_space']}}{{$v['title']}}</option>
             @endforeach 
             @endif
        </select>

        模板类型:
        <select data-toggle="redirect" id="tpl" name='tpl' rel="{{$query}}">
         @if($templates)
         @foreach($templates as $k => $v)
            <option value='{{$k}}' @if($select['select']['tpl'] == $k) selected @endif>{{$v}}</option>
         @endforeach 
         @endif
        </select>

        <input type="hidden" name="order_id" value="{{$select['select']['order_id']}}">
        <button type="submit" class="btn btn-default btn-sm">过滤</button>

         @if($select['select']['tpl'] == 'shipping')
            <div style="border:1px solid #000;margin:5px 0;padding:5px;">
                @if($categorys)
                @foreach($categorys as $k => $v)
                    <input type="checkbox" name="category[{{$v['id']}}]" id="category_{{$v['id']}}" value="1" @if(isset($select['select']['category'][$v['id']])) checked="true" @endif  /><label for="category_{{$v['id']}}">{{$v['name']}} </label>
                @endforeach
                @endif
            </div>
         @endif
    </form>
</div>