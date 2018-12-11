<?php

// 高级搜索生成 where 条件
function search_condition($query)
{
    $search = $query['search'];
    $type   = $query['condition'];

    switch ($type) {
        case 'is':
            $condition = array('=', $search);
            break;
        case 'isnot':
            $condition = array('!=', $search);
            break;
        case 'like':
            $condition = array('like', '%'.$search.'%');
            break;
        case 'not_like':
            $condition = array('not like', '%'.$search.'%');
            break;
        case 'start_with':
            $condition = array('like', $search.'%');
            break;
        case 'not_start_with':
            $condition = array('not like', $search.'%');
            break;
        case 'end_with':
            $condition = array('like', '%'.$search);
            break;
        case 'empty':
            $condition = array('=', '');
            break;
        case 'not_empty':
            $condition = array('!=', '');
            break;

        case 'gt':
            $condition = array('>', $search);
            break;
        case 'egt':
            $condition = array('>=', $search);
            break;
        case 'lt':
            $condition = array('<', $search);
            break;
        case 'elt':
            $condition = array('<=', $search);
            break;
        case 'eq':
            $condition = array('=', $search);
            break;
        case 'neq':
            $condition = array('!=', $search);
            break;

        case 'birthday':
            $condition = array('birthday', $search);
            break;
            
        case 'birthbetween':
            $condition = array('birthbetween', $search);
            break;
            
        case 'pacs':
            $condition = array('pacs', $search);
            break;

        case 'in':
            $condition = array('in', explode(',', $search));
            break;

        case 'address':
            $condition = array('!=', '');
            break;

        case 'second2':
            $condition = array('second2', [strtotime($search[0]), strtotime($search[1])]);
            break;
        case 'between':
            $search = strtotime($search);
            $condition = array('between', array($search-1, $search+86400));
            break;
        case 'not_between':
            $search = strtotime($search);
            $condition = array('not_between', array($search, $search+86399));
            break;
        case 'tlt':
            $condition = array('<', strtotime($search));
            break;
        case 'tgt':
            $condition = array('>', strtotime($search) + 86400);
            break;
        default:
            $condition = array('=', $search);
    }
    
    return $condition;
}

// 组合搜索表单
function search_form($params = [], $columns = [], $orders = [])
{
    if ($params['referer']) {
        $uri = join('_', Request::segments());
        Session::put('referer_'.$uri, URL::full());
    }

    $params['advanced'] = isset($params['advanced']) ? $params['advanced'] : 0;

    $gets = Input::get();

    $query = $where = [];

    foreach ($gets as $key => $get) {
        $key = str_replace('_', '.', $key);
        array_set($query, $key, $get);
    }
    
    if ($query['field']) {
        foreach ($query['field'] as $i => $field) {
            $forms['field'][$i]     = $field;
            $forms['condition'][$i] = $query['condition'][$i];
            $forms['search'][$i]    = $query['search'][$i];

            $where[$i]['field']     = $field;
            $where[$i]['condition'] = $query['condition'][$i];
            $where[$i]['search']    = $query['search'][$i];

            $active = 0;

            if ($query['condition'][$i] == 'not_empty' || $query['condition'][$i] == 'empty') {
                $active = 1;
            }

            if ($active == 0) {
                $values = is_array($query['search'][$i]) ? $query['search'][$i] : [$query['search'][$i]];

                foreach ($values as $key => $value) {
                    if ($value == '') {
                        continue;
                    }
                    $active = 1;
                }
            }
            $where[$i]['active'] = $active;
        }
    } else {
        foreach ($columns as $i => $column) {
            $forms['field'][$i]     = $column[1];
            $forms['condition'][$i] = '';
            $forms['search'][$i]    = empty($column[3]) ? '' : $column[3];
        }
    }

    foreach ($params as $key => $default) {
        $params[$key] = Input::get($key, $default);
        $forms[$key]  = $params[$key];
    }

    $search['forms']   = $forms;
    $search['columns'] = $columns;
    $search['params']  = $params;
    $search['where']   = $where;
    $search['query']   = $gets + $params;
    $search['limit']   = $gets['limit'] > 0 ? $gets['limit'] : 25;
    return $search;
}

