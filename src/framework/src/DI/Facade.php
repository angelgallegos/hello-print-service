<?php

namespace Framework\DI;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Facade
{
    /**
     * self|null
     */
    private static ?Facade $instance = null;

    /**
     * ContainerInterface
     */
    private static ContainerInterface $myContainer;

    /**
     * @param ContainerInterface $container
     */
    private function __construct(ContainerInterface $container)
    {
        self::$myContainer = $container;
    }

    /**
     * @param string $serviceId
     *
     * @return object
     * @throws \Exception
     */
    public static function create($serviceId)
    {
        if (null === self::$instance) {
            throw new \Exception("Facade is not instantiated");
        }

        return self::$myContainer->get($serviceId);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return null|Facade
     */
    public static function init(ContainerInterface $container)
    {
        if (null === self::$instance) {
            self::$instance = new self($container);
        }

        return self::$instance;
    }
}