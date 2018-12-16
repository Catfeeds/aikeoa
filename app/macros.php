<?php

use Illuminate\Database\Query\Builder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

// 设置 Carbon 语言
Carbon::setLocale('zh');

// 自定义模板引擎标签
Blade::extend(function ($view) {
    $view = preg_replace('/\{\{\:(.+)\}\}/', '<?php ${1}; ?>', $view);
    $view = preg_replace('/(?<!\w)(\s*)@datetime\(\s*(.*)\)/', '<?php echo format_datetime($2); ?>', $view);
    $view = preg_replace('/(?<!\w)(\s*)@date\(\s*(.*)\)/', '<?php echo format_date($2); ?>', $view);
    $view = preg_replace('/(?<!\w)(\s*)@number\(\s*(.*)\)/', '<?php echo number_format($2); ?>', $view);
    $view = preg_replace('/(?<!\w)(\s*)@age\(\s*(.*)\)/', '<?php echo date_year($2); ?>', $view);
    return $view;
});

Collection::macro('toNested', function ($text = 'name', $byId = 'id') {
    $me = $this->keyBy($byId);
    array_nest($me, $text);
    return $me;
});

Request::macro('isWxwork', function () {
    return is_wxwork();
});

Request::macro('module', function ($default = 'index') {
    return Request::segment(1, $default);
});

Request::macro('controller', function ($default = 'index') {
    return Request::segment(2, $default);
});

Request::macro('action', function ($default = 'index') {
    return Request::segment(3, $default);
});

Builder::macro('search', function ($search) {
    list($condition, $value) = search_condition($search);

    if ($condition == 'between') {
        $this->whereBetween($search['field'], $value);

    } elseif ($search['condition'] == 'date2') {
        $this->whereBetween($search['field'], $value);

    } elseif ($search['condition'] == 'second') {
        $this->whereBetween($search['field'], strtotime($value));

    } elseif ($search['condition'] == 'second2') {
        $this->whereBetween($search['field'], $value);

    } elseif ($condition == 'not_between') {
        $this->whereNotBetween($search['field'], $value);

    } elseif ($condition == 'dialog') {
        $this->whereIn($search['field'], $value);

    } elseif ($condition == 'in') {
        $this->whereIn($search['field'], $value);

    } elseif ($search['field'] == 'customer.circle_id') {
        if ($value[0]) {
            $this->where($search['field'], $value[0]);
        }
        if ($value[1]) {
            $this->where('customer.id', $value[1]);
        }

    } elseif ($condition == 'birthday' || $condition == 'birthbetween') {
        $this->whereRaw('DATE_FORMAT('.$search['field'].',"%m-%d") between ? and ?', $value);

    } elseif ($condition == 'pacs') {
        $this->where($search['field'], 'like', '%'. join("\n", $value).'%');

    } elseif ($search['field'] == 'user.province_id' || $condition == 'area') {
        if ($value[0]) {
            $this->where('user.province_id', $value[0]);
        }
        if ($value[1]) {
            $this->where('user.city_id', $value[1]);
        }
        if ($value[2]) {
            $this->where('user.county_id', $value[2]);
        }

    } else {
        $this->where($search['field'], $condition, $value);

    }
    return $this;
});

Builder::macro('toTreeChildren', function ($select = ['node.*']) {

    // 重新定义表结构
    $this->from(DB::raw($this->from.' as node, '.$this->from.' as parent'))
    ->select($select)
    ->selectRaw('(COUNT(parent.id)-1) level')
    ->whereRaw('node.lft BETWEEN parent.lft AND parent.rgt')
    ->groupBy('node.id')
    ->orderBy('node.lft', 'asc');
    $res = $this->get();

    $rows = array();

    if ($res->count()) {
        foreach ($res as $v) {
            $v['children'][] = $v['id'];
            
            $v['layer'] = str_repeat('|&ndash;', $v['level']);

            $rows[$v['id']] = $v;

            if ($rows[$v['parent_id']]['children']) {
                $rows[$v['parent_id']]['children'] = array_merge($rows[$v['parent_id']]['children'], $v['children']);
            }
        }

        foreach ($rows as $row) {
            if ($row['parent_id'] > 0) {
                $children = array_merge($rows[$row['parent_id']]['children'], $row['children']);
                $rows[$row['parent_id']]['children'] = array_unique($children);
            }
        }
    }
    return $rows;
});

Builder::macro('permission', function ($field, $user = null, $null = false, $all = true, $children = false, $created_by = '') {
    if ($user === null) {
        $user = auth()->user();
    }

    if ($null) {
        $where[] = "ifnull($field, '') = ''";
    }
    if ($all) {
        $where[] = db_instr($field, 'all');
    }
    $where[] = db_instr($field, 'u'. $user['id']);
    $where[] = db_instr($field, 'r'. $user['role_id']);
    $where[] = db_instr($field, 'd'. $user['department_id']);
    
    if ($created_by) {
        $where[] = $created_by.'='.$user['id'];
    }
    
    if ($children) {
        $dep = explode(',', $us['deptpath']);
        foreach ($dep as $deps) {
            $_deps   = str_replace(array('[',']'), array('',''), $deps);
            $where[] = db_instr($fids, 'd'.$_deps);
        }
    }
    $sql = join(' or ', $where);
    if ($sql) {
        $sql = '('.$sql.')';
    }

    $this->whereRaw($sql);
    return $this;
});
