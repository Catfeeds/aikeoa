<?php namespace Aike\Workflow;

use Aike\Index\BaseModel;

class ProcessData extends BaseModel
{
    protected $table = 'work_process_data';

    public function user()
    {
        return $this->belongsTo('Aike\User\User');
    }
}
