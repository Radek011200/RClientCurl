<?php
declare(strict_types=1);

namespace Radek011200\CurlClientPhp\Response;

use CurlHandle;
use GuzzleHttp\Psr7\Response;

class HandleResponse
{
    /**
     * @param CurlHandle $curl
     * @param string $response
     * @return Response
     */
    public function handle(CurlHandle $curl, string $response): Response
    {
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $headers = substr($response, 0, $header_size);
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

        return new Response($status, $headers, $body);
    }
}