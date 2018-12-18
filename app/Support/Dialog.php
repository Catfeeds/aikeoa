<?php

class Dialog
{
    public static $items = [
        'user' => [
            'title' => '用户',
            'table' => 'user',
            'field' => 'nickname',
            'url'   => 'user/user/dialog',
        ],
        'role' => [
            'title' => '角色',
            'table' => 'role',
            'field' => 'name',
            'url'   => 'user/role/dialog',
        ],
        'department' => [
            'title' => '部门',
            'table' => 'department',
            'field' => 'name',
            'url'   => 'user/department/dialog',
        ],
        'supplier' => [
            'title' => '供应商',
            'table' => 'supplier',
            'join'  => 'user',
            'field' => 'user.nickname',
            'url'   => 'supplier/supplier/dialog',
        ],
        'customer' => [
            'title' => '客户',
            'table' => 'customer',
            'join'  => 'user',
            'field' => 'user.nickname',
            'url'   => 'customer/customer/dialog',
        ],
        'customer_contact' => [
            'title' => '客户联系人',
            'table' => 'customer_contact',
            'join'  => 'user',
            'field' => 'user.nickname',
            'url'   => 'customer/contact/dialog',
        ],
        'supplier_product' => [
            'title' => '商品',
            'table' => 'product',
            'field' => 'name',
            'url'   => 'supplier/product/dialog',
        ],
        'product' => [
            'title' => '产品',
            'table' => 'product',
            'field' => 'name',
            'url'   => 'product/product/dialog',
        ],
        'promotion' => [
            'title' => '促销',
            'table' => 'promotion',
            'field' => 'id',
            'url'   => 'promotion/promotion/dialog',
        ],
        'circle' => [
            'title' => '客户圈',
            'table' => 'customer_circle',
            'field' => 'name',
            'url'   => 'customer/circle/dialog',
        ],
        'hr' => [
            'title' => '人事档案',
            'table' => 'hr',
            'field' => 'name',
            'url'   => 'hr/hr/dialog',
        ],
        'logistics' => [
            'title' => '物流',
            'table' => 'logistics',
            'field' => 'name',
            'url'   => 'order/logistics/dialog',
        ],
    ];

    public function text($item, $value)
    {
        $dialog = self::$items[$item];

        $rows = '';
        
        if ($value) {
            $ids = explode(',', $value);

            $table = $dialog['table'];
            $join = $dialog['join'];

            if ($join) {
                $rows = DB::table($table)
                ->LeftJoin('user', 'user.id', '=', $table.'.user_id')
                ->whereIn($table.'.id', $ids)
                ->pluck($dialog['field'])->implode(',');
            } else {
                $rows = DB::table($table)
                ->whereIn('id', $ids)
                ->pluck($dialog['field'])->implode(',');
            }
        }
        return $rows;
    }

    public function show($key, $data, $multi = 0, $readonly = 0)
    {
        $id   = $key.'_id';
        $name = $key.'_name';

        if ($readonly == 0) {
            $html[] = '<div class="select-group input-group">';
        } else {
            $html[] = '<div class="select-group">';
        }
        
        if ($readonly == 0) {
            $arrow  = $multi == 1 ? 'icon-group' : 'icon-user';
            $option = "dialogShow('$id','$name','$multi');";
            $html[] = '<div class="form-control input-sm" onclick="'.$option .'" id="'.$name.'">'.$data[$name].'</div>';

            $html[] = '<div class="input-group-btn">';
            $html[] = '<button type="button" onclick="'.$option .'" class="btn btn-sm btn-default"><i class="icon '.$arrow.'"></i></button>';
            $html[] = '<button type="button" data-toggle="dialog-search-clear" data-id="'.$id.'" data-name="'.$name.'" class="btn btn-sm btn-default"><i class="icon icon-trash"></i></button>';
            $html[] = '</div>';
        } else {
            $html[] = '<div class="form-control input-sm" id="'.$name.'">'.$data[$name].'</div>';
        }
        $html[] = '<input type="hidden" id="'.$id.'" name="'.$id.'" value="'.$data[$id].'">';
        $html[] = '</div>';
        return join("\n", $html);
    }

