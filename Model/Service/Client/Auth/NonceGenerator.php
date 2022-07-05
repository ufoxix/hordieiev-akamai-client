<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Client\Auth;

use Hordieiev\AkamaiClient\Api\Service\Client\Auth\NonceGeneratorInterface;
use Magento\Framework\Oauth\NonceGeneratorInterface as CoreNonceGenerator;

/**
 * Class NonceGenerator
 */
class NonceGenerator implements NonceGeneratorInterface
{
    /**
     * @var CoreNonceGenerator
     */
    private $coreNonceGenerator;

    /**
     * @param CoreNonceGenerator $coreNonceGenerator
     */
    public function __construct(
        CoreNonceGenerator $coreNonceGenerator
    ) {
        $this->coreNonceGenerator = $coreNonceGenerator;
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        return $this->coreNonceGenerator->generateNonce();
    }
}
