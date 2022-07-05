<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Validator;

use Hordieiev\AkamaiClient\Api\Service\Validator\CredentialInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\ValidatorException;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class Credential
 */
class Credential implements CredentialInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param LoggerInterface $logger
     * @param ConfigInterface $config
     */
    public function __construct(
        LoggerInterface $logger,
        ConfigInterface $config
    ) {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        try {
            $this->initValidation();
        } catch (Throwable $throwable) {
            $this->logger->critical(
                __($throwable->getMessage()),
                [__CLASS__ => $throwable]
            );

            $this->errors = [];
            throw new ValidatorException(__($throwable->getMessage()));
        }
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    private function initValidation(): void
    {
        $this->checkIsFeatureFlagEnable();
        $this->checkHostName();
        $this->checkClientToken();
        $this->checkClientSecret();
        $this->checkAccessToken();

        if ($this->errors) {
            throw new LocalizedException(__(
                sprintf(
                    '%s ' . PHP_EOL . 'Akamai request failed: ' . PHP_EOL . ' %s',
                    implode(PHP_EOL, $this->errors),
                    self::BASE_ERROR_MESSAGE
                )
            ));
        }
    }

    /**
     * @return void
     */
    private function checkIsFeatureFlagEnable(): void
    {
        if (!$this->config->isFeatureFlagEnable()) {
            $this->errors[] = sprintf('Feature flag %s', self::DISABLED_ERROR_MESSAGE);
        }
    }

    /**
     * @return void
     */
    private function checkHostName(): void
    {
        if (!$this->config->getHostName()) {
            $this->errors[] = sprintf('Akamai host %s', self::CONFIGURED_ERROR);
        }
    }

    /**
     * @return void
     */
    private function checkClientToken(): void
    {
        if (!$this->config->getClientToken()) {
            $this->errors[] = sprintf('Akamai client_token %s', self::CONFIGURED_ERROR);
        }
    }

    /**
     * @return void
     */
    private function checkClientSecret(): void
    {
        if (!$this->config->getClientSecret()) {
            $this->errors[] = sprintf('Akamai client_secret %s', self::CONFIGURED_ERROR);
        }
    }

    /**
     * @return void
     */
    private function checkAccessToken(): void
    {
        if (!$this->config->getAccessToken()) {
            $this->errors[] = sprintf('Akamai access_token %s', self::CONFIGURED_ERROR);
        }
    }
}
