<?php

declare(strict_types=1);

namespace Ilex\CustomResponse;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

class CustomResponseFactoryTest extends TestCase
{
    /**
     * @dataProvider provider
     *
     * @param string $label
     * @param CustomResponseFactory $factory
     */
    public function testCreateRedirectResponseFromUri(
        string $label,
        CustomResponseFactory $factory
    ): void {
        $url = 'http://www.example.com';
        $uri = (new Psr17Factory())->createUri($url);
        $response = $factory->createRedirectResponseFromUri($uri);

        self::assertEquals(true, $response->hasHeader('Location'));

        self::assertEquals(
            $url,
            $response->getHeaderLine('location'),
            $label . ': Test location fail'
        );
        self::assertEquals(
            302,
            $response->getStatusCode(),
            $label . ': Test status code fail'
        );

        $response = $factory->createRedirectResponseFromString($url, 301);
        self::assertEquals(
            $url,
            $response->getHeaderLine('location'),
            $label . ': Test location fail'
        );
        self::assertEquals(301, $response->getStatusCode());
    }

    /**
     * @dataProvider provider
     *
     * @param string $label
     * @param CustomResponseFactory $factory
     */
    public function testCreateRedirectResponseFromString(
        string $label,
        CustomResponseFactory $factory
    ): void {
        $url = 'http://www.example.com';
        $response = $factory->createRedirectResponseFromString($url);
        self::assertEquals(true, $response->hasHeader('Location'));
        self::assertEquals(
            $url,
            $response->getHeaderLine('location'),
            $label . ': Test location fail'
        );
        self::assertEquals(
            302,
            $response->getStatusCode(),
            $label . ': Test status code fail'
        );

        $response = $factory->createRedirectResponseFromString($url, 301);
        self::assertEquals(301, $response->getStatusCode());
    }

    /**
     * @dataProvider provider
     *
     * @param string $label
     * @param CustomResponseFactory $factory
     */
    public function testCreateEmptyResponse(
        string $label,
        CustomResponseFactory $factory
    ): void {
        $response = $factory->createEmptyResponse();
        self::assertEquals('', (string)$response->getBody());
        self::assertEquals(
            204,
            $response->getStatusCode(),
            $label . ': Test status code fail'
        );
        $code = 201;
        $response = $factory->createEmptyResponse($code);
        self::assertEquals(
            $code,
            $response->getStatusCode(),
            $label . ': Test status code fail'
        );
    }

    /**
     * @dataProvider provider
     *
     * @param string $label
     * @param CustomResponseFactory $factory
     */
    public function testCreateJsonResponseFromArray(
        string $label,
        CustomResponseFactory $factory
    ): void {
        $data = [
            'nested' => [
                'json' => [
                    'tree',
                ],
            ],
        ];
        $json = '{"nested":{"json":["tree"]}}';

        $response = $factory->createJsonResponseFromArray($data);
        self::assertEquals(
            200,
            $response->getStatusCode(),
            $label . ': Test status code fail'
        );
        self::assertEquals(
            'application/json',
            $response->getHeaderLine('content-type'),
            $label . ': Test content-type fail'
        );
        self::assertEquals(
            $json,
            (string)$response->getBody(),
            $label . ': Test json content fail'
        );
        $response = $factory->createJsonResponseFromArray($data, 500);
        self::assertEquals(
            500,
            $response->getStatusCode(),
            $label . ': Test status code fail'
        );
        $pretty = <<<'JSON'
{
    "nested": {
        "json": [
            "tree"
        ]
    }
}
JSON;
        $response = $factory->createJsonResponseFromArray(
            $data,
            200,
            JSON_PRETTY_PRINT
        );
        self::assertEquals(
            $pretty,
            (string)$response->getBody(),
            $label . ': Test json content fail'
        );
    }

    public function provider(): array
    {
        return [
            [
                'Test Nyholm Factory',
                new CustomResponseFactory(new Psr17Factory()),
            ],
            [
                'Test zend-diactoros',
                new CustomResponseFactory(new \Zend\Diactoros\ResponseFactory()),
            ],
        ];
    }
}
