<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;

class HandleRequest
{
    /**
     * @param Request $request
     * @param Options $options
     * @return RequestInterface
     */
    public function handleRequest(Request $request, Options $options): RequestInterface
    {
        if($options->getHeaders() !== null) {
            foreach ($options->getHeaders() as $header) {
                $request = $request->withAddedHeader($header->key, $header->value);
            }
        }

        if($options->getBody()) {
            $request = $request->withBody($options->getBody());
        }

        return $request;
    }
}