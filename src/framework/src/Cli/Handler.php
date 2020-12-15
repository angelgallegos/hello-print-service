<?php

namespace Framework\Cli;

use Exception;
use Framework\Utils\Configuration\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Handler
{
    /**
     * @var ContainerBuilder|null
     */
    private ?ContainerBuilder $container;

    /**
     * @var ConsumableTypes|null
     */
    private ?ConsumableTypes $availableTypes;

    /**
     * Handler constructor.
     * @param ContainerBuilder|null $container
     * @param ConsumableTypes $consumableTypes
     */
    public function __construct(
        ?ContainerBuilder $container,
        ConsumableTypes $consumableTypes
    ) {
        $this->container = $container;
        $this->availableTypes = $consumableTypes;
    }

    /**
     * @param ConfigurationInterface $input
     * @throws Exception
     */
    public function run(ConfigurationInterface $input)
    {
        if (!$input->has("--type")) {
            $this->container->get("logger")->alert("The argument --type should be present");
            return;
        }

        $service = $this->container->get($this->availableTypes->getTypes()[$input->get("--type")]);
        $service->consume();
    }
}