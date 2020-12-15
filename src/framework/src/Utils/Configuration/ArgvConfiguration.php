<?php

namespace Framework\Utils\Configuration;

use __\__;

class ArgvConfiguration implements ConfigurationInterface
{

    /**
     * @var array
     */
    private array $config;

    /**
     * ArrayConfig constructor.
     *
     * @param array|null $argv
     */
    public function __construct(array $argv = null)
    {
        $argv = $argv ?? $_SERVER['argv'] ?? [];
        array_shift($argv);
        $configs = [];
        $skipNext = false;
        foreach ($argv as $key => $arg) {
            if ((substr($arg, 0, 2 ) === "--") && !$skipNext) {
                $configs[$arg] = $argv[$key+1];
                $skipNext = true;
            }
        }
        $this->config = $configs;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return __::get($this->config, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function getUsingFlag(string $flag, string $key, $default = null)
    {
        $flag = $this->get($flag);
        if(!$flag || null === $flag){
            if(__::isFunction($default)){
                return $default();
            }

            return $default;
        }
        return $this->get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return __::has($this->config, $key);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->config;
    }
}