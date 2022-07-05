<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Logger;

use Hordieiev\AkamaiClient\Api\Service\Logger\LogHandlerInterface;
use Hordieiev\AkamaiClient\Api\Service\Resolver\GetStoreInterface;
use Hordieiev\AkamaiClient\Model\Service\Logger\EmulationHandler as TargetClass;
use Magento\Framework\App\Config\ScopeCodeResolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\App\Emulation;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class EmulationHandlerTest
 */
class EmulationHandlerTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var TargetClass|MockInterface
     */
    private $subject;

    /**
     * @var GetStoreInterface|MockInterface
     */
    private $getStore;

    /**
     * @var Emulation|MockInterface
     */
    private $emulation;

    /**
     * @var ScopeCodeResolver|MockInterface
     */
    private $scopeCodeResolver;

    /**
     * @var LogHandlerInterface|MockInterface
     */
    private $logHandler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->getStore = Mockery::mock(GetStoreInterface::class);
        $this->emulation = Mockery::mock(Emulation::class);
        $this->scopeCodeResolver = Mockery::mock(ScopeCodeResolver::class);
        $this->logHandler = Mockery::mock(LogHandlerInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\EmulationHandler::startEmulation
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\EmulationHandler::__construct
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testStartEmulation(): void
    {
        $this->logHandler->shouldReceive('startLog');
        $this->emulation->shouldReceive('startEnvironmentEmulation');
        $this->getStore->shouldReceive('getStoreId')
            ->andReturn(1);
        $this->scopeCodeResolver->shouldReceive('clean');
        $this->logHandler->shouldReceive('stopLog');
        $this->subject->startEmulation();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\EmulationHandler::stopEmulation
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\EmulationHandler::__construct
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testStopEmulation(): void
    {
        $this->logHandler->shouldReceive('startLog');
        $this->emulation->shouldReceive('stopEnvironmentEmulation');
        $this->logHandler->shouldReceive('stopLog');
        $this->subject->stopEmulation();
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'getStore' => $this->getStore,
                'emulation' => $this->emulation,
                'scopeCodeResolver' => $this->scopeCodeResolver,
                'logHandler' => $this->logHandler,
            ]
        );
    }

    /**
     * Tears down the fixture
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
