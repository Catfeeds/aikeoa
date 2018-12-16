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

    public function scopeDialog($q, $value)
    {
        return $q->leftJoin('user', 'user.id', '=', 'customer.user_id')
        ->whereIn('customer.id', $value)
        ->pluck('user.nickname', 'customer.id');
    }
}
