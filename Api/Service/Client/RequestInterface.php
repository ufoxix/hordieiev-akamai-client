<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Client;

use GuzzleHttp\Psr7\Response;
use Magento\Framework\Webapi\Rest\Request;

/**
 * Interface RequestInterface
 *
 * @api
 */
interface RequestInterface
{
    public const MIN_VALID_STATUS_CODE = 100;
    public const MAX_VALID_STATUS_CODE = 599;
    public const INTERNAL_SERVER_ERROR_CODE = 500;

    /**
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     *
     * @return Response
     */
    public function doRequest(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = Request::HTTP_METHOD_GET
    ): Response;
}
