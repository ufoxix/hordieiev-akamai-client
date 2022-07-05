<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Client\Auth;

use Hordieiev\AkamaiClient\Api\Service\Client\Auth\TimeStampGeneratorInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class TimeStampGenerator
 */
class TimeStampGenerator implements TimeStampGeneratorInterface
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    public function generateTimeStampByPattern(string $pattern = self::FORMAT): string
    {
        return $this->timezone->date(null, null, false)->format($pattern);
    }
}
