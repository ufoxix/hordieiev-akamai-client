<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Client\Auth;

/**
 * Interface AuthenticationInterface
 *
 * @api
 */
interface AuthenticationInterface
{
    /**
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     *
     * @return string
     */
    public function execute(string $uriEndpoint, array $params, string $requestMethod): string;
}
