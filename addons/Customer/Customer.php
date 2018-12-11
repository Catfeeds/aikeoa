<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Customer extends BaseModel
{
    protected $table = 'customer';

    public function user()
    {
        return $this->belongsTo(\Aike\User\User::class);
    }

    public function circle()
    {
        return $this->belongsTo(\Aike\Customer\Circle::class);
    }
    
    public function contacts()
    {
        return $this->hasMany(\Aike\Customer\Contact::class);
    }
}
