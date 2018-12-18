<?php namespace Aike\Index;

use DB;
use Input;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Database\Query\Builder as QueryBuilder;

class BaseModel extends Eloquent
{
    public $timestamps = false;

    /**
     * 设置不允许批量赋值的字段
     */
    protected $guarded = ['id'];

    /**
     * 获取连接的新查询生成器实例
     *
     * @return \App\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        return new QueryBuilder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );
    }

    /**
     * 重写日期格式
     */
    protected function getDateFormat()
    {
        return 'U';
    }

    public function getDates()
    {
        return [];
    }

    public function scopeWithAt($query, $relation, array $columns)
    {
        return $query->with([$relation => function ($query) use ($columns) {
            $query->select(array_merge(['id'], $columns));
        }]);
    }

    public function step()
    {
        return $this->belongsTo('Aike\Model\Step', 'step_sn', 'sn');
    }

    public function scopeGetStep($query, $sn = 1)
    {
        $table = $this->table;
        return \Aike\Model\Model::leftJoin('model_step', 'model_step.model_id', '=', 'model.id')
        ->where('model.table', $table)
        ->where('model_step.sn', $sn)
        ->select('model_step.*')
        ->first();
    }

    public function scopeGetSteps($query)
    {
        $table = $this->table;
        return \Aike\Model\Model::leftJoin('model_step', 'model_step.model_id', '=', 'model.id')
        ->where('model.table', $table)
        ->select('model_step.*')
        ->orderBy('model_step.sn', 'asc')
        ->get();
    }
    
    public function scopeStepAt($query)
    {
        $table = $this->table;

        // 过滤草稿状态
        $query->whereRaw('('.$table.'.created_by=? and '.$table.'.step_number=1 or '.$table.'.step_number>1)', [auth()->id()]);

        return $query->with(['step' => function ($q) use ($table) {
            $q->LeftJoin('model', 'model.id', '=', 'model_step.model_id')
            ->where('model.table', $table)
            ->select('model_step.*');
        }]);
    }

    /**
     * 查询 Dialog 字段显示的值，其他模型可复写此方法
     */
    public function scopeDialog($query, $value)
    {
        return $query->whereIn('id', $value)->get();
    }

    /**
     * 取得所有层级
     *
     * @var string $columns 选择字段
     */
    public function scopeTree($query, $select = ['node.*'])
    {
        $table = $this->table;

        $rows = $this->from(DB::raw($this->from.' as node, '.$this->from.' as parent'))
        ->select($select)
        ->selectRaw('(COUNT(parent.id)-1) level')
        ->whereRaw('node.lft BETWEEN parent.lft AND parent.rgt')
        ->groupBy('node.id')
        ->orderBy('node.lft', 'asc');

        $result = array();

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $row['layer'] = str_repeat('|&ndash;', $row['level']);
                $result[$row['id']] = $row;
            }
        }
        return $result;
    }
    
    /**
     * 取得指定层级集
     *
     * @var int $id 条件编号
     * $type int 0.包含自己的所有子类, 1.包含自己所有父类
     */
    public function scopeTreeById($query, $id, $type = 0)
    {
        $table = $this->table;
        $rows = $this->from(DB::raw($table.' as node, '.$table.' as parent'))
        ->whereRaw($type == 0 ? 'node.lft BETWEEN parent.lft AND parent.rgt' : 'parent.rgt BETWEEN node.lft AND node.rgt')
        ->where('parent.id', $id)
        ->groupBy('node.id')
        ->orderBy('node.lft', 'asc')
        ->select(['node.*'])
        ->get();

        return $rows;
    }

    public function scopeToTree($query, $text = 'name', $selected = 0, $state = 'closed')
    {
        if ($selected > 0) {
            $selected = $query->treeSinglePath($selected);
        }
        $nodes = $query->get()->toArray();

        // 格式化的树
        $tree = [];

        //临时扁平数据
        $map = [];

        foreach ($nodes as $node) {
            $node['text'] = $node[$text];
            $node['state'] = ($state == 'closed' && empty($selected[$node['id']])) ? 'closed' : 'open';
            $map[$node['id']] = $node;
        }
        unset($selected);

        foreach ($nodes as $node) {
            if (isset($map[$node['parent_id']])) {
                $map[$node['parent_id']]['children'][] = &$map[$node['id']];
            } else {
                $tree[] = &$map[$node['id']];
            }
        }
        unset($map, $nodes);
        return $tree;
    }

    /**
     * 将所有子节点的ID压入根节点
     */
    public function scopeToChild($query, array $select = array('*'))
    {
        $items = $query->get($select)->keyBy('id')->toArray();
        $id = 0;
        foreach ($items as &$item) {
            $path = explode(',', $item['path']);
            $item['parent'] = $path;
        }
        return $items;
    }

    /**
     * 返回当前节点的完整路径
     */
    public function scopeTreeSinglePath($query, $id)
    {
        $table = $this->table;
        $rows = $this->from(DB::raw($table.' as node, '.$table.' as parent'))
        ->whereRaw('node.lft BETWEEN parent.lft AND parent.rgt')
        ->where('node.id', $id)
        ->orderBy('node.lft', 'asc')
        ->select(['parent.*'])
        ->get()->keyBy('id');
        return $rows;
    }

    /**
     * 重建树形结构的左右值
     *
     * @var $parent_id 构建的开始id
     */
    public function scopeTreeRebuild($query, $parent_id = 0, $left = 0)
    {
        // 左值 +1 是右值
        $right = $left + 1;

        // 获得这个节点的所有子节点
        $rows = $this->where('parent_id', $parent_id)
        ->orderBy('sort', 'asc')
        ->get(['id', 'parent_id', 'lft', 'rgt']);

        if ($rows->count()) {
            foreach ($rows as $row) {
                // 这个节点的子$right是当前的右值，这是由treeRebuild函数递增
                $right = $this->TreeRebuild($row->id, $right);
            }
        }

        // 更新左右值
        $this->where('id', $parent_id)->orderBy('sort', 'asc')
        ->update(['lft'=>$left, 'rgt'=>$right]);

        // 返回此节点的右值+1
        return $right + 1;
    }

    /**
     * 设置by字段的筛选类型
     */
    public function scopeSetBy($query, $header)
    {
        $table = $header['table'];
        $by = Input::get('by');
        switch ($by) {
            // 我的客户
            case 'me':
                $query->where($table.'.created_by', auth()->id());
                break;
            // 我的下属
            case 'sub':
                break;
            // 我关注的
            case 'follow':
                break;
            // 我共享的
            case 'myshare':
                break;
            // 共享给我的
            case 'share':
                break;
            // 本日创建
            case 'day':
                $query->whereRaw("TO_DAYS(FROM_UNIXTIME(".$table.".created_at,'%Y%m%d')) = TO_DAYS(NOW())");
                break;
            // 本周创建
            case 'week':
                $query->whereRaw("YEARWEEK(FROM_UNIXTIME(".$table.".created_at,'%Y%m%d')) = YEARWEEK(NOW())");
                break;
            // 本月创建
            case 'month':
                $query->whereRaw("FROM_UNIXTIME(".$table.".created_at,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m')");
                break;
        }
        if ($by == 'trash') {
            $query->where($table.'.deleted_by', '>', 0);
        } else {
            $query->where($table.'.deleted_by', 0);
        }
        return $query;
    }
}
