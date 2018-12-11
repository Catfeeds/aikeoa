<?php namespace Aike\Model;

use Schema;
use DB;
use URL;
use Auth;
use Config;
use Request;

use Module;

use Aike\Index\BaseModel;
use Aike\Index\Attachment;
use Aike\System\Setting;

class Field extends BaseModel
{
    protected $table = 'model_field';

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public static function title()
    {
        return [
            'text'         => 'text(单行文本)',
            'textarea'     => 'textarea(多行文本)',
            'password'     => 'password(密码文本)',
            'option'       => 'option(选项菜单)',
            'radio'        => 'radio(单选按钮)',
            'select'       => 'select(下拉菜单)',
            'select2'      => 'select2(下拉菜单)',
            'autocomplete' => 'autocomplete(自动完成)',
            'checkbox'     => 'checkbox(复选框)',
            'dialog'       => 'dialog(对话框)',
            'urd'          => 'urd(权限对话框)',
            'dataset'      => 'dataset(数据集)',
            'auto'         => 'auto(宏控件)',
            'calc'         => 'calc(计算控件)',
            'editor'       => 'editor(编辑器)',
            'date'         => 'date(日期时间)',
            'image'        => 'image(单图上传)',
            'images'       => 'images(多图上传)',
            'file'         => 'file(文件上传)',
            'files'        => 'files(多文件上传)',
            'address'      => 'address(省市区)',
            'label'        => 'label(标签)',
            'sn'           => 'sn(流水号)',
        ];
    }

    public static function tr_text($setting, $param)
    {
        $title = $param['title'];
        $name  = $param['name'];
        $tips  = $param['tips'];
        $type  = $param['type'] == '' ? 'text' : $param['type'];
        $items = $param['items'];
        $value = isset($setting[$name]) ?  $setting[$name] : $param['value'];

        $str = '<div class="form-group">
        <label class="col-sm-2 control-label" for="'.$name.'">'.$title.'</label>
        <div class="col-sm-10 control-text">';
        if ($type == 'text') {
            $str .= '<input type="text" class="form-control input-inline input-sm" value="' . $value . '" name="setting['.$name.']">';
        }
        if ($type == 'textarea') {
            $str .= '<textarea class="form-control input-inline input-sm" value="" name="setting['.$name.']">' . $value . '</textarea>';
        }
        if ($type == 'radio') {
            foreach ($items as $item) {
                $str .= '<label class="radio-inline"><input type="radio" value="'.$item['value'].'" name="setting['.$name.']" ' . ($value == $item['value'] ? 'checked' : '') . '> '.$item['name'].'</label>';
            }
        }
        if ($type == 'select') {
            $str .= '<select class="form-control input-inline input-sm" name="setting['.$name.']">
            <option value=""> - </option>';
            foreach ($items as $k => $v) {
                $selected = $value == $k ? ' selected' : '';
                $str .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
            }
            $str .= '</select>';
        }
        if ($type == 'align') {
            $items = ['left' => 'left', 'center' => 'center', 'right' => 'right'];
            $str .= '<select class="form-control input-inline input-sm" name="setting['.$name.']">
            <option value=""> - </option>';
            foreach ($items as $k => $v) {
                $selected = $value == $k ? ' selected' : '';
                $str .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
            }
            $str .= '</select>';
        }
        $str .= ' <font color="gray"> '.$tips.'</font>
        </div>
        </div>';
        return $str;
    }

    public static function tr_texts($setting, $params)
    {
        $str = '';
        foreach ($params as $param) {
            $str .= Field::tr_text($setting, $param);
        }
        return $str;
    }

