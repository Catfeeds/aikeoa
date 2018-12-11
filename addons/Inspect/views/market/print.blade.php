<table class="table" style="width:1024px;height:768px;margin:20px auto;border: 1px solid #ddd;">
    
    <!--
    <tr>
        <td width="10%" align="right">客户</td>
        <td width="40%" align="left">
            {{$main['company_name']}}
        </td>
        <td width="10%" align="right">客户销售</td>
        <td width="40%" align="left">
            {{$main['salesman']}}
        </td>
    </tr>
    -->

    <tr>
        <td align="left" style="vertical-align:middle;">
            店名：{{$main['title']}}，位置信息：{{$main['location']}}，收银台：{{(int)$main['cashier']}}个，客户销售：{{$main['salesman']}}，<span style="color:#f00;">门店价值：{{$main['category_name']}}</span>
        </td>
    </tr>

    <tr>
        <td align="left" style="vertical-align:middle;">
            条码数：小菜{{(int)$main['bcxc']}}个, 下饭菜{{(int)$main['bcxfc']}}个, 辣酱{{(int)$main['bclj']}}个, 泡菜{{(int)$main['bcpc']}}个, 佐料{{(int)$main['bczl']}}个
        </td>
    </tr>

    <tr>
        <td align="left" style="vertical-align:middle;">
            问题说明：{{$main['remark']}}
        </td>
    </tr>

    <tr>
        <td align="left" style="height:610px;vertical-align:middle;">
            @if($_attachments)
            @foreach($_attachments as $i => $v)
                @if($i > 2)
                    <?php break; ?>
                @endif
                @if($i == 0)
                    <div style="float:left;text-align:center;" width="30%"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" height="550" width="309" style="margin-left:15px;" /><br>陈列照片{{$i}}</div>
                @elseif($i == 2)
                    <div style="float:left;text-align:center;" width="30%"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" height="550" width="309" /><br>陈列照片{{$i}}</div>
                @else
                    <div style="float:left;text-align:center;" width="30%"><img src="{{$public_url}}/uploads/{{$v['path']}}/{{$v['name']}}" height="550" width="309" style="margin-left:25px;margin-right:25px;" /><br>陈列照片{{$i}}</div>
                @endif
            @endforeach 
            @endif
        </td>
    </tr>
</table>