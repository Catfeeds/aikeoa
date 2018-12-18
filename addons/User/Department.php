<?php namespace Aike\User;

use Aike\Index\BaseModel;

class Department extends BaseModel
{
    protected $table = 'department';

    static public $bys = [
        'name'  => 'by',
        'items' => [
            ['value' => '', 'name' => '全部'],
            ['value' => 'divider'],
            ['value' => 'day', 'name' => '今日创建'],
            ['value' => 'week', 'name' => '本周创建'],
            ['value' => 'month', 'name' => '本月创建'],
        ]
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * 取得部门列表
     */
    public static function getAll($departmentId = 0)
    {
        static $data = null;

        if ($data === null) {
            $data = Department::orderBy('lft', 'asc')->get(['id', 'parent_id', 'name'])->toNested('name');
        }
        return $departmentId > 0 ? $data[$departmentId] : $data;
    }

    public function scopeDialog($q, $value)
    {
        return $q->whereIn('id', $value)
        ->pluck('name', 'id');
    }
}
