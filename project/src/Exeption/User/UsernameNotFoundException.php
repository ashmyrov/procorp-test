<?php

namespace App\Exception\User;

use Exception;

class UsernameNotFoundException extends Exception
{
    public function __construct(string $username)
    {
        parent::__construct(sprintf('Username "%s" does not exist.', $username));
    }
}