<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Client\Auth;

use Hordieiev\AkamaiClient\Api\Service\Client\Auth\AuthenticationInterface;
use Hordieiev\AkamaiClient\Api\Service\Client\Auth\NonceGeneratorInterface;
use Hordieiev\AkamaiClient\Api\Service\Client\Auth\TimeStampGeneratorInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Webapi\Rest\Request;

/**
 * Class Authentication
 */
class Authentication implements AuthenticationInterface
{
    /**
     * @string
     */
    private const JSON_STRING = 'json';

    /**
     * @var int
     */
    private const MAX_BODY_SIZE = 131072;

    /**
     * @var TimeStampGeneratorInterface
     */
    private $timeStampGenerator;

    /**
     * @var NonceGeneratorInterface
     */
    private $nonceGenerator;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var null|string
     */
    private $uriEndpoint;

    /**
     * @var null|array
     */
    private $params;

    /**
     * @var null|string
     */
    private $requestMethod;

    /**
     * @param TimeStampGeneratorInterface $timeStampGenerator
     * @param NonceGeneratorInterface $nonceGenerator
     * @param ConfigInterface $config
     * @param SerializerInterface $serializer
     */
    public function __construct(
        TimeStampGeneratorInterface $timeStampGenerator,
        NonceGeneratorInterface $nonceGenerator,
        ConfigInterface $config,
        SerializerInterface $serializer
    ) {
        $this->timeStampGenerator = $timeStampGenerator;
        $this->nonceGenerator = $nonceGenerator;
        $this->config = $config;
        $this->serializer = $serializer;
    }

    /**
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     *
     * @return string
     */
    public function execute(
        string $uriEndpoint,
        array $params,
        string $requestMethod
    ): string {
        $this->initParameters($uriEndpoint, $params, $requestMethod);
        return $this->getAuthorizationHeaderValue();
    }

    /**
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     *
     * @return void
     */
    private function initParameters(string $uriEndpoint, array $params, string $requestMethod): void
    {
        $this->uriEndpoint = $uriEndpoint;
        $this->params = $params;
        $this->requestMethod = $requestMethod;

        if (isset($this->params[self::JSON_STRING])) {
            $this->params[self::JSON_STRING] = $this->serializer->serialize($this->params[self::JSON_STRING]);
        }
    }

    /**
     * @return string
     */
    private function getAuthorizationHeaderValue(): string
    {
        $timestamp = $this->timeStampGenerator->generateTimeStampByPattern();
        $nonce = $this->nonceGenerator->generate();

        $preparedAuthHeader = sprintf(
            'EG1-HMAC-SHA256 client_token=%s;access_token=%s;timestamp=%s;nonce=%s;',
            $this->config->getClientToken(),
            $this->config->getAccessToken(),
            $timestamp,
            $nonce
        );

        return sprintf(
            '%ssignature=%s',
            $preparedAuthHeader,
            $this->signRequest($preparedAuthHeader, $timestamp)
        );
    }

    /**
     * Returns a signature of the given request, timestamp and auth_header
     *
     * @param string $preparedAuthHeader
     * @param string $timestamp
     *
     * @return string
     */
    private function signRequest(
        string $preparedAuthHeader,
        string $timestamp
    ): string {
        return $this->makeBase64HmacSha256(
            $this->makeDataToSign($preparedAuthHeader),
            $this->makeSigningKey($timestamp, $this->config->getClientSecret())
        );
    }

    /**
     * Returns a string with all data that will be signed
     *
     * @param string $preparedAuthHeader
     *
     * @return string
     */
    private function makeDataToSign(string $preparedAuthHeader): string
    {
        $data = [
            strtoupper($this->requestMethod),
            'https',
            $this->config->getHostName(),
            $this->uriEndpoint,
            '',
            (strtoupper($this->requestMethod) === Request::HTTP_METHOD_POST) ? $this->makeContentHash() : '',
            $preparedAuthHeader
        ];

        return implode("\t", $data);
    }

    /**
     * @param string $timestamp
     * @param string $clientSecret
     *
     * @return string
     */
    private function makeSigningKey(string $timestamp, string $clientSecret): string
    {
        return $this->makeBase64HmacSha256($timestamp, $clientSecret);
    }

    /**
     * Returns Base64 encoded HMAC-SHA256 Hash
     *
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    private function makeBase64HmacSha256(string $data, string $key): string
    {
        return base64_encode(hash_hmac('sha256', $data, $key, true));
    }

    /**
     * Returns a hash of the HTTP POST body
     *
     * @return string
     */
    private function makeContentHash(): string
    {
        if (empty($this->params[self::JSON_STRING])) {
            return '';
        }

        return $this->makeBase64Sha256($this->getBody(true));
    }

    /**
     * Returns Base64 encoded SHA256 Hash
     *
     * @param string $data
     *
     * @return string
     */
    private function makeBase64Sha256(string $data): string
    {
        return base64_encode(hash('sha256', $data, true));
    }

    /**
     * Get request body
     *
     * @param bool $truncate
     *
     * @return string
     */
    private function getBody(bool $truncate = false): string
    {
        if (!$truncate) {
            return $this->params[self::JSON_STRING] ?? '';
        }

        return isset($this->params[self::JSON_STRING])
            ? substr($this->params[self::JSON_STRING], 0, self::MAX_BODY_SIZE)
            : '';
    }
}
