<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service;

use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Api\Service\Resolver\GetStoreInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use Throwable;

/**
 * Class Config
 */
class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var GetStoreInterface
     */
    private $getStore;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param GetStoreInterface $getStore
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        GetStoreInterface $getStore,
        EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->getStore = $getStore;
        $this->encryptor = $encryptor;
    }

    /**
     * @return bool
     */
    public function isFeatureFlagEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_IS_FEATURE_FLAG_ENABLE);
    }

    /**
     * @return string
     */
    public function getHostName(): string
    {
        return $this->getConfigValue(self::CONFIG_HOST_NAME);
    }

    /**
     * @return string
     */
    public function getClientToken(): string
    {
        return $this->getConfigValue(self::CONFIG_CLIENT_TOKEN);
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getConfigValue(self::CONFIG_CLIENT_SECRET);
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->getConfigValue(self::CONFIG_ACCESS_TOKEN);
    }

    /**
     * @return bool
     */
    public function isDebugEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_IS_DEBUG_ENABLE);
    }

    /**
     * @return string
     */
    public function getNetworkModeEnv(): string
    {
        return $this->getConfigValue(self::CONFIG_AKAMAI_NETWORK_ENV);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function getConfigValue(string $path): string
    {
        try {
            $configValue = (string)$this->scopeConfig->getValue(
                $path,
                ScopeInterface::SCOPE_STORE,
                $this->getStore->getStoreId()
            );
        } catch (Throwable $throwable) {
            return '';
        }

        return $path === self::CONFIG_CLIENT_SECRET
            ? $this->encryptor->decrypt($configValue)
            : $configValue;
    }
}
