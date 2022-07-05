<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Interface GetStoreInterface
 *
 * @api
 */
interface GetStoreInterface
{
    /**
     * @param int|null $storeId
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore(?int $storeId = null): StoreInterface;

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int;
}
