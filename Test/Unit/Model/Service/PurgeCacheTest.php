<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service;

use Hordieiev\AkamaiClient\Api\Service\Client\RequestInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Model\Service\PurgeCache as TargetClass;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PurgeCache
 */
class PurgeCacheTest extends TestCase
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
     * @var RequestInterface|MockInterface
     */
    private $request;

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

        $this->request = Mockery::mock(RequestInterface::class);
        $this->config = Mockery::mock(ConfigInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\PurgeCache::execute
     * @covers \Hordieiev\AkamaiClient\Model\Service\PurgeCache::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\PurgeCache::processBodyParameterList
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testExecute(): void
    {
        $this->config->shouldReceive('getNetworkModeEnv');

        $this->request->shouldReceive('doRequest');

        $this->subject->execute(['black-friday', 'white-friday']);
    }

    /**
     * @return void
     */
    public function testExecuteException(): void
    {
        $this->config->shouldReceive('getNetworkModeEnv');
        $this->request->shouldReceive('doRequest')
            ->with('')
            ->andReturn('');
        $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        $this->subject->execute(['black-friday', 'white-friday']);
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'request' => $this->request,
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
