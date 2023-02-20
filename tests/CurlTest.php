<?php

namespace Radek011200\CurlClientPhp\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Radek011200\CurlClientPhp\Curl;
use Radek011200\CurlClientPhp\Request\Header;
use Radek011200\CurlClientPhp\Request\Options;

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

    /**
     * @var Options
     */
    private Options $options;

    const URL = "https://433o2.wiremockapi.cloud/json";

    protected function setUp(): void
    {
        $this->curl = new Curl();
        $this->options = new Options();
    }

    /**
     * @throws Exception
     * @return void
     */
    public function testWhenSendRequestPostExceptStatusCodeIsCreatedAndResponseIsJson(): void
    {
        $this->options
            ->addHeader(new Header('Accept', 'application/json'))
            ->withBody(["id" => 11, "value" => "Some value"]);

        $response = $this->curl->Post(self::URL, $this->options);

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
        $response = $this->curl->Get(self::URL . '/' . self::$id, $this->options);
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
        $this->options
            ->addHeader(New Header('Accept', 'application/json'))
            ->withBody(["id" => 11, "value" => "Some value"]);
        $response = $this->curl->Put(self::URL . '/' . self::$id, $this->options);

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