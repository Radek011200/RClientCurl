<?php

namespace Radek011200\CurlClientPhp\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Radek011200\CurlClientPhp\Curl;

class CurlTest extends TestCase
{
    /**
     * @var int
     */
    private static int $id;

    /**
     * @var Curl
     */
    private Curl $curl;
    const URL = "https://433o2.wiremockapi.cloud/json";

    protected function setUp(): void
    {
        $this->curl = new Curl();
    }

    /**
     * @throws Exception
     * @return void
     */
    public function testWhenSendRequestPostExceptStatusCodeIsCreatedAndResponseIsJson(): void
    {
        $response = $this->curl->Post(self::URL, [
            "headers" => [
                "Accept" => "application/json",
            ],
            "body" => [
                "id"=> 11,
                "value"=> "Some value"
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->assertSame([
            'message' => 'Success',
            'data' => [
                'id' => 11,
                'value' => 'Some value',
            ],
        ], $responseBody);

        $this->assertJson($response->getBody());
        $this->assertArrayHasKey('Content-Type', $response->getHeaders());
        $this->assertEquals(201, $response->getStatusCode());

        self::$id = $responseBody['data']['id'];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testWhenSendRequestGetExceptStatusCodeIsOkAndResponseIsJson(): void
    {
        $response = $this->curl->Get(self::URL . '/' . self::$id);
        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->assertSame([
            'id' => 11,
            'value' => 'Some value'
        ], $responseBody);

        $this->assertJson($response->getBody());
        $this->assertArrayHasKey('Content-Type', $response->getHeaders());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws Exception
     * @return void
     */
    public function testWhenSendRequestPutExceptStatusCodeIsOkAndResponseIsJson(): void
    {
        $response = $this->curl->Put(self::URL . '/' . self::$id, [
            "headers" => [
                "Accept" => "application/json",
            ],
            "body" => [
                "id"=> 11,
                "value"=> "Some value"
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        $this->assertSame([
            'id' => 11,
            'value' => 'Some value'
        ], $responseBody);

        $this->assertJson($response->getBody());
        $this->assertArrayHasKey('Content-Type', $response->getHeaders());
        $this->assertEquals(200, $response->getStatusCode());
    }
}