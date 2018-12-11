<?php namespace Aike\Customer;

use Aike\Index\BaseModel;

class Contact extends BaseModel
{
    protected $table = 'customer_contact';

    protected $guarded = ['id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('Aike\User\User');
    }

    public function customer()
    {
        return $this->belongsTo('Aike\Customer\Customer');
    }
}