    /**
     * 以下函数作用于字段添加/修改部分
     */
    public static function form_text($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
            ['title' => '行计事件', 'name' => 'row_count', 'tips' => ''],
            ['title' => '总计事件', 'name' => 'total_count', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    public static function form_calc($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    public static function form_auto($setting = [])
    {
        $types = [
            'sys_date'       => '当前日期，形如 1999-01-01',
            'sys_date_cn'    => '当前日期，形如 2009年1月1日',
            'sys_date_cn_s1' => '当前日期，形如 2009年',
            'sys_date_cn_s2' => '当前年份，形如 2009',
            'sys_date_cn_s3' => '当前日期，形如 2009年1月',
            'sys_date_cn_s4' => '当前日期，形如 1月1日',
            'sys_time'       => '当前时间',
            'sys_datetime'   => '当前日期+时间',
            'sys_week'       => '当前星期中的第几天，形如 星期一',
            'sys_userid'     => '当前用户ID',
            'sys_nickname'   => '当前用户姓名',
            'sys_department_name'      => '当前用户部门',
            'sys_user_position'        => '当前用户职位',
            'sys_user_position_assist' => '当前用户辅助职位',
            'sys_nickname_date'        => '当前用户姓名+日期',
            'sys_nickname_datetime'    => '当前用户姓名+日期+时间',
            'sys_sql'                  => '来自sql查询语句',
        ];

        $params = [
            ['title' => '类型', 'name' => 'type', 'items' => $types, 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    public static function form_password($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    public static function form_textarea($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => '高度', 'name' => 'height', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    public static function form_editor($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => '高度', 'name' => 'height', 'tips' => 'px'],
            ['title' => '类型', 'name' => 'type', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '完整模式'], ['value' => 0, 'name' => '简洁模式']], 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '', 'type' => 'textarea'],
        ];
        return Field::tr_texts($setting, $params);
    }

    public static function form_select($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '选项列表', 'name' => 'content', 'tips' => '格式：选项名称1|选项值1 (回车换行)', 'type' => 'textarea'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 地址选项
    public static function form_address($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 标签
    public static function form_label($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 单据编号
    public static function form_sn($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '规则', 'name' => 'rule', 'tips' => '格式: {Y}{M}{D}-{SN,4}'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 单选按钮
    public static function form_radio($setting = [])
    {
        return Field::form_select($setting);
    }

    // 多选按钮
    public static function form_checkbox($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '选项列表', 'name' => 'content', 'tips' => '格式：选项名称1|选项值1 (回车换行)', 'type' => 'textarea'],
            ['title' => '默认值', 'name' => 'default', 'tips' => ''],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 图片上传
    public static function form_image($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => '高度', 'name' => 'height', 'tips' => 'px'],
            ['title' => '大小', 'name' => 'size', 'tips' => 'MB'],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 文件上传
    public static function form_file($setting = [])
    {
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '格式', 'name' => 'type', 'tips' => '多个格式以,号分开，如：zip,rar,tar'],
            ['title' => '文件表名', 'name' => 'table', 'tips' => '例如: attachment'],
            ['title' => '文件子路径', 'name' => 'path', 'tips' => '例如: calendar'],
            ['title' => '大小', 'name' => 'size', 'tips' => 'MB'],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 多文件上传
    public static function form_files($setting = [])
    {
        return Field::form_file();
    }

    // 选项菜单
    public static function form_option($setting = [])
    {
        $options = DB::table('option')->where('parent_id', 0)->pluck('name', 'value');
        $params = [
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '数据源', 'name' => 'type', 'items' => $options, 'tips' => '', 'type' => 'select'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '多个选中值以分号分隔“,”，格式：选中值1,选中值2'],
            ['title' => '其他选项', 'name' => 'single', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '单选'], ['value' => 0, 'name' => '多选']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 数据集
    public static function form_dataset($setting = [])
    {
        $types = [];
        $dialogs = Module::dialogs();
        foreach ($dialogs as $table => $dialog) {
            $types[$table] = $dialog['name'];
        }
        $types['custom'] = '自定义';
        $types['sql']    = 'SQL';

        $displays = array(
            'dialog' => '弹窗',
            'select' => '列表',
        );

        $params = [
            ['title' => '数据源', 'name' => 'type', 'items' => $types, 'tips' => '', 'type' => 'select'],
            ['title' => '显示类型', 'name' => 'display', 'items' => $displays, 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '值字段', 'name' => 'id', 'tips' => '格式：此表字段=选项字段'],
            ['title' => '显示字段', 'name' => 'name', 'tips' => '格式：此表字段=选项字段'],
            ['title' => '映射字段', 'name' => 'mapping', 'tips' => '格式：此表字段=选项字段 (回车换行)'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '多个选中值以分号分隔“,”，格式：选中值1,选中值2'],
            ['title' => '其他选项', 'name' => 'single', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '单选'], ['value' => 0, 'name' => '多选']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // select2插件
    public static function form_select2($setting = [])
    {
        $types = [];
        $dialogs = Module::dialogs();
        foreach ($dialogs as $table => $dialog) {
            $types[$table] = $dialog['name'];
        }
        $types['sql'] = 'SQL';

        $params = [
            ['title' => '数据源', 'name' => 'type', 'items' => $types, 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '查询关联字段', 'name' => 'relation', 'tips' => '限制主表字段查询值'],
            ['title' => '映射字段', 'name' => 'mapping', 'tips' => '格式：此表字段=选项字段 (回车换行)'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '多个选中值以分号分隔“,”，格式：选中值1,选中值2'],
            ['title' => '其他选项', 'name' => 'single', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '单选'], ['value' => 0, 'name' => '多选']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // autocomplete插件
    public static function form_autocomplete($setting = [])
    {
        $types = [];
        $dialogs = Module::dialogs();
        foreach ($dialogs as $table => $dialog) {
            $types[$table] = $dialog['name'];
        }
        $types['sql'] = 'SQL';

        $params = [
            ['title' => '数据源', 'name' => 'type', 'items' => $types, 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '查询关联字段', 'name' => 'relation', 'tips' => '限制主表字段查询值'],
            ['title' => '映射字段', 'name' => 'mapping', 'tips' => '格式：此表字段=选项字段 (回车换行)'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '多个选中值以分号分隔","，格式：选中值1,选中值2'],
            ['title' => '其他选项', 'name' => 'single', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '单选'], ['value' => 0, 'name' => '多选']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 对话框
    public static function form_dialog($setting = [])
    {
        $types = [];
        $dialogs = Module::dialogs();
        foreach ($dialogs as $table => $dialog) {
            $types[$table] = $dialog['name'];
        }
        $types['sql'] = 'SQL';

        $params = [
            ['title' => '数据源', 'name' => 'type', 'items' => $types, 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '值字段', 'name' => 'id', 'tips' => '格式：此表字段=选项字段'],
            ['title' => '显示字段', 'name' => 'name', 'tips' => '格式：此表字段=选项字段'],
            ['title' => '映射字段', 'name' => 'mapping', 'tips' => '格式：此表字段=选项字段 (回车换行)'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '多个选中值以分号分隔“,”，格式：选中值1,选中值2'],
            ['title' => '其他选项', 'name' => 'single', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '单选'], ['value' => 0, 'name' => '多选']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 权限对话框
    public static function form_urd($setting = [])
    {
        $types = [];
        $dialogs = Module::dialogs();
        foreach ($dialogs as $table => $dialog) {
            $types[$table] = $dialog['name'];
        }
        $types['sql'] = 'SQL';

        $params = [
            //['title' => '数据源', 'name' => 'type', 'items' => $types, 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            //['title' => '值字段', 'name' => 'id', 'tips' => '格式：此表字段=选项字段'],
            //['title' => '显示字段', 'name' => 'name', 'tips' => '格式：此表字段=选项字段'],
            //['title' => '映射字段', 'name' => 'mapping', 'tips' => '格式：此表字段=选项字段 (回车换行)'],
            //['title' => '映射字段', 'name' => 'mapping', 'tips' => '格式：此表字段=选项字段 (回车换行)'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '多个选中值以分号分隔“,”，格式：选中值1,选中值2'],
            ['title' => '其他选项', 'name' => 'single', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '单选'], ['value' => 0, 'name' => '多选']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 日期
    public static function form_date($setting = [])
    {
        $params = [
            ['title' => '显示格式', 'name' => 'type', 'value' => 'Y-m-d H:i:s', 'tips' => '格式: Y-m-d H:i:s 表示: 2001-02-13 11:20:20'],
            ['title' => '数据格式', 'name' => 'save', 'items' => ['date' => '日期', 'u' => '时间戳'], 'tips' => '', 'type' => 'select'],
            ['title' => 'Align', 'name' => 'align', 'tips' => '', 'type' => 'align'],
            ['title' => '宽度', 'name' => 'width', 'tips' => 'px'],
            ['title' => 'css', 'name' => 'css', 'tips' => 'input-inline'],
            ['title' => '默认值', 'name' => 'default', 'tips' => '', 'type' => 'radio', 'items' => [['value' => 1, 'name' => '当前时间'], ['value' => 0, 'name' => '空']]],
        ];
        return Field::tr_texts($setting, $params);
    }

    // 字段扩展设置
    public static function content_field($field)
    {
        // 配置
        $setting = isset($field['setting']) ? json_decode($field['setting'], true) : $field;

        $field['data'] = $field['table'].'['.$field['field'].']';
        $field['key']  = $field['table'].'_'.$field['field'];

        $attribute = $field['attribute'];

        $attribute['class'] = ['form-control','input-sm'];

        if ($field['form_type'] == 'textarea') {
        } else {
            // $attribute['class'][] = 'input-inline';
        }

        if ($setting['css']) {
            $attribute['class'][] = $setting['css'];
        }

        if ($setting['width']) {
            $attribute['style'][] = 'width:'.$setting['width'].'px';
        }

        if ($setting['height']) {
            $attribute['style'][] = 'height:'.$setting['height'].'px';
        }

        if ($field['validate']) {
            $attribute['validate'] = $field['validate'];
        }

        $attribute['id']    = $field['key'];
        $attribute['name']  = $field['data'];

        $field['attribute'] = $attribute;
        $field['setting']   = $setting;

        return $field;
    }

    // 字段属性组合
    public static function content_attribute($attributes)
    {
        foreach ($attributes as $k => $v) {
            if ($k == 'class') {
                $attributes[$k] = $k.'="'.join(' ', $v).'"';
            } elseif ($k == 'style') {
                $attributes[$k] = $k.'="'.join(';', $v).'"';
            } else {
                $attributes[$k] = $k.'="'.$v.'"';
            }
        }
        return join(' ', $attributes);
    }

    public static function content_label($field, $content = '')
    {
        return $content;
    }

    /**
     * 以下函数作用于发布内容部分
     */
    public static function content_text($field, $content = '')
    {
        $field = Field::content_field($field);
        
        $type = $field['is_hide'] == 0 ? 'text' : 'hidden';

        if ($field['is_read'] == 1 && $field['is_hide'] == 1) {
            return '';
        }

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
        }
        return $field['is_show'] ? $content : '<input type="'.$type.'" value="' . $content . '" ' . Field::content_attribute($field['attribute']) . ' />';
    }

    public static function content_sn($field, $content = '')
    {
        $field = Field::content_field($field);
        $setting = $field['setting'];

        $time = time();
        $user = auth()->user();

        $items = [
            '{y}'  => date('y', $time),
            '{Y}'  => date('Y', $time),
            '{M}'  => date('m', $time),
            '{D}'  => date('d', $time),
            '{H}'  => date('H', $time),
            '{I}'  => date('i', $time),
            '{S}'  => date('s', $time),
            '{U}'  => $user['nickname'],
            '{UD}' => $user->department['title'],
            '{UR}' => $user->role['title'],
            '{UP}' => $user->position['title'],
        ];

        // 生成单据编码
        if (preg_match('/{SN,(\d+)}/', $setting['rule'], $sn)) {
            $items['{SN,'.$sn[1].'}'] = str_pad((int)$field['data_sn'] + 1, $sn[1], '0', STR_PAD_LEFT);
        }

        if ($content == '') {
            $content = str_replace(array_keys($items), array_values($items), $setting['rule']);
        }

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
        }
        return $field['is_show'] ? $content : '<input type="text" value="' . $content . '" ' . Field::content_attribute($field['attribute']) . ' />';
    }

    public static function content_auto($field, $content = '')
    {
        $field = Field::content_field($field);
        $setting = $field['setting'];

        $t = isset($setting['type']) ? $setting['type'] : '';

        $time = time();
        $user = auth()->user();

        $items = [
            '{Y}'    => date('Y', $time),
            '{M}'    => date('m', $time),
            '{D}'    => date('d', $time),
            '{H}'    => date('H', $time),
            '{I}'    => date('i', $time),
            '{S}'    => date('s', $time),
            'sys_nickname' => $user['nickname'],
            'sys_nickname_datetime' => $user['nickname'].' '.date('Y-m-d H:i'),
            'sys_department_name'   => $user->department['title'],
            '{UR}'   => $user->role['title'],
            '{UP}'   => $user->position['title'],
        ];

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
        } else {
            if ($field['is_show'] == 0) {
                $content = $items[$t];
            }

            if ($field['is_auto']) {
                $field['attribute']['readonly'] = 'readonly';
            }
        }

        return $field['is_show'] ? $content : '<input type="text" value="' . $content . '" ' . Field::content_attribute($field['attribute']) . ' />';
    }

    public static function content_address($field, $content = '')
    {
        $field = Field::content_field($field);

        if ($field['is_show']) {
            return $content;
        }

        $_content = explode("\n", $content);

        $class = ['form-control','input-sm'];

        if ($field['is_read']) {
            //$class[] = 'readonly';
        }
        $attribute[] = 'class="'. join(' ', $class).'"';

        $attr = join(' ', $attribute);

        $field['attribute']['placeholder'] = '街道';

        $str = '<div class="form-inline"><select '.$attr.' id="'.$field['key'].'_0" name="'.$field['data'].'[0]"></select>';
        $str .= '&nbsp;<select '.$attr.' id="'.$field['key'].'_1" name="'.$field['data'].'[1]"></select>';
        $str .= '&nbsp;<select '.$attr.' id="'.$field['key'].'_2" name="'.$field['data'].'[2]"></select>';
        $str .= '&nbsp;<input '.$attr.' type="text" id="'.$field['key'].'_3" name="'.$field['data'].'[3]" placeholder="街道" value="' . $_content[3] . '" />';
        $str .= '</div>';
        $pcas = 'new pcas("'.$field['key'].'_0", "'.$field['key'].'_1", "'.$field['key'].'_2", "'.$_content[0].'", "'.$_content[1].'", "'.$_content[2].'");';
        $str .= '<script type="text/javascript">'.$pcas.'</script>';
        
        return $str;
    }

    public static function content_password($field, $content = '')
    {
        $field = Field::content_field($field);

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
        }
        return $field['is_show'] ? $content : '<input type="password" ' . Field::content_attribute($field['attribute']) . ' />';
    }

    public static function content_textarea($field, $content = '')
    {
        $field = Field::content_field($field);

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
        }
        return $field['is_show'] ? $content : '<textarea ' . Field::content_attribute($field['attribute']) . '>' . $content . '</textarea>';
    }

    public static function content_editor($field, $content = '')
    {
        return ueditor($name, $content);
    }

    public static function content_select($field, $content = '')
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];
        $str = '<select ' . Field::content_attribute($field['attribute']) . '>';

        $select = explode("\n", $setting['content']);
        foreach ($select as $t) {
            $n = $v = $selected = '';
            list($n, $v) = explode('|', $t);
            $v = is_null($v) ? trim($n) : trim($v);
            $selected = $v == $content ? ' selected="selected"' : '';
            $str.= "<option value='" . $v . "'" . $selected . ">" . $n . "</option>";
        }
        return $str . '</select>';
    }

    public static function content_dataset($field, $content = '')
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];

        if ($field['is_show']) {
            return $content;
        }

        $content = $content == 0 ? '' : $content;
        return \Dialog::user($setting['type'], $field['data'], $content);
    }

    public static function content_autocomplete($field, $content = '', $row = [])
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];
        
        if ($field['is_show']) {
            return $content;
        }

        $dialog = Module::dialogs($setting['type']);

        $value = $content == 0 ? '' : $content;
        $rows = [];
        if ($value) {
            $ids = explode(',', $value);
            $table = $dialog['table'];
            $join = $dialog['join'];
            if ($join) {
                $rows = DB::table($table)
                ->LeftJoin('user', 'user.id', '=', $table.'.user_id')
                ->whereIn($table.'.id', $ids)
                ->pluck($dialog['field'], $table.'.user_id');
            } else {
                $rows = DB::table($table)
                ->whereIn('id', $ids)
                ->pluck($dialog['field'], $table.'.id');
            }
        }

        $name = $field['data'];
        $id = str_replace(['[',']'], ['_',''], $name);

        $names = $values = [];
        foreach ($rows as $k => $v) {
            $names[]  = $v;
            $values[] = $k;
        }

        $relations = explode(',', $setting['relation']);
        $query = [];
        if ($relations) {
            foreach ($relations as $relation) {
                if ($relation) {
                    list($k, $v) = explode('=', $relation);
                    $query[$v] = $row[$k];
                }
            }
        }

        $disabled = $readonly == 0 ? '' : 'disabled="disabled"';

        $_names  = join(',', $names);
        $_values = join(',', $values);
        $input   = empty($_values) ? '' : 'ac_result';

        $_name = str_replace([']'], ['_name]'], $name);

        $html[] = '
            <input type="text" class="form-control input-sm '.$input.'" name="'.$_name.'" id="'.$id.'_name" value="'.join(',', $names).'">
            <input type="hidden" name="'.$name.'" id="'.$id.'" value="'.join(',', $values).'">
        ';

        $query['type'] = 'autocomplete';
        $html[] = '<script type="text/javascript">
            $(function () {
                $("#'.$id.'_name").autocomplete("'.url($dialog['url'], $query).'", {
                    delay: 150,
                    parse: function(data) {
                        if(data == "No Records.") {
                            return [];
                        }
                        return $.map(data, function(row) {
                            return {
                                data: row,
                                value: row.id,
                                result: row.name
                            }
                        });
                    },
                    formatItem: function (item) {
                        return item.name;
                    },
                    formatMatch: function(item) {
                        return item.name;
                    },
                    formatResult: function(item) {
                        return item.name;
                    }
                });
            });
        </script>';
        return join("\n", $html);
    }

    public static function content_select2($field, $content = '', $row = [])
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];
        if ($field['is_show']) {
            return $content;
        }

        $dialog = Module::dialogs($setting['type']);

        $value = $content == 0 ? '' : $content;
        $rows = [];
        if ($value) {
            $ids = explode(',', $value);
            $table = $dialog['table'];
            $join = $dialog['join'];
            if ($join) {
                $rows = DB::table($table)
                ->LeftJoin('user', 'user.id', '=', $table.'.user_id')
                ->whereIn($table.'.id', $ids)
                ->pluck($dialog['field'], $table.'.user_id');
            } else {
                $rows = DB::table($table)->whereIn('id', $ids)->pluck($dialog['field'], $table.'.id');
            }
        }

        $name = $field['data'];
        $id = str_replace(['[',']'], ['_',''], $name);

        $options = [];
        foreach ($rows as $k => $v) {
            if (in_array($k, $ids)) {
                $options[] = '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
            }
        }

        $relations = explode(',', $setting['relation']);
        $query = [];
        if ($relations) {
            foreach ($relations as $relation) {
                if ($relation) {
                    list($k, $v) = explode('=', $relation);
                    $query[$v] = $row[$k];
                }
            }
        }

        $disabled = $readonly == 0 ? '' : 'disabled="disabled"';

        $html[] = '<select name="'.$name.'" '.$disabled.' class="form-control input-sm" id="'.$id.'">'.join('', $options).'</select>';

        $width = '100';

        $select2['options'] = [
            'placeholder' => '请选择'.$field['name'],
            'width'       => $width.'%',
            'search_key'  => $dialog['table'],
            'multiple'    => !$setting['single'],
            'ajax'        => [
                'url' => url($dialog['url'], $query),
            ],
        ];
        $html[] = '<script type="text/javascript">select2List.'.$id.'='.json_encode($select2).';</script>';
        return join("\n", $html);
    }

    public static function content_dialog($field, $content = '')
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];
        
        list($_name, $__name) = explode(':', $setting['name']);

        $dialog = Module::dialogs($setting['type']);

        $value = $content == 0 ? '' : $content;

        $rows = '';

        if ($value) {
            $ids = explode(',', $value);

            $table = $dialog['table'];
            $join  = $dialog['join'];

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

        $name = $field['data'];
        $id   = str_replace(['[',']'], ['_',''], $field['data']);

        $multi = (int)!$setting['single'];

        if ($field['is_show']) {
            return $rows;
        } else {
            if ($field['is_hide'] == 1) {
                return '<input type="hidden" value="' . $content . '" ' . Field::content_attribute($field['attribute']) . ' />';
            } else {
                $width = '100%';

                if ($field['is_read']) {
                    if ($setting['css'] == 'input-inline') {
                        $width = '153px';
                    }
                    if ($setting['width']) {
                        $width = $setting['width'].'px';
                    }

                    $html[] = '<div class="select-group" style="width:'.$width.';"><div class="form-control input-sm readonly" id="'.$id.'_text">'.$rows.'</div>';
                } else {
                    if ($setting['css'] == 'input-inline') {
                        $width = '225px';
                    }
                    if ($setting['width']) {
                        $width = $setting['width'].'px';
                    }

                    $option = "dialogUser('$dialog[name]','$dialog[url]','$id','$multi');";
                    $html[] = '<div class="select-group input-group" style="width:'.$width.';"><div class="form-control input-inline input-sm" style="cursor:pointer;" onclick="'.$option .'" id="'.$id.'_text">'.$rows.'</div>';
                    $html[] = '<div class="input-group-btn">';
                    $html[] = '<button type="button" onclick="'.$option.'" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>';
                    $html[] = '<button type="button" data-toggle="dialog-search-clear" data-id="'.$id.'" class="btn btn-sm btn-default"><i class="fa fa-remove"></i></button>';
                    $html[] = '</div>';
                }
                $html[] = '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'">';
                $html[] = '</div>';

                return join("\n", $html);
            }
        }
    }

    public static function content_urd($field, $content = '', $row = [])
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];

        $value = $content;

        $name = $field['data'];
        $name_value = str_replace('_id', '_text', $field['data']);
        $id   = str_replace(['[',']'], ['_',''], $field['data']);
        $text = $row[str_replace('_id', '_text', $field['field'])];

        $multi = (int)!$setting['single'];

        if ($field['is_show']) {
            return $text;
        } else {
            if ($field['is_hide'] == 1) {
                return '<input type="hidden" value="' . $content . '" ' . Field::content_attribute($field['attribute']) . ' />';
            } else {
                $width = '100%';

                if ($field['is_read']) {
                    if ($setting['css'] == 'input-inline') {
                        $width = '153px';
                    }
                    if ($setting['width']) {
                        $width = $setting['width'].'px';
                    }

                    $html[] = '<div class="select-group" style="width:'.$width.';"><div class="form-control input-sm readonly" id="'.$id.'_text">'.$text.'</div>';
                } else {
                    if ($setting['css'] == 'input-inline') {
                        $width = '225px';
                    }
                    if ($setting['width']) {
                        $width = $setting['width'].'px';
                    }

                    $option = "dialogUser('$dialog[name]','index/api/dialog','$id','$multi');";
                    $html[] = '<div class="select-group input-group" style="width:'.$width.';"><div class="form-control input-inline input-sm" style="cursor:pointer;" onclick="'.$option .'" id="'.$id.'_text">'.$text.'</div>';
                    $html[] = '<div class="input-group-btn">';
                    $html[] = '<button type="button" onclick="'.$option.'" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>';
                    $html[] = '<button type="button" data-toggle="dialog-search-clear" data-id="'.$id.'" class="btn btn-sm btn-default"><i class="icon icon-trash"></i></button>';
                    $html[] = '</div>';
                }
                $html[] = '<input type="hidden" id="'.$id.'_value" name="'.$name_value.'" value="'.$text.'">';
                $html[] = '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'">';
                $html[] = '</div>';

                return join("\n", $html);
            }
        }
    }

    public static function content_option($field, $content = '')
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];

        // 子表
        if ($field['is_show']) {
            return option($setting['type'], $content);
        }

        if ($setting['single'] == 0) {
            $field['attribute']['multiple'] = 'multiple';
        }

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
            $field['attribute']['onfocus']  = 'this.defaultIndex=this.selectedIndex;';
            $field['attribute']['onchange'] = 'this.selectedIndex=this.defaultIndex;';
        }

        $id = $field['attribute']['id'];

        $width = '100%';
        if ($setting['css'] == 'input-inline') {
            $width = '153px';
        }
        if ($setting['width']) {
            $width = $setting['width'].'px';
        }

        $placeholder_text_multiple = '请选择'.$field['name'];

        //$js = '<script>$(function() {$("#'.$id.'").chosenField({placeholder_text_multiple:" - ",width:"'.$width.'"}); });</script>';

        $str = $js.'<select ' . Field::content_attribute($field['attribute']) . '>';
        $str .= "<option value=''> - </option>";

        $options = option($setting['type']);
        foreach ($options as $option) {
            $selected = $option['id'] == $content ? ' selected' : '';
            $str.= "<option value='" . $option['id'] . "'" . $selected . ">" . $option['name'] . "</option>";
        }
        return $str . '</select>';
    }

