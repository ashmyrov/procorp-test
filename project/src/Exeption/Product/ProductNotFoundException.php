<?php

namespace App\Exception\Product;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct(string $uuid)
    {
        parent::__construct(sprintf('Product with uuid %s not found', $uuid));
    }
}