/**
 * 排序表格方法
 */
function url_order($search, $field, $name)
{
    $query = $search['query'];
    $query['order'] = $query['order'] == 'asc' ? 'desc' : 'asc';

    $icon = ' <i class="icon icon-selectbox"></i>';

    if ($query['srot'] == $field) {
        $icon = $query['order'] == 'desc' ? ' <i class="icon icon-sort-by-attributes"></i>' : ' <i class="icon icon-sort-by-attributes-alt"></i>';
    }
    $query['srot']  = $field;
    return '<a href="'.url('', $query).'">'.$name . $icon.'</a>';
}

// 审批流程状态处理
function get_step_status($data)
{
    $table = $data->getTable();

    // 没有数据就读取流程编号为1的数据
    if ($data->exists) {
        $step = $data->step;
    } else {
        // 新建表单
        $data->step_number = 1;
        $step = $data->getStep($data->step_number);
        // 设置model关系
        $data->setRelation('step', $step);
    }

    $id = (int)$data->id;

    $auth = Auth::user();

    switch ($step->type) {
        // 指定办理人
        case 'user':
            $ids = explode(',', $step->type_value);
            if (in_array($auth->id, $ids)) {
                $user_id = $auth->id;
            }
            break;

         // 负责人
        case 'owner':
            $user_id = $auth->salesman_id;
            break;

        // 指定角色办理人
        case 'role':
            $ids = explode(',', $step->type_value);
            $roles = DB::table('role')->whereIn('id', $ids)->pluck('name', 'id');
            if (isset($roles[$auth->role_id])) {
                $user_id = $auth->id;
            }
            break;

        // 单据创建者
        case 'created_by':
            $user_id = $data->created_by;
            break;

        // 直属领导
        case 'leader':
            // 第一步提交后审批
            if ($data->step_number <= 2) {
                $user = DB::table('user')->find($data->created_by);
                $user_id = $user['leader_id'];
            } else {
                $user_id = $auth->leader_id;
            }
            break;

        // 部门负责人
        case 'manager':
            $user_id = $auth->department->manager;
            break;

        // 客户负责人
        case 'customer_owner':
            $customer = DB::table('user')->find($data['customer_id']);
            $user_id  = $customer['salesman_id'];
            break;

        // 主表字段值
        case 'field':
            if ($step->type_value == 'supplier_id') {
                $supplier = DB::table('supplier')->find($data->supplier_id);
                $user_id  = $supplier['user_id'];
            }
            break;

        // 自定义
        case 'custom':
            $user_id = 0;
            break;
    }

    $res['user_id'] = $user_id;
    $res['number']  = $step->number;
    $res['edit']    = $user_id == $auth->id;
    $res['color']   = $step->color == '' ? 'default' : $step->color;
    $res['name']    = $step->name;
    $res['key']     = AES::encrypt($table.'.'.$id, config('app.key'));

    // 新建表单设置编辑权限
    if ($data->step_number == 1) {
        if ($data->exists) {
            $res['edit'] = $user_id == $auth->id;
        } else {
            $res['edit'] = 1;
        }
    }

    if ($res['edit']) {
        $html = '<a class="label label-'.$res['color'].' label-turn" href="javascript:;" onclick="app.turn(\''.$res['key'].'\');">'.$res['name'].'</a>';
    } else {
        $html = '<span class="label label-'.$res['color'].'">'.$res['name'].'</span>';
    }
    $res['html']  = $html;

    $bg = $res['edit'] == 1 ? 'bg-danger ' : '';
    $res['text'] = '<span class="'.$bg.'badge">'.$step->number.'</span> '.$step->name;

    return $res;
}

/**
 * 通知方法
 */
function notify($users = [], $content = [], $options = [])
{
    if (func_num_args() == 0) {
        return new Aike\Index\Notification();
    } else {
        return new Aike\Index\Notification($users, $content, $options);
    }
}

/**
 * 检查权限授权层级
 */
function authorise($action = null, $asset_name = null)
{
    return Aike\User\User::authorise($action, $asset_name);
}

/**
 * 附件上传
 */
