<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Logger;

use Magento\Framework\Exception\LocalizedException;

/**
 * Interface EmulationHandlerInterface
 *
 * @api
 */
interface EmulationHandlerInterface
{
    /**
     * @return void
     * @throws LocalizedException
     */
    public function startEmulation(): void;

    /**
     * @return void
     * @throws LocalizedException
     */
    public function stopEmulation(): void;
}
