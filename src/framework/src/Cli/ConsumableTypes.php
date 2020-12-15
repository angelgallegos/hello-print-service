<?php

namespace Framework\Cli;

use __\__;

class ConsumableTypes
{
    private array $types = [];

    /**
     * ConsumableTypes constructor.
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param array $data
     * @return ConsumableTypes
     */
    public static function create(
        array $data
    ): self {
        $types = __::get($data, 'consumer.types');
        $mappedTypes = [];
        foreach ($types as $key => $type) {
            $mappedTypes[$key] = __::get($type,'service');
        }

        return new self($mappedTypes);
    }
}