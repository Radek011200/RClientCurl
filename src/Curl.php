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
        $request = new Request("GET", $url);
        $uri = new Uri($url);
        $request->withUri($uri);

        $request = $this->prepareRequest($request, $options);
        return $this->sendRequest($request);
    }

    /**
     * @throws Exception
     */
    private function checkResponse(ResponseInterface $response): void
    {
        $status = $response->getStatusCode();
        $toCheck = $status/100;

        if($toCheck >= 4 && $toCheck < 6)
            throw new Exception($status . " " . $response->getReasonPhrase());
    }
}
