<?php namespace Aike\Workflow\Workflow\Process;

use BaseModel;

class Data extends BaseModel
{
    protected $table = 'work_process_data';

    public function user()
    {
        return $this->belongsTo('Aike\User\User');
    }
}
