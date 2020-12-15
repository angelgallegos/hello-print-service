<?php

namespace Framework;

use Framework\Cli\ConsumableTypes;
use Framework\Utils\Configuration\ConfigurationInterface;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ApplicationInterface
 *
 * Interface with underlying framework
 */
interface ApplicationInterface
{
    /**
     * Boots the current kernel.
     */
    public function boot();

    /**
     * Register components to the DI Container
     *
     * @param ContainerBuilder $container
     *
     * @return ContainerBuilder
     */
    public function register(ContainerBuilder $container): ContainerBuilder;

    /**
     * Retrieves the instance of the Container
     *
     * @return ContainerBuilder
     */
    public function getContainer(): ContainerBuilder;

    /**
     * get the configuration of the application
     *
     * @return ConfigurationInterface
     */
    public function getConfig(): ConfigurationInterface;

    /**
     * Gets the Logger instance
     *
     * @return Logger
     */
    public function getLogger(): Logger;

    /**
     * Gets the project dir (path of the project's composer file).
     *
     * @return string
     */
    public function getProjectDir(): string;

    /**
     * Get a list of the current application version(s)
     * - app
     * - base
     * - ops
     *
     * @return array
     */
    public function getVersion(): array;

    /**
     * @return ConsumableTypes
     */
    public function loadConsumableTypes(): ConsumableTypes;
}