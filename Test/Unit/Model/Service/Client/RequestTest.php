<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Hordieiev\AkamaiClient\Api\Service\Client\Auth\AuthenticationInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Api\Service\Logger\LogHandlerInterface;
use Hordieiev\AkamaiClient\Api\Service\Validator\CredentialInterface;
use Hordieiev\AkamaiClient\Model\Service\Client\Request as TargetClass;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 */
class RequestTest extends TestCase
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
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var CredentialInterface
     */
    private $credentialValidator;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var LogHandlerInterface
     */
    private $logHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->clientFactory = Mockery::mock(ClientFactory::class);
        $this->responseFactory = Mockery::mock(ResponseFactory::class);
        $this->credentialValidator = Mockery::mock(CredentialInterface::class);
        $this->config = Mockery::mock(ConfigInterface::class);
        $this->logHandler = Mockery::mock(LogHandlerInterface::class);
        $this->serializer = Mockery::mock(SerializerInterface::class);
        $this->authentication = Mockery::mock(AuthenticationInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Request::doRequest
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Request::__construct
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Request::setHeaders
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Request::isValidStatusCode
     * @throws \Exception
     */
    public function testDoRequest(): void
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestamp = $date->format('Ymd\TH:i:sO');
        $nonce = '293078ae-d012-4dbd-99d9-9c08af6988d8';
        $client = $this->getClientMock();
        $response = $this->getResponseMock();
        $hostname = 'akab-4ep3vriiyyxxxxoj-lwtpid5umie2c4r4.luna.akamaiapis.net';
        $clientToken = 'xxxx-jscujxxxxramk3v3-xxxxif7zsam6krve';
        $accessToken = 'xxxx-i3dyrm735wdd2yny-fb5qt2xxxx6jo23s';
        $uriEndpoint = '/api-definitions/v2/endpoints';
        $auth = sprintf(
            'EG1-HMAC-SHA256 client_token=%s;access_token=%s;timestamp=%s;nonce=%s;',
            $clientToken,
            $accessToken,
            $timestamp,
            $nonce
        );
        $params = [
            'headers' => [
                'Authorization' => $auth,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ];
        $this->logHandler->shouldReceive('startLog')
            ->with(TargetClass::class);
        $this->credentialValidator->shouldReceive('validate');
        $this->clientFactory->shouldReceive('create')
            ->with(['config' => [
                'base_uri' => sprintf('https://%s', $hostname)
            ]])
            ->andReturn($client);
        $this->serializer->shouldReceive('serialize');
        $this->logHandler->shouldReceive('log');
        $this->config->shouldReceive('getHostName')
            ->andReturn($hostname);
        $this->authentication->shouldReceive('execute')
            ->with($uriEndpoint, $params, 'GET')
            ->andReturn($auth . 'signature=rxxxxxXxxXXXYZ1/xLQ5YxxxXXXXXJ+oFVU38lvPf4=');
        $this->responseFactory->shouldReceive('create');
        $response->shouldReceive('getBody');
        $response->shouldReceive('getContents');
        $this->logHandler->shouldReceive('stopLog')
            ->with(TargetClass::class);
    }

    /**
     * @return Client|MockInterface
     */
    private function getClientMock(): Client
    {
        return Mockery::mock(Client::class);
    }

    /**
     * @return Response|MockInterface
     */
    private function getResponseMock(): Response
    {
        return Mockery::mock(Response::class);
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'clientFactory' => $this->clientFactory,
                'responseFactory' => $this->responseFactory,
                'credentialValidator' => $this->credentialValidator,
                'config' => $this->config,
                'logHandler' => $this->logHandler,
                'serializer' => $this->serializer,
                'authentication' => $this->authentication,
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
