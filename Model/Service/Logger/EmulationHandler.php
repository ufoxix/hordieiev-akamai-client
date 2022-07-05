<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Logger;

use Hordieiev\AkamaiClient\Api\Service\Logger\EmulationHandlerInterface;
use Hordieiev\AkamaiClient\Api\Service\Logger\LogHandlerInterface;
use Hordieiev\AkamaiClient\Api\Service\Resolver\GetStoreInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeCodeResolver;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\App\Emulation;
use Throwable;

/**
 * Class EmulationHandler
 */
class EmulationHandler implements EmulationHandlerInterface
{
    private const START = 'START EMULATION';
    private const STOP = 'STOP EMULATION';

    /**
     * @var GetStoreInterface
     */
    private $getStore;

    /**
     * @var Emulation
     */
    private $emulation;

    /**
     * @var ScopeCodeResolver
     */
    private $scopeCodeResolver;

    /**
     * @var LogHandlerInterface
     */
    private $logHandler;

    /**
     * @var bool
     */
    private $emulationRun = false;

    /**
     * @param GetStoreInterface $getStore
     * @param Emulation $emulation
     * @param ScopeCodeResolver $scopeCodeResolver
     * @param LogHandlerInterface $logHandler
     */
    public function __construct(
        GetStoreInterface $getStore,
        Emulation $emulation,
        ScopeCodeResolver $scopeCodeResolver,
        LogHandlerInterface $logHandler
    ) {
        $this->getStore = $getStore;
        $this->emulation = $emulation;
        $this->scopeCodeResolver = $scopeCodeResolver;
        $this->logHandler = $logHandler;
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function startEmulation(): void
    {
        if ($this->emulationRun === false) {
            try {
                $this->logHandler->startLog(self::START);
                $this->emulation->startEnvironmentEmulation(
                    $this->getStore->getStoreId(),
                    Area::AREA_FRONTEND,
                    true
                );
                $this->scopeCodeResolver->clean();
                $this->emulationRun = true;
                $this->logHandler->stopLog(self::START);
            } catch (Throwable $throwable) {
                throw new LocalizedException(__($throwable->getMessage()));
            }
        }
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function stopEmulation(): void
    {
        try {
            $this->logHandler->startLog(self::STOP);
            $this->emulation->stopEnvironmentEmulation();
            $this->emulationRun = false;
            $this->logHandler->stopLog(self::STOP);
        } catch (Throwable $throwable) {
            throw new LocalizedException(__($throwable->getMessage()));
        }
    }
}
