<table class="table m-b-none">
    <tr height="24">
        <th align="center">促销本年考核费比(占比)</th>
        <th align="center">消费促销(金额)</th>
        <th align="center">渠道促销(金额)</th>
        <th align="center">经销促销(金额)</th>
        <th align="center">本年已兑现促销金额</th>
        </tr>
            
    <tr height="24">
            <td align="center">{{$assess}}</td>
            <td align="center">{{number_format($promotion[cat][1],2)}}</td>
            <td align="center">{{number_format($promotion[cat][2],2)}}</td>
            <td align="center">{{number_format($promotion[cat][3],2)}}</td>
            <td align="center">{{number_format($promotion_honor,2)}}</td>
        </tr>
</table>

<table class="table b-t m-b-none">
    <tr height="24">
        @if($product_categorys)
            @foreach($product_categorys as $category)
                @if($category['parent_id'] == 0)
                    <th align="center">{{$category['name']}}</th>
                @endif
            @endforeach
        @endif
        </tr>
    <tr height="24">
        @if($product_categorys)
            @foreach($product_categorys as $category)
                @if($category['parent_id']==0)
                    <td align="center">{{$cat_salesdata_ret[$category['id']]}}</td>
                @endif
            @endforeach
        @endif
    </tr>
</table>
</div>