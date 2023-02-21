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
     * @return true
     */
    public function isJwtToken(): bool
    {
        foreach ($this->getHeaders() as $header) {
            if ($header->key === 'Authorization')
                return true;
        }
        return false;
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