    public static function user($item, $name, $value = '', $multi = 0, $readonly = 0, $width = 'auto')
    {
        $rows = '';

        $dialog = self::$items[$item];

        if ($value) {
            $ids = explode(',', $value);

            $table = $dialog['table'];
            $join = $dialog['join'];

            if ($join) {
                $rows = DB::table($table)
                ->LeftJoin('user', 'user.id', '=', $table.'.user_id')
                ->whereIn($table.'.id', $ids)
                ->pluck($dialog['field'])->implode(',');
            } else {
                $rows = DB::table($table)
                ->whereIn('id', $ids)
                ->pluck($dialog['field'])->implode(',');
            }
        }

        $id = str_replace(['[',']'], ['_',''], $name);
        
        if ($readonly == 0) {
            $width  = is_numeric($width) ? 'width:'.$width.'px;' : '';
            $html[] = '<div class="select-group input-group">';
        } else {
            $html[] = '<div class="select-group">';
        }

        if ($readonly == 0) {
            $arrow  = $multi == 0 ? 'fa-search' : 'fa-search';
            $option = "dialogUser('$dialog[title]','$dialog[url]','$id','$multi');";

            $html[] = '<div class="form-control input-sm" style="'.$width.'cursor:pointer;" onclick="'.$option .'" id="'.$id.'_text">'.$rows.'</div>';

            $html[] = '<div class="input-group-btn">';
            $html[] = '<button type="button" onclick="'.$option .'" class="btn btn-sm btn-default"><i class="fa '.$arrow.'"></i></button>';
            $html[] = '<button type="button" data-toggle="dialog-search-clear" data-id="'.$id.'" class="btn btn-sm btn-default"><i class="fa fa-remove"></i></button>';
            $html[] = '</div>';
        } else {
            $html[] = '<div class="form-control input-sm" id="'.$id.'_text">'.$rows.'</div>';
        }
        $html[] = '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'">';
        $html[] = '</div>';
        return join("\n", $html);
    }

    public function search($data, $query)
    {
        $params = [];
        parse_str($query, $params);

        $defaultParams = [
            'prefix'   => 1,
            'multi'    => 0,
            'readonly' => 0,
            'width'    => '100%',
        ];

        $params = array_merge($defaultParams, $params);
        extract($params);
        
        $_id = str_replace(['[',']'], ['_',''], $id);
        $_name = str_replace(['[',']'], ['_',''], $name);

        $jq = '';
        foreach ($params as $key => $value) {
            $jq .= ' data-'.$key.'="'. $value.'"';
        }

        $params['id']   = str_replace(['[',']'], ['_',''], $id);
        $params['name'] = str_replace(['[',']'], ['_',''], $name);

        $e[] = '<div class="select-group input-group">';
        $e[] = '<div class="form-control input-sm" style="width:'.$params['width'].';cursor:pointer;" data-toggle="dialog-search"'.$jq.' id="'.$params['id'].'_text">'.$data[$name].'</div>';
        $e[] = '<div class="input-group-btn">';
        $e[] = '<button type="button" data-toggle="dialog-search"'.$jq.' class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>';
        $e[] = '<button type="button" data-toggle="dialog-search-clear" data-id="'.$params['id'].'" data-name="'.$params['name'].'" class="btn btn-sm btn-default"><i class="fa fa-remove"></i></button>';
        $e[] = '</div>';
        $e[] = '<input type="hidden" id="'.$params['id'].'" name="'.$id.'" value="'.$data[$id].'">';
        $e[] = '<input type="hidden" id="'.$params['name'].'" name="'.$name.'" value="'.$data[$name].'">';
        $e[] = '</div>';
        return join("\n", $e);
    }
}
