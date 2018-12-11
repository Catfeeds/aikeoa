<?php namespace Aike\Workflow;

use Aike\Index\BaseModel;

class Process extends BaseModel
{
    protected $table = 'work_process';

    public function user()
    {
        return $this->belongsTo('Aike\User\User');
    }
}
