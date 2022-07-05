<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Model\Service\Logger;

use Hordieiev\AkamaiClient\Api\Service\ConfigInterface;
use Hordieiev\AkamaiClient\Api\Service\Logger\LogHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LogHandler
 */
class LogHandler implements LogHandlerInterface
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
     * @var null|bool
     */
    private $debugEnabled;

    /**
     * @var array
     */
    private $timers = [];

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
     * @param string $action
     *
     * @return void
     */
    public function startLog(string $action): void
    {
        if ($this->getDebugStatus()) {
            $this->log(
                sprintf('%s %s >>>>> BEGIN %s', PHP_EOL, PHP_EOL, $action)
            );
            $this->timers[$action] = microtime(true);
        }
    }

    /**
     * @param string $action
     *
     * @return void
     */
    public function stopLog(string $action): void
    {
        if (isset($this->timers[$action]) && $this->getDebugStatus()) {
            $this->log(
                sprintf(
                    '<<<<< END %s (%s)',
                    $action,
                    $this->formatTime($this->timers[$action], microtime(true))
                )
            );
        }
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function log(string $message): void
    {
        if ($this->getDebugStatus()) {
            $this->logger->info($message);
        }
    }

    /**
     * @return bool
     */
    private function getDebugStatus(): bool
    {
        if ($this->debugEnabled === null) {
            $this->debugEnabled = $this->config->isDebugEnable();
        }

        return $this->debugEnabled;
    }

    /**
     * @param float $begin
     * @param float $end
     *
     * @return string
     */
    private function formatTime(float $begin, float $end): string
    {
        return sprintf('%s sec', $end - $begin);
    }
}
