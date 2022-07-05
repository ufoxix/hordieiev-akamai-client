<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service;

use Magento\Framework\Exception\LocalizedException;

/**
 * Interface PurgeCacheInterface
 *
 * @api
 */
interface PurgeCacheInterface
{
    public const INVALIDATE_BY_CACHE_TAG_PATH = '/ccu/v3/invalidate/tag/%s';

    /**
     * @param array $tagList
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute(array $tagList): void;
}
