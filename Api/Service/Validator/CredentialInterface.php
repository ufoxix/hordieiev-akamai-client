<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Validator;

use Magento\Framework\Exception\ValidatorException;

/**
 * Interface CredentialInterface
 *
 * @api
 */
interface CredentialInterface
{
    public const BASE_ERROR_MESSAGE =
        'You need to configure your Akamai credentials in Stores > Configuration > Central > Akamai Configuration.';
    public const DISABLED_ERROR_MESSAGE = 'functionality disabled in Magento MBO.';
    public const CONFIGURED_ERROR = 'has not been configured.';

    /**
     * @return void
     * @throws ValidatorException
     */
    public function validate(): void;
}
