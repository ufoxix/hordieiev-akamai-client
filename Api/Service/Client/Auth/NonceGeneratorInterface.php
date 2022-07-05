<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Client\Auth;

/**
 * Interface NonceGeneratorInterface
 *
 * @api
 */
interface NonceGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
