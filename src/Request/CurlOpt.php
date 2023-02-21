<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

class CurlOpt
{
    public int $key;
    public mixed $value;

    public function __construct(int $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}