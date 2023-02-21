<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Radek011200\CurlClientPhp\Request\HttpMethod;
use Radek011200\CurlClientPhp\Request\Options;
use Radek011200\CurlClientPhp\Response\HandleResponse;

class CurlHandler
{
    /**
     * @throws Exception
     */
    public function handle(RequestInterface $request, Options $options): ResponseInterface
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, (string)$request->getUri());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader($request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);

        if($request->getMethod() === HttpMethod::POST ||
            $request->getMethod() === HttpMethod::PUT ||
            $request->getMethod() === HttpMethod::PATCH)
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody());
        }

        foreach ($options->getCurlOPT() as $option)
        {
            curl_setopt($curl, $option->key, $option->value);
        }

        if($options->isBasicAuthLoginData())
        {
            $basicAuthData = $options->getBasicAuthLogin();
            curl_setopt($curl, CURLOPT_USERPWD, $basicAuthData['login'] . ':' . $basicAuthData['password']);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            throw new Exception(curl_error($curl));
        }

        return (new HandleResponse())->handle($curl,$response);
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    private function prepareHeader(RequestInterface $request): array
    {
        $headers = [];

        foreach ($request->getHeaders() as $header => $headerValue) {
            $headers[] = $header . ': ' . $headerValue[0];
        }

        return $headers;
    }
}