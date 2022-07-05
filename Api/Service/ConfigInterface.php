<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service;

/**
 * Interface ConfigInterface
 *
 * @api
 */
interface ConfigInterface
{
    /**#@+
     * Constants defined for config data access
     *
     * @var string
     */
    public const CONFIG_IS_FEATURE_FLAG_ENABLE = 'config_akamai/credentials/feature_flag_setting';
    public const CONFIG_HOST_NAME = 'config_akamai/credentials/host_name';
    public const CONFIG_CLIENT_TOKEN = 'config_akamai/credentials/client_token';
    public const CONFIG_CLIENT_SECRET = 'config_akamai/credentials/client_secret';
    public const CONFIG_ACCESS_TOKEN = 'config_akamai/credentials/access_token';
    public const CONFIG_IS_DEBUG_ENABLE = 'config_akamai/credentials/debug';
    public const CONFIG_AKAMAI_NETWORK_ENV = 'config_akamai/credentials/network_mode';
    /**#@-*/

    /**
     * @return bool
     */
    public function isFeatureFlagEnable(): bool;

    /**
     * @return string
     */
    public function getHostName(): string;

    /**
     * @return string
     */
    public function getClientToken(): string;

    /**
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * @return bool
     */
    public function isDebugEnable(): bool;

    /**
     * @return string
     */
    public function getNetworkModeEnv(): string;
}
