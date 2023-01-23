<?php

namespace Radek011200\CurlClientPhp\Tests;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use PHPUnit\Framework\TestCase;
use Radek011200\CurlClientPhp\Curl;

class ExampleTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[NoReturn] public function testExample()
    {

        $test = new Curl();
//        $response = $test->sendGet("https://2j96d.wiremockapi.cloud/json/1", [
//            "headers" => [
//                "Accept" => "application/json",
//            ],
//            "body" => [
//                "test" => "test",
//            ]
//        ]);
       $test->setBasicAuthData("login", "password");
        $response = $test->sendPost("https://2j96d.wiremockapi.cloud/json", [
            "headers" => [
                "Accept" => "application/json",
            ],
            "body" => [
                "id" => uniqid(),
                "value" => 'test POST',
            ]
        ]);
        var_dump($response);die();
    }
}