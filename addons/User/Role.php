<?php namespace Aike\User;

use Aike\Index\BaseModel;

class Role extends BaseModel
{
    protected $table = 'role';

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
}
