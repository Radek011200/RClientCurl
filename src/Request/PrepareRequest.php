<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class PrepareRequest
{
    /**
     * @param string $method
     * @param string $url
     * @return Request
     */
    public static function prepare(string $method, string $url): Request
    {
        $request = new Request($method, $url);
        $uri = new Uri($url);

        $request->withUri($uri);
        return $request;
    }
}