<div class="panel">

    <form method="post" class="form-horizontal" action="{{url()}}" id="myform" name="myform">
        
        <table class="table table-form">
            <tr>
                <td align="right" width="10%">名称</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="name" value="{{$row['name']}}">
                </td>
            </tr>

            <tr>
                <td align="right">数据</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="data" value="{{$row['data']}}">
                </td>
            </tr>

            <tr>
                <td align="right">链接</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="url" value="{{$row['url']}}">
                </td>
            </tr>

            <tr>
                <td align="right">权限</td>
                <td align="left">
                    {{Dialog::search($row, 'id=receive_id&name=receive_name&multi=1')}}
                </td>
            </tr>

            <tr>
                <td align="right">图标</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="icon" value="{{$row['icon']}}">
                </td>
            </tr>

            <tr>
                <td align="right">宽度</td>
                <td align="left">
                    <select name="grid" id="grid" class="form-control input-sm">
                        @foreach([
                            '6' => '1/2',
                            '4' => '1/3',
                            '8' => '2/3',
                            '3' => '1/4',
                            '9' => '3/4',
                            '12' => '100%',
                        ] as $k => $v)
                        <option value="{{$k}}" @if($k == $row['grid']) selected="selected" @endif>{{$v}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <div class="dropdown-menu buttons">
                    <li><button type="button" data-id="default" class="btn btn-block btn-default">&nbsp;</button></li>
                    <li><button type="button" data-id="primary" class="btn btn-block btn-primary">&nbsp;</button></li>
                    <li><button type="button" data-id="warning" class="btn btn-block btn-warning">&nbsp;</button></li>
                    <li><button type="button" data-id="danger" class="btn btn-block btn-danger">&nbsp;</button></li>
                    <li><button type="button" data-id="success" class="btn btn-block btn-success">&nbsp;</button></li>
                    <li><button type="button" data-id="info" class="btn btn-block btn-info">&nbsp;</button></li>
                  </div>

            <tr>
                <td align="right">颜色</td>
                <td align="left">
                    <select name="color" id="color" class="form-control input-sm">
                        @foreach([
                            'default' => '默认',
                            'primary' => '主要',
                            'warning' => '警告',
                            'danger' => '危险',
                            'success' => '成功',
                            'info' => '信息',
                        ] as $k => $v)
                        <option value="{{$k}}" @if($k == $row['color']) selected="selected" @endif>{{$v}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">类型</td>
                <td align="left">
                    <select class="form-control input-sm" name="type" id="type">
                        <option value="1" @if($row['type'] == 1) selected @endif>部件</option>
                        <option value="2" @if($row['type'] == 2) selected @endif>待办</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">状态</td>
                <td align="left">
                    <select class="form-control input-sm" name="status" id="status">
                        <option value="1" @if($row['status'] == 1) selected @endif>正常</option>
                        <option value="0" @if($row['status'] == 0) selected @endif>停用</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">默认显示</td>
                <td align="left">
                    <select class="form-control input-sm" name="default" id="default">
                        <option value="1" @if($row['default'] == 1) selected @endif>是</option>
                        <option value="0" @if($row['default'] == 0) selected @endif>否</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right">排序</td>
                <td align="left">
                    <input class="form-control input-sm" type="text" name="sort" value="{{$row['sort']}}">
                </td>
            </tr>

            <tr>
                <td align="right"></td>
                <td align="left">
                    <input type="hidden" name="id" value="{{$row['id']}}">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> 提交</button>
                    <button type="button" onclick="history.back();" class="btn btn-default">返回</button>
                </td>
            </tr>

        </table>
    </form>
</div>