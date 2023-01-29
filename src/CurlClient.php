<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp;

use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurlClient
{
    public function __construct()
    {
        $this->handleResponse = new HandleResponse();
    }

    /**
     * @var HandleResponse
     */
    private HandleResponse $handleResponse;

    /**
     * @var array
     */
    private array $basicAuthLoginData = [];

    /**
     * @var string
     */
    private string $jwtToken;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, (string)$request->getUri());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request->getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);

        if ($this->basicAuthLoginData)
        {
            curl_setopt($curl, CURLOPT_USERPWD, $this->basicAuthLoginData['login'] . ':' . $this->basicAuthLoginData['password']);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            throw new Exception(curl_error($curl));
        }

        return $this->handleResponse->handle($curl,$response);
    }

    /**
     * @param Request $request
     * @param array $options
     * @return RequestInterface
     */
    protected function prepareRequest(Request $request, array $options = []): RequestInterface
    {
        if(isset($options["headers"])) {
            foreach ($options["headers"] as $key => $value) {
                $request = $request->withAddedHeader($key, $value);
            }
        }

        if(isset($options["body"])) {
            $stream = Utils::streamFor(json_encode($options["body"]));
            $request = $request->withBody($stream);
        }

        if (!empty($this->jwtToken)) {
            $request = $request->withAddedHeader('Authorization' ,'Bearer ' . $this->jwtToken);
        }

        return $request;
    }

    /**
     * @param string $method
     * @param string $url
     * @return Request
     */
    protected function setMethodAndUrl(string $method, string $url): Request
    {
        $request = new Request($method, $url);
        $uri = new Uri($url);
        $request->withUri($uri);

        return $request;
    }

    /**
     * @param string $login
     * @param string $password
     * @return void
     */
    public function setBasicAuthData(string $login, string $password): void
    {
        $this->basicAuthLoginData['login'] = $login;
        $this->basicAuthLoginData['password'] = $password;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setJwtToken(string $token): void
    {
        $this->jwtToken = $token;
    }

    /**
     * @return void
     */
    public function clearBasicAuthData(): void
    {
        $this->basicAuthLoginData = [];
    }

    /**
     * @return void
     */
    public function clearJwtToken(): void
    {
        $this->jwtToken = "";
    }
}