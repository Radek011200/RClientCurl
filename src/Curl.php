<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp;

use Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;

class Curl
{
    /**
     * @var array
     */
    private array $basicAuthLoginData = [];

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, (string)$request->getUri());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request->getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        if ($this->basicAuthLoginData)
        {
            curl_setopt($curl, CURLOPT_USERPWD, $this->basicAuthLoginData['login'] . ':' . $this->basicAuthLoginData['password']);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        $response = curl_exec($curl);


        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 15, $header_size-15);

        $body = substr($response, $header_size);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);


        $header_rows = explode("\n", $headers);

        $headers = array();
        foreach($header_rows as $header) {
            $header = trim($header);
            if(!strpos($header, ':') || empty($header))
                continue;
            list($key, $value) = explode(':', $header);
            $value = trim($value);
            $headers[$key] = $value;
        }

        $response = new Response($status, $headers, $body);
        $this->checkResponse($response);

        return $response;
    }

    /**
     * @param Request $request
     * @param array $options
     * @return RequestInterface
     */
    private function prepareRequest(Request $request, array $options = []): RequestInterface
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

        return $request;
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function sendGet(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl("GET", $url);
        $request = $this->prepareRequest($request, $options);
        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function sendPost(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl("POST", $url);
        $request = $this->prepareRequest($request, $options);
        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function sendDelete(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl("DELETE", $url);
        $request = $this->prepareRequest($request, $options);
        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    public function sendPut(string $url, array $options = []): ResponseInterface
    {
        $request = $this->setMethodAndUrl("PUT", $url);
        $request = $this->prepareRequest($request, $options);
        return $this->sendRequest($request);
    }

    /**
     * @param string $method
     * @param string $url
     * @return Request
     */
    private function setMethodAndUrl(string $method, string $url): Request
    {
        $request = new Request($method, $url);
        $uri = new Uri($url);
        $request->withUri($uri);
        return $request;
    }

    /**
     * @param ResponseInterface $response
     * @return void
     * @throws Exception
     */
    private function checkResponse(ResponseInterface $response): void
    {
        $status = $response->getStatusCode();
        $toCheck = $status/100;

        if($toCheck >= 4 && $toCheck < 6)
            throw new Exception($status . " " . $response->getReasonPhrase());
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
}
