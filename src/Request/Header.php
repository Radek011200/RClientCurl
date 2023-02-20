<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

class Header
{
    public string $key;
    public mixed $value;

    public function __construct(string $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}