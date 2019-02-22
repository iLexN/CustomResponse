<?php

declare(strict_types=1);

namespace Ilex\CustomResponse;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class CustomResponseFactory implements CustomResponseFactoryInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Create a JSON response with the given data.
     *
     * Default JSON encoding is performed with the following options, which
     * produces RFC4627-compliant JSON, capable of embedding into HTML.
     *
     * - JSON_HEX_TAG
     * - JSON_HEX_APOS
     * - JSON_HEX_AMP
     * - JSON_HEX_QUOT
     * - JSON_UNESCAPED_SLASHES
     *
     * @param array $data Data to convert to JSON.
     * @param int $status Integer status code for the response; 200 by default.
     * @param int $encodingOptions JSON encoding options to use.
     *
     * @return ResponseInterface
     */
    public function createJsonResponseFromArray(
        array $data,
        int $status = 200,
        int $encodingOptions = CustomResponseFactoryInterface::DEFAULT_JSON_FLAGS
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse($status)
            ->withHeader('content-type', 'application/json');
        $json = \json_encode($data, $encodingOptions);
        if (false === $json) {
            throw new \RuntimeException('json_encode have error');
        }
        $response->getBody()->write($json);
        return $response;
    }

    /**
     * 201 Created responses are often empty, and only include a Link or
     *      Location header pointing to the newly created resource.
     * 202 Accepted responses are typically empty, indicating that the new
     *      entity has been received, but not yet processed.
     * 204 No Content responses are, by definition, empty, and often used as a
     *      success response when deleting an entity.
     *
     * @param int $status
     *
     * @return ResponseInterface
     */
    public function createEmptyResponse(
        int $status = CustomResponseFactoryInterface::DEFAULT_STATUS_EMPTY
    ): ResponseInterface {
        return $this->responseFactory->createResponse($status);
    }

    public function createRedirectResponseFromString(
        string $uri,
        int $status = CustomResponseFactoryInterface::DEFAULT_STATUS_REDIRECT
    ): ResponseInterface {
        return $this->responseFactory->createResponse($status)
            ->withHeader('location', $uri);
    }

    public function createRedirectResponseFromUri(
        UriInterface $uri,
        int $status = CustomResponseFactoryInterface::DEFAULT_STATUS_REDIRECT
    ): ResponseInterface {
        return $this->createRedirectResponseFromString((string)$uri, $status);
    }
}
