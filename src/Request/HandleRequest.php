<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;

class HandleRequest
{
    /**
     * @param Request $request
     * @param Options $options
     * @param array $body
     * @return RequestInterface
     */
    public function handleRequest(Request $request, Options $options, array $body): RequestInterface
    {
        if($options->getHeaders() !== null) {
            foreach ($options->getHeaders() as $header) {
                $request = $request->withAddedHeader($header->key, $header->value);
            }
        }

        if($body) {
            $request = $request->withBody(Utils::streamFor(json_encode($body)));
        }

        return $request;
    }
}