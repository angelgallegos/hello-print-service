<?php

namespace App\Mutators\Request;

use Framework\Utils\Configuration\ConfigurationInterface;

abstract class RequestMutatorAbstract implements RequestMutatorInterface
{
    protected ConfigurationInterface $configs;

    /**
     * RequestMutatorAbstract constructor.
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configs = $configuration;
    }
}