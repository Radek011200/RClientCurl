<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp;

use Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Radek011200\CurlClientPhp\Request\HandleRequest;
use Radek011200\CurlClientPhp\Request\HttpMethod;
use Radek011200\CurlClientPhp\Request\Options;
use Radek011200\CurlClientPhp\Request\PrepareRequest;

class Curl
{
    /**
     * @param string $url
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Get(string $url, Options $options = null): ResponseInterface
    {
        return $this->createRequest(PrepareRequest::prepare(HttpMethod::GET, $url), $options);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Post(string $url, Options $options = null): ResponseInterface
    {
        return $this->createRequest(PrepareRequest::prepare(HttpMethod::POST, $url), $options);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Delete(string $url, Options $options = null): ResponseInterface
    {
        return $this->createRequest(PrepareRequest::prepare(HttpMethod::DELETE, $url), $options);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Put(string $url, Options $options = null): ResponseInterface
    {
        return $this->createRequest(PrepareRequest::prepare(HttpMethod::PUT, $url), $options);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Patch(string $url, Options $options = null): ResponseInterface
    {
        return $this->createRequest(PrepareRequest::prepare(HttpMethod::PATCH, $url), $options);
    }

    /**
     * @param Request $request
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    private function createRequest(Request $request, Options $options = null): ResponseInterface
    {
        $request = (new HandleRequest())->handleRequest($request, $options);
        return (new CurlHandler())->handle($request, $options);
    }
}
