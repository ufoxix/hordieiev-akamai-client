<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Resolver;

use Hordieiev\AkamaiClient\Model\Service\Resolver\GetStore as TargetClass;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class GetStoreTest
 */
class GetStoreTest extends TestCase
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
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->storeManager = Mockery::mock(StoreManagerInterface::class);
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Resolver\GetStore::getStore
     * @covers \Hordieiev\AkamaiClient\Model\Service\Resolver\GetStore::__construct
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetStore(): void
    {
        $store = $this->getStoreMock();
        $this->storeManager->shouldReceive('getStore')->andReturn($store);
        $actual = $this->subject->getStore();
        $this->assertEquals($store, $actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Resolver\GetStore::getStoreId
     * @covers \Hordieiev\AkamaiClient\Model\Service\Resolver\GetStore::__construct
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetStoreId(): void
    {
        $store = $this->getStoreMock();
        $this->storeManager->shouldReceive('getStore')->andReturn($store);
        $store->shouldReceive('getId')->andReturn(1);
        $this->logger->shouldReceive('critical');
        $actual = $this->subject->getStoreId();
        $this->assertEquals((int)$store->getId(), $actual);
    }

    /**
     * @return void
     */
    public function testGetStoreIdThrowException(): void
    {
        $store = $this->getStoreMock();
        $this->storeManager->shouldReceive('getStore')
            ->with(99999999)
            ->andReturn($store);
        $store->shouldReceive('getId')->andReturn(1);
        $this->logger->shouldReceive('critical');
        $this->expectException(\Magento\Framework\Exception\NoSuchEntityException::class);
        $this->subject->getStoreId();
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'storeManager' => $this->storeManager,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return StoreInterface|MockInterface
     */
    private function getStoreMock(): StoreInterface
    {
        return Mockery::mock(StoreInterface::class);
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