function attachment_uploader($field = 'attachment', $ids = '', $draft = true)
{
    $attachments = Aike\Index\Attachment::edit($ids);

    if (!$draft) {
        unset($attachments['draft']);
    }

    return view('attachment/create2', [
        'field'       => $field,
        'attachments' => $attachments,
    ]);
}

/**
 * 附件显示
 */
function attachment_show($field = 'attachment', $ids = '')
{
    $attachments = Aike\Index\Attachment::view($ids);
    return view('attachment/show2', [
        'attachments' => $attachments,
    ]);
}

/**
 * 附件新建
 */
function attachment_create($field, $rows = [], $draft = [])
{
    static $js = 0;

    $js++;

    return view('attachment/create', [
        'field' => $field,
        'rows'  => $rows,
        'draft' => $draft,
        'js'    => $js,
    ]);
}

/**
 * 附件编辑
 */
function attachment_edit($table, $id, $path = '')
{
    $attach['model'] = $table;
    $attach['path']  = $path;
    $attach['draft'] = DB::table($table)->where('add_user_id', Auth::id())->where('state', 0)->get();

    $id = array_filter(explode(',', $id));
    if ($id) {
        $queue = DB::table($table)->whereIn('id', $id)->where('state', 1)->get();
    }
    $attach['queue'] = array_by($queue);

    return $attach;
}

/**
 * 附件编辑
 */
function attachment_view($table, $id)
{
    $attach['model'] = $table;
    $id = array_filter(explode(',', $id));
    if ($id) {
        $queue = DB::table($table)->whereIn('id', $id)->where('state', 1)->get();
    }
    $attach['view'] = array_by($queue);

    return $attach;
}

/**
 * 查询附件
 */
function attachment_get($table, $id)
{
    $id = array_filter(explode(',', $id));
    if ($id) {
        return DB::table($table)->whereIn('id', $id)->get();
    }
    return [];
}

/**
 * 附件删除
 */
function attachment_delete($table, $id)
{
    $id = array_filter(explode(',', $id));
    if ($id) {
        $rows = DB::table($table)->whereIn('id', $id)->get();
        
        foreach ($rows as $row) {
            // 文件路径
            $name = $row['path'] == '' ? $row['name'] : $row['path'];
            $file = upload_path().'/'.$name;

            if (is_file($file)) {
                unlink($file);
            }

            // 旧版文件删除
            $old = upload_path().'/'.$row['path'].'/'.$row['name'];
            if (is_file($old)) {
                unlink($old);
            }

            DB::table($table)->where('id', $row['id'])->delete();
        }
    }
    return true;
}

/**
 * 将草稿附件存储为可用
 */
function attachment_store($table, $id)
{
    if (empty($id)) {
        return '';
    }

    foreach ($id as $_id) {
        DB::table($table)->where('id', $_id)->update([
            'state' => 1,
        ]);
    }
    return join(',', array_filter($id));
}

// 多图片base64上传保存
function attachment_base64($table, $images, $path = 'default', $extension = 'jpg')
{
    $path = $path.'/'.date('Y/m');
    $directory = upload_path().'/'.$path;

    if (!is_dir($directory)) {
        @mkdir($directory, 0777, true);
    }
    
    $res = [];

    foreach ($images as $image) {
        $name = date('dhis_').str_random(4).'.'.$extension;
        $name = mb_strtolower($name);

        $image = base64_decode(str_replace(' ', '+', $image));
        $size = file_put_contents($directory.'/'.$name, $image);
        if ($size) {
            $res[] = DB::table($table)->insertGetId([
                'path'        => $path,
                'name'        => $name,
                'title'       => $name,
                'type'        => $extension,
                'state'       => 1,
                'size'        => $size,
                'add_user_id' => Auth::id(),
                'add_time'    => time(),
            ]);
        }
    }
    return join(',', array_filter($res));
}

