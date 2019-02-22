<?php

declare(strict_types=1);

namespace Ilex\CustomResponse;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface CustomResponseFactoryInterface
{

    public const DEFAULT_STATUS_OK = 200;

    public const DEFAULT_STATUS_EMPTY = 204;

    public const DEFAULT_STATUS_REDIRECT = 302;

    /**
     * Default flags for json_encode; value of:
     *
     * <code>
     * JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT |
     * JSON_UNESCAPED_SLASHES
     * </code>
     *
     * @const int
     */
    public const DEFAULT_JSON_FLAGS = 79;

    public const DEFAULT_TEXT_CONTENT_TYPE = 'text/plain; charset=utf-8';

    public const DEFAULT_HTML_CONTENT_TYPE = 'text/html; charset=utf-8';

    public function __construct(ResponseFactoryInterface $responseFactory);

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
        int $status = CustomResponseFactoryInterface::DEFAULT_STATUS_OK,
        int $encodingOptions = CustomResponseFactoryInterface::DEFAULT_JSON_FLAGS
    ): ResponseInterface;

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
    ): ResponseInterface;

    public function createRedirectResponseFromString(
        string $uri,
        int $status = CustomResponseFactoryInterface::DEFAULT_STATUS_REDIRECT
    ): ResponseInterface;

    public function createRedirectResponseFromUri(
        UriInterface $uri,
        int $status = CustomResponseFactoryInterface::DEFAULT_STATUS_REDIRECT
    ): ResponseInterface;
}
