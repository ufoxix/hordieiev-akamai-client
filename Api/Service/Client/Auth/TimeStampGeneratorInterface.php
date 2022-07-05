<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Client\Auth;

/**
 * Interface TimeStampGeneratorInterface
 *
 * @api
 */
interface TimeStampGeneratorInterface
{
    public const FORMAT = 'Ymd\TH:i:sO';

    /**
     * @param string $pattern
     *
     * @return string
     */
    public function generateTimeStampByPattern(string $pattern = self::FORMAT): string;
}
