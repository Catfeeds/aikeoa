<?php namespace Aike\Project;

use Aike\Index\BaseModel;

class Task extends BaseModel
{
    protected $table = 'project_task';

    public function project()
    {
        return $this->belongsTo('Aike\Project\Project');
    }

    public function users()
    {
        return $this->belongsToMany('Aike\User\User', 'project_task_user', 'task_id', 'user_id');
    }

    public function syncUsers($gets)
    {
        $users = $gets[$gets['type'].'_users'];
        $users = $users == '' ? [] : explode(',', $users);
        $this->users()->sync($users);
    }
}
