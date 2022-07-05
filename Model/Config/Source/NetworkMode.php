<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class NetworkMode
 */
class NetworkMode implements OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'staging', 'label' => __('Staging')],
            ['value' => 'production', 'label' => __('Production')],
        ];
    }
}
