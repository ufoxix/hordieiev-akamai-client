<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Resolver;

use Hordieiev\AkamaiClient\Api\Service\Resolver\GetStoreInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class GetStore
 */
class GetStore implements GetStoreInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @param int|null $storeId
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore(?int $storeId = null): StoreInterface
    {
        try {
            return $this->storeManager->getStore($storeId);
        } catch (Throwable $throwable) {
            $this->logger->critical(
                __($throwable->getMessage()),
                [__CLASS__ => $throwable]
            );

            throw new NoSuchEntityException(__($throwable->getMessage()));
        }
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return (int)$this->getStore()->getId();
    }
}
