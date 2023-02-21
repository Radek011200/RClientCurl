<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

class CurlOpt
{
    /**
     * @var int
     */
    public int $key;

    /**
     * @var mixed
     */
    public mixed $value;

    /**
     * @param int $key
     * @param mixed $value
     */
    public function __construct(int $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}