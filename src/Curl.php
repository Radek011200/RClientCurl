<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp;

use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Radek011200\CurlClientPhp\Request\HandleRequest;
use Radek011200\CurlClientPhp\Request\HttpMethod;
use Radek011200\CurlClientPhp\Request\Options;

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
        return $this->sendRequest($this->prepareRequest(HttpMethod::GET, $url), $options);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @param array $body
     * @return ResponseInterface
     * @throws Exception
     */
    public function Post(string $url, Options $options = null, array $body = []): ResponseInterface
    {
        return $this->sendRequest($this->prepareRequest(HttpMethod::POST, $url), $options, $body);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Delete(string $url, Options $options = null): ResponseInterface
    {
        return $this->sendRequest($this->prepareRequest(HttpMethod::DELETE, $url), $options);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @param array $body
     * @return ResponseInterface
     * @throws Exception
     */
    public function Put(string $url, Options $options = null, array $body = []): ResponseInterface
    {
        return $this->sendRequest($this->prepareRequest(HttpMethod::PUT, $url), $options, $body);
    }

    /**
     * @param string $url
     * @param Options|null $options
     * @param array $body
     * @return ResponseInterface
     * @throws Exception
     */
    public function Patch(string $url,Options $options = null, array $body = []): ResponseInterface
    {
        return $this->sendRequest($this->prepareRequest(HttpMethod::PATCH, $url), $options, $body);
    }

    /**
     * @param Request $request
     * @param Options|null $options
     * @param array $body
     * @return ResponseInterface
     * @throws Exception
     */
    private function sendRequest(Request $request, Options $options = null, array $body = []): ResponseInterface
    {
        $request = (new HandleRequest())->handleRequest($request, $options, $body);
        return (new CurlHandler())->handle($request, $options);
    }

    /**
     * @param string $method
     * @param string $url
     * @return Request
     */
    private function prepareRequest(string $method, string $url): Request
    {
        $request = new Request($method, $url);
        $uri = new Uri($url);

        $request->withUri($uri);
        return $request;
    }
}
