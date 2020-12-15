<?php
namespace App;

use Exception;
use Framework\Cli\ConsumableTypes;
use Framework\Cli\Handler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Framework\Application as BaseApplication;
use Symfony\Component\Yaml\Yaml;
use function dirname;

class Application extends BaseApplication
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function register(ContainerBuilder $container): ContainerBuilder
    {
        $container = parent::register($container);

        if (is_file(dirname(__DIR__).'/config/services.yaml')) {
            $fileLocator = new FileLocator(__DIR__ . '/../config');
            $loader = new YamlFileLoader($container, $fileLocator);
            $loader->load('services.yaml');
        }

        $container->set("command_handler", new Handler($container, $this->loadConsumableTypes()));

        return $container;
    }

    /**
     * @inheritDoc
     */
    public function loadConsumableTypes(): ConsumableTypes
    {
        return ConsumableTypes::create(
            Yaml::parseFile(__DIR__ . '/../config/consumable_types.yaml')
        );
    }
}