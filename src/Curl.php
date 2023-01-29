<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp;

use Exception;
use Psr\Http\Message\ResponseInterface;

class Curl extends CurlClient
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Get(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl(HttpMethod::GET, $url);
        $request = $this->prepareRequest($request, $options);

        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Post(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl(HttpMethod::POST, $url);
        $request = $this->prepareRequest($request, $options);

        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Delete(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl(HttpMethod::DELETE, $url);
        $request = $this->prepareRequest($request, $options);

        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Put(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl(HttpMethod::PUT, $url);
        $request = $this->prepareRequest($request, $options);

        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function Patch(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl(HttpMethod::PATCH, $url);
        $request = $this->prepareRequest($request, $options);

        return $this->sendRequest($request);
    }
}
