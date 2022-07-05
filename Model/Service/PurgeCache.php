<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service;

use Hordieiev\AkamaiClient\Api\Service\Client\RequestInterface;
use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Api\Service\PurgeCacheInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Request;
use Throwable;

/**
 * Class PurgeCache
 */
class PurgeCache implements PurgeCacheInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param RequestInterface $request
     * @param ConfigInterface $config
     */
    public function __construct(
        RequestInterface $request,
        ConfigInterface $config
    ) {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * @param array $tagList
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute(array $tagList): void
    {
        try {
            $this->request->doRequest(
                sprintf(self::INVALIDATE_BY_CACHE_TAG_PATH, $this->config->getNetworkModeEnv()),
                $this->processBodyParameterList($tagList),
                Request::HTTP_METHOD_POST
            );
        } catch (Throwable $throwable) {
            throw new LocalizedException(__($throwable->getMessage()));
        }
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function processBodyParameterList(array $params): array
    {
        return [
            'json' => [
                'objects' => $params ? array_values(array_unique($params)) : [],
            ]
        ];
    }
}