// 多图片上传保存
function attachment_images($table, $name, $path = 'default')
{
    $images = Input::file($name);
    
    $path = $path.'/'.date('Y/m');
    $upload_path = upload_path().'/'.$path;

    $res = [];

    foreach ($images as $image) {
        if ($image->isValid()) {
            // 文件后缀名
            $extension = $image->getClientOriginalExtension();

            // 兼容do开发的客户端上传
            if ($extension == 'do') {
                $clientName = $image->getClientOriginalName();
                $extension = pathinfo(substr($clientName, 0, -3), PATHINFO_EXTENSION);
            }

            // 文件新名字
            $filename = date('dhis_').str_random(4).'.'.$extension;
            $filename = mb_strtolower($filename);

            if ($image->move($upload_path, $filename)) {
                $res[] = DB::table($table)->insertGetId([
                    'path'        => $path,
                    'name'        => $filename,
                    'title'       => $filename,
                    'type'        => $extension,
                    'state'       => 1,
                    'size'        => $image->getClientSize(),
                    'add_user_id' => Auth::id(),
                    'add_time'    => time(),
                ]);
            }
        }
    }
    return join(',', array_filter($res));
}

/**
 * 单图片上传，并删除旧图片
 */
function image_create($path, $name = 'image', $oldfile = '')
{
    if (Request::hasFile($name)) {
        $file = Input::file($name);

        // 文件后缀名
        $extension = $file->getClientOriginalExtension();

        // 文件新名字
        $filename = date('dhis_').str_random(4).'.'.$extension;
        $filename = mb_strtolower($filename);

        $path = $path.'/'.date('Y/m');

        $upload_path = upload_path().'/'.$path;

        if ($file->move($upload_path, $filename)) {
            // 上传成功删除旧文件
            if ($oldfile) {
                image_delete($oldfile);
            }
            return $path.'/'.$filename;
        }
    }
    return null;
}

/**
 * 删除单个图片
 */
function image_delete($file)
{
    $file = upload_path().'/'.$file;
    if (is_file($file)) {
        unlink($file);
    }
}

/**
 * 生成缩略图
*/
function thumb($file, $width, $hight)
{
    $info = pathinfo($file);
    $thumb = $info['dirname'].'/thumb-'.$width.'-'.$info['basename'];
    if (is_file($thumb)) {
        return 'thumb-'.$width.'-'.$info['basename'];
    }

    $img = new Image();
    if (is_file($file)) {
        $img->crop($file, $width, $hight, 3, true);
        $img->save($thumb);
    }
    return 'thumb-'.$width.'-'.$info['basename'];
}

// 用户头像处理
function avatar($id = 0)
{
    if ($id == 0) {
        $id = Auth::id();
    }

    $avatar = Aike\User\User::find($id)->avatar;
    if (is_file(upload_path('avatar').'/'.$avatar)) {
        $src = URL::to('uploads/avatar').'/'.$avatar;
    } else {
        $src = URL::to('assets/').'/images/a1.jpg';
    }
    return $src;
}

/**
 * 检查账户是否超级管理员
 */
function is_admin()
{
    return Auth::user()->admin == 1 ? true : false;
}

/**
 * 计算年龄使用
 */
function date_year($date)
{
    if ($date == '0000-00-00') {
        return 0;
    }

    $d = new Carbon\Carbon($date);
    return $d->diffInYears();
}

/**
 * 计算剩余时间
 */
function remain_time($start, $end, $format = '%y年%m个月%d天%h小时%i分钟')
{
    if ($start == 0 || $end == 0) {
        return '';
    }
    $start = Carbon\Carbon::createFromTimeStamp($start);
    $end  = Carbon\Carbon::createFromTimeStamp($end);
    $diff = $start->diff($end);
    return $format == '' ? $diff : $diff->format($format);
}

function time_day_hour($time) 
{
    $second = time() - $time;
    $day = floor($second / (3600 * 24));
    // 除去整天之后剩余的时间
    $second = $second % (3600 * 24);
    $hour = floor($second / 3600);
    return $day.'天'.$hour.'小时';
}

/**
 * 获取人性化的时间
 */
function human_time($time)
{
    return Carbon\Carbon::createFromTimeStamp($time)
    ->diffForHumans();
}

/**
* 人性化文件大小格式
*
* @param  int $bytes 文件字节
* @return string     字符串
*/
function human_filesize($bytes)
{
    $s = ['B', 'KB', 'MB', 'GB', 'TB'];
    for ($f = 0; $bytes >= 1024 && $f < 4; $f++) {
        $bytes /= 1024;
    }
    return number_format((int)$bytes, 2).$s[$f];
}