    public static function content_date($field, $content = '')
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];

        $type = isset($setting['type']) ? $setting['type'] : 'Y-m-d H:i:s';
        $save = isset($setting['save']) ? $setting['save'] : 'date';

        $time_sign = array(
            'Y'  => 'yyyy',
            'y'  => 'yy',
            'm'  => 'mm',
            'm'  => 'MM',
            'M'  => 'M',
            'n'  => 'm',
            'd'  => 'dd',
            'j'  => 'd',
            'l'  => 'DD',
            'jS' => 'D',
            'W'  => 'W',
            'H'  => 'HH',
            'h'  => 'hh',
            'G'  => 'H',
            'g'  => 'h',
            'i'  => 'mm',
            's'  => 'ss',
            'z'  => 'z',
            'c'  => 'c',
            'r'  => 'r',
            'a'  => 'a',
            't'  => 't',
            'A'  => 'A'
        );
        $time_format = strtr($type, $time_sign);

        $content = empty($content) ? ($setting['default'] == 1 ? date($type): '') : ($save == 'date' ? $content : date($type, $content));

        if ($field['is_show']) {
            return $content;
        }

        if ($field['is_read']) {
            $field['attribute']['readonly'] = 'readonly';
        } else {
            $field['attribute']['onclick'] = "WdatePicker({dateFmt:'".$time_format."'});";
        }
        return '<input type="text" value="' .$content. '" ' . Field::content_attribute($field['attribute']) . ' />';
    }

    public static function content_radio($field, $content = '')
    {
        $field = Field::content_field($field);
        $str = '';
        foreach ($select as $t) {
            $attribute = $field['attribute'];
            $n    = $v = $selected = '';
            list($n, $v) = explode('|', $t);
            $v    = is_null($v) ? trim($n) : trim($v);
            if ($v == $content) {
                $attribute['checked'] = 'checked';
            }
            $str.= $n . '&nbsp;<input type="radio" name="'. $field['data'] . '" value="' . $v . '" ' . Field::content_attribute($attribute) . ' />&nbsp;&nbsp;';
        }
        return $str;
    }

    public static function content_checkbox($name, $content = '', $field = '')
    {
        // 配置
        $setting = isset($field['setting']) ? json_decode($field['setting'], true) : $field;
        $default = $setting['default'];
        $content = is_null($content) ? ($default ? explode(',', $default) : '') : string2array($content);
        $select  = explode(chr(13), $setting['content']);
        $str     = '';
        foreach ($select as $t) {
            $n    = $v = $selected = '';
            list($n, $v) = explode('|', $t);
            $v    = is_null($v) ? trim($n) : trim($v);
            $selected = is_array($content) && in_array($v, $content) ? ' checked' : '';
            $str.= $n . '&nbsp;<input type="checkbox" name="data[' . $name . '][]" value="' . $v . '" ' . $selected . ' />&nbsp;&nbsp;';
        }
        return $str;
    }

    public static function content_image($name, $content = '', $field = '')
    {
        // 配置
        $setting  = isset($field['setting']) ? json_decode($field['setting'], true) : $field;
        // 必填字段
        $required = isset($field['not_null']) && $field['not_null'] ? ' required' : '';
        $size     = (int)$setting['size'];
        $height   = isset($setting['height']) ? $setting['height'] : '';
        $width    = isset($setting['width']) ? $setting['width'] : '';
        $str      = '<input type="text" class="input-text" size="50" value="' . $content . '" name="data[' . $name . ']" id="fc_' . $name . '" ' . $required . ' />
	    <input type="button" style="width:66px;cursor:pointer;" class="button" onClick="preview(\'fc_' . $name . '\')" value="' . trans('a-image') . '" />
	    <input type="button" style="width:66px;cursor:pointer;" class="button" onClick="uploadImage(\'fc_' . $name . '\',\'' . $width . '\',\'' . $height . '\',\'' . $size . '\')" value="' . trans('a-mod-119') . '" />';
        return $str;
    }

    public static function content_file($name, $content = '', $field = '')
    {
        // 配置
        $setting  = isset($field['setting']) ? json_decode($field['setting'], true) : $field;
        // 必填字段
        $required = isset($field['not_null']) && $field['not_null'] ? ' required' : '';
        $type     = base64_encode($setting['type']);
        $size     = (int)$setting['size'];
        return '<input type="text" class="input-text" size="50" value="' . $content . '" name="data[' . $name . ']" id="fc_' . $name . '" ' . $required . ' />
	    <input type="button" style="width:66px;cursor:pointer;" class="button" onClick="file_info(\'fc_' . $name . '\')" value="' . trans('a-mod-164') . '" />
	    <input type="button" style="width:66px;cursor:pointer;" class="button" onClick="uploadFile(\'fc_' . $name . '\',\'' . $type . '\',\'' . $size . '\')" value="' . trans('a-mod-120') . '" />';
    }

    public static function content_files($field, $content = '')
    {
        $field   = Field::content_field($field);
        $setting = $field['setting'];

        $table = isset($setting['table']) && $setting['table'] ? $setting['table'] : 'attachment';

        $_setting = Setting::pluck('value', 'key');

        $key      = str_replace(['[',']'], ['.',''], $field['data']);
        $input_id = str_replace('.', '_', $key);

        $attachment = \Aike\Index\Attachment::edit($content, $key);

        if ($field['is_read'] || $field['is_show']) {
            $str = '<div id="fileQueue_'.$input_id.'" class="uploadify-queue">';

            if (count((array)$attachment['rows'])) {
                foreach ($attachment['rows'] as $file) {
                    $str .= '<div id="file_queue_'.$file['id'].'" class="uploadify-queue-item">
                        <span class="file-name"><span class="icon icon-paperclip"></span> <a href="javascript:uploader.file(\'file_queue_'.$file['id'].'\');">'.$file['name'].'</a></span>
                        <span class="file-size">('.human_filesize($file['size']).')</span>';

                    if (in_array($file['type'], ['pdf'])) {
                        $str .= '<a href="'.URL::to('uploads').'/'.$file['path'].'" class="btn btn-xs btn-default" target="_blank">预览</a>';
                    } elseif (in_array($file['type'], ['jpg','png','gif','bmp'])) {
                        $str .= '<button type="button" data-toggle="dialog-image" data-url="'.URL::to('uploads').'/'.$file['path'].'" data-title="附件预览" class="btn btn-xs btn-default">预览</button>';
                    } else {
                        $str .= '<a class="btn btn-xs btn-default" href="'.url('index/attachment/download', ['id'=>$file['id']]).'">下载</a>';
                    }
                    $str .= '</div><div class="clear"></div>';
                }
            }
            $str .= '</div>';
            return $str;
        } else {
            $qrcode = url('index/attachment/qrcode', ['path' => Request::module(), 'key' => $key, 'x-auth-token' => create_token(auth()->id(), 7)]);
            $str = '<script id="uploader-item-tpl" type="text/html">
                <div id="file_draft_<%=id%>" class="uploadify-queue-item">
                    <span class="file-name"><span class="text-danger hinted" title="草稿状态">!</span> <a href="javascript:uploader.file(\'file_draft_<%=id%>\', \''.URL::to('uploads').'/<%=path%>\');"><%=name%></a></span>
                    <span class="file-size">(<%=size%>)</span>
                    <span class="cancel"><a class="option gray" style="color:#666;" href="javascript:uploader.cancel(\'file_draft_<%=id%>\');"><i class="fa fa-times-circle"></i></a></span>
                    <input type="hidden" class="'.$input_id.' id" name="'. $field['data'] . '[]" value="<%=id%>" />
                </div>
                <div class="clear"></div>
            </script>
            <div class="uploadify-queue">
            <script src="'.URL::to('/assets').'/vendor/jquery.qrcode.min.js"></script>
            <style>
            .layui-layer-tips .layui-layer-content {
                background-color: #eee;
                padding: 8px;
            }
            .layui-layer-tips i.layui-layer-TipsL, .layui-layer-tips i.layui-layer-TipsR {
                border-bottom-color: #eee;
            }
            </style>
            <script type="text/javascript">
            (function($) {
                $("#qrcode_'.$input_id.'").qrcode({
                    render: "canvas",
                    text: "'.$qrcode.'",
                    width: 150,
                    height: 150
                });
                var qr = $("#qrcode_'.$input_id.'").find("canvas")[0];
                var qrimg = "<img src="+ qr.toDataURL() +" />";
                var qrcode_index = 0;
                var timer_'.$input_id.' = null;
                $("#qrcode_'.$input_id.'_btn").on("mouseenter", function() {
                    qrcode_index = layer.tips(qrimg, "#qrcode_'.$input_id.'_btn", {
                        time: 0,
                    });
                    // 激活扫码上传功能
                    if(timer_'.$input_id.' == null) {
                        timer_'.$input_id.' = setInterval(\'FindFile("'.$input_id.'", "'.$key.'")\', 3000);
                    }
                }).on("mouseleave", function() {
                    layer.close(qrcode_index);
                });

                if (window.FindFile == undefined) {
                    function FindFile(inputId, key) {
                        $.post("'.url('index/attachment/draft').'", {key: key}, function(data) {
                            var qrArray = [];
                            var fileDraft = "#fileDraft_" + inputId;
                            var items = $(fileDraft).find(".id");
                            $.each(items, function(i, row) {
                                qrArray.push($(this).val());
                            });
                            $.each(data, function(i, row) {
                                if (qrArray.indexOf(row.id + "") == -1) {
                                    row.size = fileFormatSize(row.size);
                                    var html = template("uploader-item-tpl", row);
                                    $(fileDraft).append(html);
                                }
                            });
                        });
                    }
                    window.FindFile = FindFile;
                }
            })(jQuery);
            </script>
            <a class="btn btn-sm btn-info hinted" title="文件大小限制: '.$_setting['upload_max'].'MB" href="javascript:viewBox(\'attachment\', \'上传\', \''.url('index/attachment/uploader', ['path' => Request::module(), 'key' => $key]).'\');"><i class="fa fa-cloud-upload"></i> 上传</a>
            <a class="btn btn-sm btn-info" id="qrcode_'.$input_id.'_btn" href="javascript:;"><i class="fa fa-qrcode"></i> 扫码上传</a>
            <div id="qrcode_'.$input_id.'" style="display:none;"></div>
            <div class="clear"></div>
            <div id="fileQueue_'.$input_id.'" class="uploadify-queue">';
                
            if (count((array)$attachment['rows'])) {
                foreach ($attachment['rows'] as $file) {
                    $str .= '<div id="file_queue_'.$file['id'].'" class="uploadify-queue-item">
                        <span class="file-name"><span class="icon icon-paperclip"></span> <a href="javascript:uploader.file(\'file_queue_'.$file['id'].'\', \''.URL::to('uploads').'/'.$file['path'].'\');">'.$file['name'].'</a></span>
                        <span class="file-size">('.human_filesize($file['size']).')</span>
                        <span class="cancel"><a class="option gray" href="javascript:uploader.cancel(\'file_queue_'.$file['id'].'\');"><i class="fa fa-times-circle"></i></a></span>
                        <input type="hidden" class="'.$input_id.' id" name="'. $field['data'] . '[]" value="'.$file['id'].'">
                    </div>
                    <div class="clear"></div>';
                }
            }

            $str .= '</div>
                <div id="fileDraft_'.$input_id.'">';

            if (count($attachment['draft'])) {
                foreach ($attachment['draft'] as $file) {
                    $str .= '<div id="queue_draft_'.$file['id'].'" class="uploadify-queue-item">
                        <span class="file-name"><span class="text-danger hinted" title="草稿附件">!</span> <a href="javascript:uploader.file(\'queue_draft_'.$file['id'].'\', \''.URL::to('uploads').'/'.$file['path'].'\');">'.$file['name'].'</a></span>
                        <span class="file-size">('.human_filesize($file['size']).')</span>
                        <span class="cancel"><a class="option gray" href="javascript:uploader.cancel(\'queue_draft_'.$file['id'].'\');"><i class="fa fa-times-circle"></i></a></span>
                        <input type="hidden" class="'.$input_id.' id" name="'. $field['data'] . '[]" value="'.$file['id'].'">
                    </div>
                    <div class="clear"></div>';
                }
            }
            $str .= '</div></div>';
            return $str;
        }
    }
}
