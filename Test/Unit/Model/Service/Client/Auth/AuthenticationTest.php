<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Client\Auth;

use Hordieiev\AkamaiClient\Api\Service\Client\Auth\NonceGeneratorInterface;
use Hordieiev\AkamaiClient\Api\Service\Client\Auth\TimeStampGeneratorInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication as TargetClass;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticationTest
 */
class AuthenticationTest extends TestCase
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
     * @var TimeStampGeneratorInterface|MockInterface
     */
    private $timeStampGenerator;

    /**
     * @var NonceGeneratorInterface|MockInterface
     */
    private $nonceGenerator;

    /**
     * @var ConfigInterface|MockInterface
     */
    private $config;

    /**
     * @var SerializerInterface|MockInterface
     */
    private $serializer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->timeStampGenerator = Mockery::mock(TimeStampGeneratorInterface::class);
        $this->nonceGenerator = Mockery::mock(NonceGeneratorInterface::class);
        $this->config = Mockery::mock(ConfigInterface::class);
        $this->serializer = Mockery::mock(SerializerInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::execute
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::initParameters
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::getAuthorizationHeaderValue
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::signRequest
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::makeDataToSign
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::makeSigningKey
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::makeBase64HmacSha256
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::makeContentHash
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::makeBase64Sha256
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\Authentication::getBody
     */
    public function testExecute(): void
    {
        $this->timeStampGenerator->shouldReceive('generateTimeStampByPattern')
            ->andReturn('');
        $this->nonceGenerator->shouldReceive('generate')
            ->andReturn('');
        $this->config->shouldReceive('getClientToken')
            ->andReturn('');
        $this->config->shouldReceive('getAccessToken')
            ->andReturn('');
        $this->config->shouldReceive('getHostName')
            ->andReturn('');
        $this->config->shouldReceive('getClientSecret')
            ->andReturn('');
        $this->subject->execute(
            '/api-definitions/v2/endpoints',
            [],
            'GET'
        );
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'timeStampGenerator' => $this->timeStampGenerator,
                'nonceGenerator' => $this->nonceGenerator,
                'config' => $this->config,
                'serializer' => $this->serializer,
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
