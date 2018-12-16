<?php namespace Aike\User;

use Aike\Index\BaseModel;

class Role extends BaseModel
{
    protected $table = 'role';

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
     * 取得角色列表
     */
    public static function getAll($roleId = 0)
    {
        static $data = null;

        if ($data === null) {
            $data = Role::orderBy('lft', 'asc')->get(['id', 'parent_id', 'name', 'title'])->toNested('title');
        }
        return $roleId > 0 ? $data[$roleId] : $data;
    }

    public function scopeDialog($q, $value)
    {
        return $q->whereIn('id', $value)
        ->pluck('title', 'id');
    }
}
