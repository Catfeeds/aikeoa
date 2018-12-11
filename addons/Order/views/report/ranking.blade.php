<style>
.red_text td,
.red_text td span { color:red; }
</style>

<div class="panel">

    <div class="wrapper b-b b-light">
        <div class='h4'>{{$now_year}}年经销商销售排行</div>
    </div>

    <div class="wrapper b-b b-light">
        @if(Auth::user()->role->name != 'customer')
        <form class="form-inline" id="myquery" name="myquery" action="{{url()}}" method="get">
            @include('data/select')
            <select class="form-control input-sm" id='tag' name='tag' data-toggle="redirect" rel="{{$query}}">
                <option value="city_id" @if($select['select']['tag']=='city_id') selected @endif>城市模式</option>
                <option value="customer_id" @if($select['select']['tag']=='customer_id') selected @endif>客户模式</option>
            </select>
            <button type="submit" class="btn btn-default btn-sm">过滤</button>
        </form>
        @endif
    </div>

    <table class="table table-bordered">
    	<tr>
    	<th width="80">排行</th>
    	@if($select['select']['tag'] == 'customer_id')
            <th style="white-space:nowrap;">销售圈</th>
    		<th style="white-space:nowrap;">区域</th>
    	@endif
        @if($select['select']['tag']=='city_id')
    	    <th align="center" style="white-space:nowrap;">省份</th>
            <th align="center" style="white-space:nowrap;">城市</th>
        @else
            <th align="center" style="white-space:nowrap;">客户</th>
        @endif
        </th>
    	<th style="white-space:nowrap;">比去年同期增长率</th>
        <th style="white-space:nowrap;">比去年同期增长额</th>
    	<th style="white-space:nowrap;">总销售额</th>
        <th style="white-space:nowrap;">销售额占区域比</th>
        <th style="white-space:nowrap;">增长额总销售贡献比</th>
        <th style="white-space:nowrap;">增长贡献率</th>
    	 @if($categorys['title'])
         @foreach($categorys['title'] as $category)
    		<th align="center" style="white-space:nowrap;">{{$category['name']}}</th>
    	 @endforeach
         @endif

    	<?php
            $this_year_data = $single[$now_year];
            if ($this_year_data) {
                arsort($this_year_data);
            }
            $last_year_data = $single[$last_year];
            $i = 0;
            $total = [];

            $growth = [
                // 今年比去年同期增加额求和
                'a' => [],
                // 今年比去年同期增加额正数求和
                'b' => [],
                // 今年比去年同期增加额负数求和
                'c' => []
            ];

            foreach($this_year_data as $k => $v) {
                $res = $v - $last_year_data[$k];
                $growth['a'][$k] += $res;
                // 正数求和
                if($res > 0) {
                    $growth['b'][$k] += $res;
                }
                // 负数求和
                if($res < 0) {
                    $growth['c'][$k] += $res;
                }
            }

            $this_year_sum = array_sum($this_year_data);
            $last_year_sum = array_sum($last_year_data);
            $growth_a = array_sum($growth['a']);
            $growth_b = array_sum($growth['b']);
            $growth_c = array_sum($growth['c']);

        ?>

    	@if($this_year_data)
            @foreach($this_year_data as $k => $v)

        	<?php
                $i++;
                $total['all'] += $this_year_data[$k];
            ?>

        	<tr class="@if(($v - $last_year_data[$k]) < 0) red_text @endif">
        	    <td align="center">{{$i}}</td>

        	    @if($select['select']['tag']=='customer_id')
                    <td align="center">{{$circles[$single['info'][$k]['circle_id']]['name']}}</td>
        	        <td align="center">{{get_user($single['info'][$k]['salesman_id'], 'nickname')}}</td>
        	    @endif

                @if($select['select']['tag']=='city_id')
                    <td align="center">
                        {{$single['info'][$k]['province_name']}}
                    </td>
                    <td align="center">
                        {{$single['info'][$k]['city_name']}}
                    </td>
                @else
                    <td align="left">
        	            {{get_user($k, 'nickname')}}</span>
                    </td>
        	     @endif

        	    <td align="right" title="去年累计: {{(int)$last_year_data[$k]}} - 今年累计: {{(int)$this_year_data[$k]}}">
        	         @if($last_year_data[$k] > 0)
                        <span @if(($v / $last_year_data[$k] - 1) < 0) style="color:red;" @endif>
                        <?php 
                            $last_year_pre = number_format(($v / $last_year_data[$k] - 1) * 100, 2);
                        ?>
                        {{$last_year_pre}}%
                        </span>
        	         @else
        	            去年同期无
        	         @endif
        	    </td>

                <td align="right" title="去年累计: {{(int)$last_year_data[$k]}} - 今年累计: {{(int)$this_year_data[$k]}}">
                    <span @if($growth['a'][$k] < 0) style="color:red;" @endif>
                        {{number_format($growth['a'][$k], 2)}}
                    </span>
               </td>

        	    <td align="right">{{number_format($this_year_data[$k], 2)}}</td>

                <td align="center">{{number_format(($v / array_sum($this_year_data) * 100), 2)}}%</td>

                <td align="center">{{number_format(($growth['a'][$k] / array_sum($this_year_data) * 100), 2)}}%</td>

                <td align="center">
                    <?php 
                        if ($growth['a'][$k] > 0) {
                            echo number_format(($growth['a'][$k] / $growth_b) * 100, 2);
                        } else {
                            echo '-'.number_format(($growth['a'][$k] / $growth_c) * 100, 2);
                        }
                    ?>%
                </td>

        	    <?php $category_money = $categorys['money'][$now_year][$k]; ?>
        	     @if($categorys['title'])
                 @foreach($categorys['title'] as $category)

        			<td align="right">{{number_format($category_money[$category['id']],2)}}</td>

                    <?php $total[$category['id']] += $category_money[$category['id']]; ?>

        		 @endforeach
                 @endif
        	</tr>
        	@endforeach
            <tr>
                <th align="center">净值合计</th>
                <th align="center"></th>
                
                @if($select['select']['tag'] == 'customer_id')
                    <th align="center"></th>
                @endif

                <th align="center"></th>
                <th align="right">
                    <?php 
                        $pre = number_format((($this_year_sum - $last_year_sum) / $last_year_sum) * 100, 2);
                    ?>
                    <span @if($pre < 0) style="color:red;" @endif>{{$pre}}%</span>
                </th>

                <th align="right">
                    {{number_format($growth_a, 2)}}
                </th>
                
                <th align="right">{{number_format($total['all'], 2)}}</th>

                <th align="center"></th>
                <th align="center"></th>
                <th align="center"></th>

                @if($categorys['title'])
                @foreach($categorys['title'] as $category)
                    <th align="right">{{number_format($total[$category['id']],2)}}</th>
                @endforeach
                @endif

            </tr>
            <tr>
                <th align="center">增长合计</th>
                <th align="center"></th>
                
                @if($select['select']['tag'] == 'customer_id')
                    <th align="center"></th>
                @endif

                <th align="center"></th>
                <th align="right">
                    <?php 
                        $pre = number_format(($growth_b / $last_year_sum) * 100, 2);
                    ?>
                    <span @if($pre < 0) style="color:red;" @endif>{{$pre}}%</span>
                </th>
                <th align="right">
                    {{number_format($growth_b, 2)}}
                </th>
                <th align="center"></th>
                
                
                <th align="right"></th>
                <th align="center"></th>
                <th align="center"></th>

                @if($categorys['title'])
                @foreach($categorys['title'] as $category)
                    <th align="right"></th>
                @endforeach
                @endif

            </tr>

            <tr>
                <th align="center">下降合计</th>
                <th align="center"></th>
                
                @if($select['select']['tag'] == 'customer_id')
                    <th align="center"></th>
                @endif

                <th align="center"></th>
                <th align="right">
                    <?php 
                        $pre = number_format(($growth_c / $last_year_sum) * 100, 2);
                    ?>
                    <span @if($pre < 0) style="color:red;" @endif>{{$pre}}%</span>
                </th>
                <th align="right">
                    {{number_format($growth_c, 2)}}
                </th>
                <th align="center"></th>
                <th align="center"></th>
                <th align="center">
                </th>
                
                <th align="right"></th>
                @if($categorys['title'])
                @foreach($categorys['title'] as $category)
                    <th align="right"></th>
                @endforeach
                @endif

            </tr>
        @endif

    </table>

</div>