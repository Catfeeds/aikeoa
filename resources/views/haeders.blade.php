<div class="wrapper-sm">
    <div class="pull-right">

        @if(isset($access['create']) && $haeder['create_btn'])
            <a href="javascript:;" data-toggle="{{$haeder['table']}}" data-action="create" class="btn btn-sm btn-success"><i class="icon icon-plus"></i> 新建{{$haeder['name']}}</a>
        @endif

        <?php
            $params = $haeder['search_form']['params'];
        ?>

        @if($haeder['trash_btn'])
            <?php $params['by'] = 'trash'; ?>
            <a href="{{url('', $params)}}" class="btn btn-sm btn-default @if($haeder['search_form']['query']['by'] == 'trash') active @endif"><i class="fa fa-trash"></i> 回收站</a>
        @endif
        
    </div>

    @if($haeder['buttons'])
    <div class="btn-group">
        <a class="btn btn-info btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bars"></i> 操作 <span class="caret"></span></a>
        <ul class="dropdown-menu">
            @foreach($haeder['buttons'] as $button)
                @if($button['action'] == 'divider')
                    <li class="divider"></li>
                @else
                    @if($button['display'])
                    <li><a data-toggle="{{$haeder['table']}}" data-action="{{$button['action']}}" href="javascript:;"><i class="fa {{$button['icon']}}"></i> {{$button['name']}}</a></li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div> 
    @endif

    <span class="visible-xs">
        <a href="javascript:;" data-toggle="{{$haeder['table']}}" data-action="filter" class="btn btn-sm btn-default"><i class="fa fa-search"></i> 搜索</a>
    </span>

    <!-- 简单搜索表单 -->
    <form id="{{$haeder['table']}}-search-form" class="search-inline-form form-inline hidden-xs" name="mysearch" action="{{url()}}" method="get">
        <div class="form-group search-group">
            <select name="field_0" id="search-field-0" class="form-control input-sm">
                <option data-type="empty" value="">筛选条件</option>
                @foreach($haeder['search_form']['columns'] as $column)
                <option data-type="{{$column[0]}}" value="{{$column[1]}}">{{$column[2]}}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" style="display:none;">
            <select name="condition_0" id="search-condition-0" class="form-control input-sm"></select>
        </div>
        
        <div class="form-group" id="search-value-0"></div>
        
        <div class="btn-group">
            <button id="search-submit" type="submit" class="btn btn-sm btn-default">
                <i class="fa fa-search"></i></button>
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu text-xs" role="menu">
                <li>
                    <a data-toggle="{{$haeder['table']}}" data-action="filter" href="javascript:;">
                        <i class="fa fa-search"></i> 高级搜索</a>
                </li>
            </ul>
        </div>
        @if($haeder['search_form']['params'])
            @foreach($haeder['search_form']['params'] as $key => $param)
            <input name="{{$key}}" type="hidden" value="{{$param}}"> 
            @endforeach
        @endif
    </form>
    
    <?php $by_name = '筛选'; ?>
    @foreach($haeder['bys']['items'] as $item)
        @if($haeder['search_form']['query'][$haeder['bys']['name']] == $item['value'])
        <?php $by_name = $item['name']; ?>
        @endif
    @endforeach

    <div class="btn-group" role="group">
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-filter"></span> {{$by_name}}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <?php
                $params = $haeder['search_form']['params'];
            ?>
            @foreach($haeder['bys']['items'] as $item)
                @if($item['value'] == 'divider')
                    <li class="divider"></li>
                @else
                    <?php $params[$haeder['bys']['name']] = $item['value']; ?>
                    <li class="@if($haeder['search_form']['query'][$haeder['bys']['name']] == $item['value']) active @endif"><a href="{{url('', $params)}}">{{$item['name']}}</a></li>
                @endif
            @endforeach
            <!--
            <li>
                <a href="javascript:;">添加自定义筛选</a>
            </li>
            -->
        </ul>
    </div>

    <div style="display:none;">
        <form id="{{$haeder['table']}}-search-form-advanced" class="search-form" action="{{url()}}" method="get">
            <div class="wrapper-xs search-form-advanced">
                <div class="row">
                    @foreach($haeder['search_form']['columns'] as $i => $column)
                        <?php if($column[0] == 'text2') { continue; } ?>
                        <div class="wrapper-xs">
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{$column[2]}}</label>
                                <?php
                                if (is_array($column[0])) {
                                    $__type  = $column[0]['type'];
                                } else {
                                    $__type  = $column[0];
                                }
                                ?>
                                <input type="hidden" name="field_{{$i}}" id="search-field-{{$i}}" data-type="{{$__type}}" value="{{$column[1]}}">
                            </div>
                            <div class="col-sm-2">
                            <div class="form-group" style="display:none;">
                                    <select name="condition_{{$i}}" id="search-condition-{{$i}}" class="form-control input-sm"></select>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group" id="search-value-{{$i}}"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($haeder['search_form']['params'])
            @foreach($haeder['search_form']['params'] as $key => $param)
                <input name="{{$key}}" type="hidden" value="{{$param}}">
            @endforeach
            @endif
        </form>
    </div>

</div>