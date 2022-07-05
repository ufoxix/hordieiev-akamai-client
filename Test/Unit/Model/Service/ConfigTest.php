<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service;

use Hordieiev\AkamaiClient\Api\Service\Resolver\GetStoreInterface;
use Hordieiev\AkamaiClient\Model\Service\Config as TargetClass;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 */
class ConfigTest extends TestCase
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
     * @var ScopeConfigInterface|MockInterface
     */
    private $scopeConfig;

    /**
     * @var GetStoreInterface|MockInterface
     */
    private $getStore;

    /**
     * @var EncryptorInterface|MockInterface
     */
    private $encryptor;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->scopeConfig = Mockery::mock(ScopeConfigInterface::class);
        $this->getStore = Mockery::mock(GetStoreInterface::class);
        $this->encryptor = Mockery::mock(EncryptorInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::isFeatureFlagEnable
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testIsFeatureFlagEnable(): void
    {
        $this->scopeConfig->shouldReceive('isSetFlag')
            ->with(TargetClass::CONFIG_IS_FEATURE_FLAG_ENABLE)
            ->andReturn(true);

        $actual = $this->subject->isFeatureFlagEnable();
        $this->assertTrue($actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::isDebugEnable
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testIsDebugEnable(): void
    {
        $this->scopeConfig->shouldReceive('isSetFlag')
            ->with(TargetClass::CONFIG_IS_DEBUG_ENABLE)
            ->andReturn(true);

        $actual = $this->subject->isDebugEnable();
        $this->assertTrue($actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getHostName
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testGetHostName(): void
    {
        $expected = '';
        $this->scopeConfig->shouldReceive('getValue')
            ->with(TargetClass::CONFIG_HOST_NAME)
            ->andReturn($expected);

        $actual = $this->subject->getHostName();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getClientToken
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testGetClientToken(): void
    {
        $expected = '';
        $this->scopeConfig->shouldReceive('getValue')
            ->with(TargetClass::CONFIG_CLIENT_TOKEN)
            ->andReturn($expected);

        $actual = $this->subject->getClientToken();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getClientSecret
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testGetClientSecret(): void
    {
        $expected = '';
        $this->scopeConfig->shouldReceive('getValue')
            ->with(TargetClass::CONFIG_CLIENT_SECRET)
            ->andReturn($expected);
        $this->encryptor->shouldReceive('decrypt')
            ->with($expected)
            ->andReturn($expected);

        $actual = $this->subject->getClientSecret();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getAccessToken
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testGetAccessToken(): void
    {
        $expected = '';
        $this->scopeConfig->shouldReceive('getValue')
            ->with(TargetClass::CONFIG_ACCESS_TOKEN)
            ->andReturn($expected);

        $actual = $this->subject->getAccessToken();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getNetworkModeEnv
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Config::getConfigValue
     */
    public function testGetNetworkModeEnv(): void
    {
        $expected = '';
        $this->scopeConfig->shouldReceive('getValue')
            ->with(TargetClass::CONFIG_AKAMAI_NETWORK_ENV)
            ->andReturn($expected);

        $actual = $this->subject->getNetworkModeEnv();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'scopeConfig' => $this->scopeConfig,
                'getStore' => $this->getStore,
                'encryptor' => $this->encryptor,
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