/**
 * 生产时间范围
 * 格式: 2012-8-20 - 2012-8-28
 */
function date_range($first, $last, $step = '+1 day', $format = 'Y-m-d')
{
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
    while ($current <= $last) {
        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}

// 时间戳格式化
function format_datetime($value, $default = '', $format = 'Y-m-d H:i')
{
    if ($value instanceof Carbon\Carbon) {
        $value = $value->getTimestamp();
    }

    if ($default) {
        $data = $default;
    }
    if ($value) {
        $data = $value;
    }

    if (strlen($data) != 10) {
        return '';
    }
    
    return $data ? date($format, $data) : '';
}

// 时间戳格式化到日期
function format_date($value, $default = '', $format = 'Y-m-d')
{
    return format_datetime($value, $default, $format);
}

// 时间戳格式化时间
function format_time($value, $default = '', $format = 'H:i')
{
    return format_datetime($value, $default, $format);
}

/**
 * 数字金额转换成大写金额
 */
function str_rmb($money)
{
    // 四舍五入
    $money = round($money, 2);

    if ($money <= 0) {
        return '零元';
    }

    $units   = array('','拾','佰','仟','','万','亿','兆');
    $amount  = array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
    // 拆分小数点
    $arr     = explode('.', $money);
    // 翻转整数
    $money = strrev($arr[0]);

    // 获取数字的长度
    $length = strlen($money);

    for ($i = 0; $i < $length; $i++) {
        // 获取大写数字
        $int[$i] = $amount[$money[$i]];

        // 获取整数位
        if (!empty($money[$i])) {
            $int[$i] .= $units[$i % 4];
        }

        // 取整
        if ($i % 4 == 0) {
            $int[$i] .= $units[4 + floor($i / 4)];
        }
    }
    $con = isset($arr[1]) ? '元'. $amount[$arr[1][0]] .'角'. $amount[$arr[1][1]] .'分' : '元整';
    // 整合数组为字符串
    return implode('', array_reverse($int)) . $con;
}

/**
 * 根据参数自动获取模块控制器和方法组合 URL
 */
function url_build($path = null, $params = [])
{
    $module     = Request::module();
    $controller = Request::controller();
    $action     = Request::action();

    if (empty($path)) {
        $path = $module.'/'.$controller.'/'.$action;
    } else {
        $count = substr_count($path, '/');
        if ($count == 0) {
            $path = $module.'/'.$controller.'/'.$path;
        } elseif ($count == 1) {
            $path = $module.'/'.$path;
        }
    }

    if ($params) {
        $path = $path.'?'.http_build_query($params);
    }
    return URL::to($path);
}

/**
 * 组合URL使用referer
 */
function url_referer($path = null, $params = [], $referer = 1)
{
    // 模块内的跳转条件
    if ($referer) {
        $module     = Request::module();
        $controller = Request::controller();
        $action     = Request::action();

        if (empty($path)) {
            $uri = $module.'_'.$controller.'_'.$action;
        } else {
            $count = substr_count($path, '_');
            if ($count == 0) {
                $uri = $module.'_'.$controller.'_'.$path;
            } elseif ($count == 1) {
                $uri = $module.'_'.$path;
            }
        }
        $uri = Session::pull('referer_'.$uri);
        if ($uri) {
            return $uri;
        }
    }
    return url_build($path, $params);
}

/**
 * Laravel的url
 */
function url($path = null, $params = [], $appends = [])
{
    $params = array_merge($params, $appends);
    return url_build($path, $params);
}

/**
 * 查找指定的字符串，支持逗号分隔多个字符
 */
function array_find($data, $key)
{
    $key = array_filter(explode(',', $key));
    if (empty($key)) {
        return false;
    }
    // 不是数组进行分割
    is_array($data) or $data = explode(',', $data);
    $data = array_filter($data);
    if (array_intersect($data, $key)) {
        return true;
    }
    return false;
}

/**
 * 数组重新按指定键排序
 */
function array_by($items, $key = 'id')
{
    $maps = [];
    if (empty($items)) {
        return $maps;
    }

    foreach ($items as $item) {
        $maps[$item[$key]] = $item;
    }
    return $maps;
}

/** 把嵌套的数组转换到扁平化 */
function reduce_tree($arr, $level = -1)
{
    static $tree = [];
    $level++;
    foreach ($arr as $k => $v) {
        $v['level'] = $level;
        if ($v['children']) {
            $children = $v['children'];
            unset($v['children']);
            $tree[] = $v;
            reduce_tree($children, $level);
        } else {
            $tree[] = $v;
        }
    }
    return $tree;
}

/**
 * 把扁平数组格式化成嵌套数组
 */
function array_tree($items, $key = 'name', $id = 'id', $parentId = 'parent_id', $children = 'children')
{
    $items = is_array($items) ? $items : $items->toArray();
    $tree = $map = array();
    foreach ($items as $item) {
        $item['text']    = $item[$key];
        $item['folder']  = false;
        $item['isLeaf']  = true;
        $item['key']     = $item['id'];
        $map[$item[$id]] = $item;
    }
    
    foreach ($items as $item) {
        if (isset($map[$item[$parentId]])) {
            $map[$item[$parentId]]['folder'] = true;
            $map[$item[$parentId]]['isLeaf'] = false;
            $map[$item[$parentId]][$children][] = &$map[$item[$id]];
        } else {
            $tree[] = &$map[$item[$id]];
        }
    }
    unset($map, $items);
    return $tree;
}

/**
* 重建树形结构的左右值
*
* @var $parent_id 构建的开始id
*/
function tree_rebuild($table, $parent_id = 0, $left = 0)
{
    // 左值 +1 是右值
    $right = $left + 1;

    // 获得这个节点的所有子节点
    $rows = DB::table($table)->where('parent_id', $parent_id)
    ->orderBy('sort', 'asc')
    ->get(['id', 'parent_id', 'lft', 'rgt']);

    if (sizeof($rows)) {
        foreach ($rows as $row) {
            // 这个节点的子$right是当前的右值，这是由treeRebuild函数递增
            $right = tree_rebuild($table, $row['id'], $right);
        }
    }

    // 更新左右值
    DB::table($table)->where('id', $parent_id)->orderBy('sort', 'asc')
    ->update(['lft'=>$left, 'rgt'=>$right]);
    
    // 返回此节点的右值+1
    return $right + 1;
}

function array_nest(&$items, $text = 'name')
{
    if (empty($items)) {
        return;
    }

    $tree = [];
    foreach ($items as $item) {
        $item['layer_level'] = 0;
        $item['layer_paths'] = $item['id'];
        $item['parent'] = [$item['id']];
        $item['child'] = [$item['id']];
        $item['layer_childs']= $item['id'];
        $item['layer_html']  = '';
        $item['layer_space'] = '';

        $item['text'] = $item[$text];
        $tree[$item['id']] = $item;
    }

    foreach ($items as $item) {
        if (isset($tree[$item['parent_id']])) {
            $tree[$item['id']]['text'] = $tree[$item['parent_id']]['text'].'/'.$tree[$item['id']]['text'];
            
            $tree[$item['id']]['layer_html'] = $tree[$item['parent_id']]['layer_html'].'<span class="layer">|&ndash; </span>';
            $tree[$item['id']]['layer_space'] = $tree[$item['parent_id']]['layer_space'].'　';
            $tree[$item['id']]['layer_level'] = $tree[$item['parent_id']]['layer_level'] + 1;
            
            $tree[$item['id']]['layer_paths'] = $tree[$item['parent_id']]['layer_paths'].','.$tree[$item['id']]['layer_paths'];
            $tree[$item['parent_id']]['layer_childs'] = $tree[$item['id']]['layer_childs'].','.$tree[$item['parent_id']]['layer_childs'];

            $tree[$item['id']]['parent'] = array_merge($tree[$item['parent_id']]['parent'], $tree[$item['id']]['parent']);
            $tree[$item['parent_id']]['child'] = array_merge($tree[$item['parent_id']]['child'], $tree[$item['id']]['child']);
        }
    }
    return $tree;
}

/**
 * 百度编辑器
 */
function ueditor($name = 'content', $value = '', $config = array())
{
    static $loaded;
    if (empty($loaded)) {
        $e[] = '<script type="text/javascript">window.UEDITOR_HOME_URL = "'.URL::to('assets/vendor/ueditor').'/";</script>';
        $e[] = '<script type="text/javascript" src="'.URL::to('assets/vendor/ueditor/ueditor.config.js').'"></script>';
        $e[] = '<script type="text/javascript" src="'.URL::to('assets/vendor/ueditor/ueditor.all.min.js').'"></script>';
        $loaded = true;
    }
    $e[] = '<script type="text/plain" name="'.$name.'" id="'.$name.'">'.$value.'</script>';
    $e[] = '<script type="text/javascript">var editor = UE.getEditor("'.$name.'",{initialFrameHeight:180,focus:true,initialFrameWidth:"100%"});</script>';
    return join("\n", $e);
}

/**
 * 获取选项
 */
function option($key, $value = '')
{
    static $items  = [];
    static $values = [];

    if (empty($items[$key])) {
        $parent = DB::table('option')->where('value', $key)->first();
        if ($parent === null) {
            return [];
        }
        $items[$key]  = DB::table('option')->where('parent_id', $parent['id'])->orderBy('sort', 'asc')->get(['name', 'value as id']);
        $values[$key] = array_by($items[$key], 'id');
    }

    if (func_num_args() == 2) {
        return $values[$key][$value]['name'];
    } else {
        return $items[$key];
    }
}

/**
 * 获取省市县
 */
function get_region($id)
{
    $row = DB::table('region')->where('id', $id)->first();
    return $row['name'];
}

/**
 * 获取单用户数据
 */
function get_user($id = 0, $field = '', $letter = true)
{
    static $users = [];

    $args = func_num_args();

    if (empty($users)) {
        $users = DB::table('user')
        ->get(['id', 'department_id', 'role_id', 'username', 'nickname', 'email', 'mobile', 'birthday', 'gender']);
        $users = array_by($users);
    }

    if ($args == 0) {
        return $users;
    }

    if ($args == 1) {
        return $users[$id];
    }

    if ($field == 'nickname' && $letter == true) {
        return '<button type="button" class="option" data-toggle="dialog-form" data-title="私信" data-url="'.url('user/message/create', ['user_id'=>$id]).'" data-id="user_message-form-edit">'.$users[$id][$field].'</button>';
    } else {
        return $users[$id][$field];
    }
}

/**
 * 获取单角色数据
 */
function get_department($id = 0, $field = '')
{
    static $departments = [];

    if (empty($departments)) {
        $departments = Aike\User\Department::getAll();
    }

    if (func_num_args() == 0) {
        return $departments;
    }

    return $field == '' ? $departments[$id] : $departments[$id][$field];
}

/* 读取表格内容 */
function readExcel($filename, $encode = 'utf-8')
{
    // 设置以Excel5格式(Excel97-2003工作簿)
    $reader = PHPExcel_IOFactory::createReader('Excel5');
    // 载入excel文件
    $PHPExcel = $reader->load($filename);
    // 读取第一個工作表
    $sheet = $PHPExcel->getSheet(0);
    // 取得总行数
    $highestRow = $sheet->getHighestRow();
    // 取得总列数
    $highestColumm = $sheet->getHighestColumn();
     
    /** 循环读取每个单元格的数据 */
    // 行数是以第1行开始
    for ($row = 1; $row <= $highestRow; $row++) {
        // 列数是以A列开始
        for ($column = 'A'; $column <= $highestColumm; $column++) {
            $dataset[$row][] = (string)$sheet->getCell($column.$row)->getValue();
        }
    }
    return $dataset;
}

/* 导出表格 */
function writeExcel($columns, $data, $filename)
{
    $obj = new PHPExcel();
    $obj->setActiveSheetIndex(0);
    $obj->getActiveSheet()->setTitle('sheet0');

    // 设置单元格宽度
    $obj->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);

    $j = 0;
    // 设置第一行表格样式和名字
    foreach ($columns as $column) {
        $obj->getActiveSheet()->getStyleByColumnAndRow($j, 1)->getFont()->setBold(true);
        $obj->getActiveSheet()->setCellValueByColumnAndRow($j, 1, $column['label']);
        $j++;
    }

    $row = 2;
    foreach ($data as $i => $rows) {
        $col = 0;
        foreach ($columns as $key => $column) {
            $obj->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rows[$column['name']]);
            $col++;
        }
        $row++;
    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.urlencode($filename).'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

// 判断客户端类型
function isMobile()
{
    return preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackbe‌​rry|iemobile|bolt|bo‌​ost|cricket|docomo|f‌​one|hiptop|mini|oper‌​a mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|‌​webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// 显示商品图片
function goodsImage($v)
{
    $image = $v['image'] ? $v['image'] : 'products/'.$v['id'].'.jpg';
    $image_path = upload_path(). '/'. $image;
    if (is_file($image_path)) {
        $thumb = thumb($image_path, 100, 100);
        $file = pathinfo($image);
        $thumb = $public_url.'/uploads/'.$file['dirname'].'/'.$thumb;
        return '<a class="goods-image" rel="'.$public_url."/uploads/".$v['image'].'"><img class="thumbnail thumb-md goods-thumb" src="'.$thumb.'"></a>';
    } else {
        $thumb = $public_url.'/assets/images/default_img.png';
        return '<img class="thumbnail thumb-md goods-thumb" src="'.$thumb.'">';
    }
}

// 构建搜索下拉菜单数据
function search_select($data, $key = 'id', $value = 'name')
{
    $res = [];
    if (is_array($data)) {
        foreach ($data as $row) {
            $res[] = ['id' => $row[$key], 'name' => $row[$value]];
        }
    }
    return json_encode($res, JSON_UNESCAPED_UNICODE);
}

// 记录操作日志
function action_log($table, $table_id, $uri, $edit, $title = '')
{
    $modules = [
        'article'      => '企业公告',
        'work_process' => '工作流程',
    ];

    if (is_numeric($edit)) {
        $type = $edit > 0 ? '办理' : '添加';
    } else {
        $type = $edit;
    }

    $module = Request::module();
    $node   = $modules[$table];

    if ($table == 'work_process') {
        $description = auth()->user()->nickname.'在'.date('Y-m-d H:i').$type.'了id为'.$table_id.'的'.$title;
    } else {
        $description = auth()->user()->nickname.'在'.date('Y-m-d H:i').$type.'了id为'.$table_id.'的'.$node;
    }

    DB::table('action_log')->insert([
        'table'       => $table,
        'table_id'    => $table_id,
        'uri'         => $uri,
        'node'        => $module,
        'description' => $description,
    ]);
}

function db_instr($field, $str, $prefix = ',', $suffix = ',')
{
    return "instr(concat('$prefix', $field, '$suffix'), '".$prefix.$str.$suffix."') > 0";
}

// 获取当前控制器访问资源列表
function authorize_current_assets()
{
    static $authorize = [];

    if (empty($authorize)) {
        $access = Aike\User\UserAsset::getNowRoleAssets();
        foreach ($access as $k => $v) {
            $authorize[$k]['users']  = Aike\User\User::authoriseAccess($k);
            $authorize[$k]['access'] = $v;
        }
    }
    return $authorize;
}

// 判断当前控制器是否具有权限
function authorize_action($action, $value)
{
    $authorize = authorize_current_assets();

    $asset = $authorize[$action];
    if (empty($asset)) {
        return false;
    }

    if ($asset['access'] < 4) {
        if (in_array($value, $asset['users']) === true) {
            return true;
        }
    }

    if ($asset['access'] == 4) {
        return true;
    }

    return false;
}

function get_device_type()
{
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 'other';
    if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
        $type = 'ios';
    }
    if (strpos($agent, 'android')) {
        $type = 'android';
    }
    return $type;
}

function is_weixin()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

function is_wxwork()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

function create_token($userId, $day = 7)
{
    $payload = array(
        'sub' => $userId,
        'iat' => time(),
        // $day 天有效
        'exp' => time() + ($day * 24 * 60 * 60),
    );
    return JWT::encode($payload, config('app.key'));
}
