<?php

use Kalnoy\Nestedset\NodeTrait;

class Nestedset extends BaseModel
{
    use NodeTrait;

    public function getLftName()
    {
        return 'lft';
    }
    
    public function getRgtName()
    {
        return 'rgt';
    }
    
    public function getParentIdName()
    {
        return 'parent_id';
    }
}
