<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Logger;

use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler as TargetClass;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class LogHandlerTest
 */
class LogHandlerTest extends TestCase
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
     * @var LoggerInterface|MockInterface
     */
    private $logger;

    /**
     * @var ConfigInterface|MockInterface
     */
    private $config;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->config = Mockery::mock(ConfigInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::startLog
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::getDebugStatus
     */
    public function testStartLog(): void
    {
        $this->init();
        $this->subject->startLog('');
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::stopLog
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::getDebugStatus
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::log
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::formatTime
     */
    public function testStopLog(): void
    {
        $this->init();
        $this->subject->stopLog('');
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::log
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::getDebugStatus
     * @covers \Hordieiev\AkamaiClient\Model\Service\Logger\LogHandler::formatTime
     */
    public function testLog(): void
    {
        $this->init();
        $this->subject->log('');
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $this->config->shouldReceive('isDebugEnable')
            ->andReturn(true);
        $this->logger->shouldReceive('info');
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'logger' => $this->logger,
                'config' => $this->config,
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
