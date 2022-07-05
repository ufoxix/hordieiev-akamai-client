<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Api\Service\Logger;

/**
 * Interface LogHandlerInterface
 *
 * @api
 */
interface LogHandlerInterface
{
    /**
     * @param string $action
     *
     * @return void
     */
    public function startLog(string $action): void;

    /**
     * @param string $action
     *
     * @return void
     */
    public function stopLog(string $action): void;

    /**
     * @param string $message
     *
     * @return void
     */
    public function log(string $message): void;
}
