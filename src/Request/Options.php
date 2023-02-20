<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Request;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

class Options
{
    /**
     * @var array
     */
    private array $headers = [];

    /*
     * Psr\Http\Message\StreamInterface
     */
    private mixed $body = null;

    /**
     * @var array
     */
    private array $curlOPT = [];

    /**
     * @var array
     */
    private array $basicAuthLoginData = [];


    /**
     * @param CurlOpt $curlOpt
     * @return Options
     */
    public function addCurlOPT(CurlOpt $curlOpt): self
    {
        $this->curlOPT[] = $curlOpt;
        return $this;
    }

    /**
     * @return array
     */
    public function getCurlOPT(): array
    {
        return $this->curlOPT;
    }

    /**
     * @param Header $header
     * @return Options
     */
    public function addHeader(Header $header): self
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $body
     * @return StreamInterface
     */
    public function withBody(array $body): StreamInterface
    {
        $this->body = Utils::streamFor(json_encode($body));
        return $this->body;
    }

    /**
     * @return StreamInterface|null
     */
    public function getBody(): StreamInterface|null
    {
        return $this->body;
    }

    /**
     * @param string $jwtToken
     * @return $this
     */
    public function addJwtToken(string $jwtToken): self
    {
        $this->addHeader(new Header('Authorization', 'Bearer ' . $jwtToken));
        return $this;
    }

    /**
     * @param string $login
     * @param string $password
     * @return $this
     */
    public function addBasicAuthLoginData(string $login, string $password): self
    {
        $this->basicAuthLoginData['login'] = $login;
        $this->basicAuthLoginData['password'] = $password;
        return $this;
    }

    /**
     * @return array
     */
    public function getBasicAuthLogin(): array
    {
        return $this->basicAuthLoginData;
    }

    /**
     * @return bool
     */
    public function isBasicAuthLoginData(): bool
    {
        return boolval($this->basicAuthLoginData);
    }

    /**
     * @return $this
     */
    public function clearBasicAuthLoginData(): self
    {
        $this->basicAuthLoginData = [];
        return $this;
    }
}