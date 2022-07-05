<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Hordieiev\AkamaiClient\Api\Service\Client\Auth\AuthenticationInterface;
use Hordieiev\AkamaiClient\Api\Service\Client\RequestInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Api\Service\Logger\LogHandlerInterface;
use Hordieiev\AkamaiClient\Api\Service\Validator\CredentialInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Webapi\Rest\Request as WebApiRequest;
use Throwable;

/**
 * Class Request
 */
class Request implements RequestInterface
{
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
     * @param ClientFactory $clientFactory
     * @param ResponseFactory $responseFactory
     * @param CredentialInterface $credentialValidator
     * @param ConfigInterface $config
     * @param LogHandlerInterface $logHandler
     * @param SerializerInterface $serializer
     * @param AuthenticationInterface $authentication
     */
    public function __construct(
        ClientFactory $clientFactory,
        ResponseFactory $responseFactory,
        CredentialInterface $credentialValidator,
        ConfigInterface $config,
        LogHandlerInterface $logHandler,
        SerializerInterface $serializer,
        AuthenticationInterface $authentication
    ) {
        $this->clientFactory = $clientFactory;
        $this->responseFactory = $responseFactory;
        $this->credentialValidator = $credentialValidator;
        $this->config = $config;
        $this->logHandler = $logHandler;
        $this->serializer = $serializer;
        $this->authentication = $authentication;
    }

    /**
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     *
     * @return Response
     */
    public function doRequest(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = WebApiRequest::HTTP_METHOD_GET
    ): Response {
        $this->logHandler->startLog(__CLASS__);
        try {
            $this->credentialValidator->validate();
            /** @var Client $client */
            $client = $this->clientFactory->create(['config' => [
                'base_uri' => sprintf('https://%s', $this->config->getHostName())
            ]]);

            $this->setHeaders($params, $uriEndpoint, $requestMethod);

            $response = $client->request(
                $requestMethod,
                $uriEndpoint,
                $params
            );
        } catch (Throwable $exception) {
            $logMessage = sprintf(
                'Method: %2$s%1$sEndpoint: %3$s%1$s Params: %4$s%1$s',
                PHP_EOL,
                $requestMethod,
                $uriEndpoint,
                $this->serializer->serialize($params)
            );
            $this->logHandler->log(
                sprintf(
                    'Request failed.%1$s %2$s %1$s Response: %3$s',
                    PHP_EOL,
                    $logMessage,
                    $exception->getMessage()
                )
            );
            $responseStatus = $this->isValidStatusCode($exception->getCode()) ? $exception->getCode() :
                self::INTERNAL_SERVER_ERROR_CODE;

            /** @var Response $response */
            $response = $this->responseFactory->create([
                'status' => $responseStatus,
                'reason' => $exception->getMessage()
            ]);
        }
        $this->logHandler->log($response->getBody()->getContents());
        $this->logHandler->stopLog(__CLASS__);

        return $response;
    }

    /**
     * @param array $params
     * @param string $uriEndpoint
     * @param string $requestMethod
     *
     * @return void
     */
    private function setHeaders(
        array &$params,
        string $uriEndpoint,
        string $requestMethod
    ): void {
        $headers = [
            'Authorization' => $this->authentication->execute($uriEndpoint, $params, $requestMethod),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $params['headers'] = array_merge($params['headers'] ?? [], $headers);
    }

    /**
     * @param int $code
     *
     * @return bool
     */
    private function isValidStatusCode(int $code): bool
    {
        return $code >= self::MIN_VALID_STATUS_CODE && $code <= self::MAX_VALID_STATUS_CODE;
    }
}
