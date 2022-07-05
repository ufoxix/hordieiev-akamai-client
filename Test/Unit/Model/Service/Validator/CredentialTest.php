<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Validator;

use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Model\Service\Validator\Credential as TargetClass;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class CredentialTest
 */
class CredentialTest extends TestCase
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
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::validate
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::initValidation
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::checkIsFeatureFlagEnable
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::checkHostName
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::checkClientToken
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::checkClientSecret
     * @covers \Hordieiev\AkamaiClient\Model\Service\Validator\Credential::checkAccessToken
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function testValidate(): void
    {
        $this->init();
        $actual = $this->subject->validate();
        $this->assertNull($actual);
    }

    /**
     * @return void
     */
    public function testValidateThrowException(): void
    {
        $this->logger->shouldReceive('critical');
        $this->config->shouldReceive('isFeatureFlagEnable')
            ->andReturn(false);
        $this->config->shouldReceive('getHostName')
            ->andReturn('');
        $this->config->shouldReceive('getClientToken')
            ->andReturn('');
        $this->config->shouldReceive('getClientSecret')
            ->andReturn('');
        $this->config->shouldReceive('getAccessToken')
            ->andReturn('');
        $this->expectException(\Magento\Framework\Exception\ValidatorException::class);
        $this->subject->validate();
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $isFeatureFlagEnable = true;
        $hostname = 'akab-4ep3vriiyyxxxxoj-lwtpid5umie2c4r4.luna.akamaiapis.net';
        $clientToken = 'xxxx-jscujxxxxramk3v3-xxxxif7zsam6krve';
        $clientSecret = 'xX2xXXsWeYBpXXG5GEillN/XXXxxxx+8mVnypE4RlTM=';
        $accessToken = 'xxxx-i3dyrm735wdd2yny-fb5qt2xxxx6jo23s';
        $this->logger->shouldReceive('critical');
        $this->config->shouldReceive('isFeatureFlagEnable')
            ->andReturn($isFeatureFlagEnable);
        $this->config->shouldReceive('getHostName')
            ->andReturn($hostname);
        $this->config->shouldReceive('getClientToken')
            ->andReturn($clientToken);
        $this->config->shouldReceive('getClientSecret')
            ->andReturn($clientSecret);
        $this->config->shouldReceive('getAccessToken')
            ->andReturn($accessToken);
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
