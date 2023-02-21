<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

class Header
{
    /**
     * @var string
     */
    public string $key;

    /**
     * @var mixed
     */
    public mixed $value;

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __construct(string $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}