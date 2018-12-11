<?php namespace App;

class License
{
    public function check($type, $count)
    {
        $license = [
            'role'     => 9999,
            'user'     => 9999,
            'supplier' => 9999,
            'customer' => 9999,
        ];
        return $count >= $license[$type] ? 1 : 0;
    }
}
