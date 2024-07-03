<?php

namespace App\Services\Trello\Entities;

abstract class Entity implements \JsonSerializable
{
    private array $fields = [];

    public function __construct(array $data)
    {
        $this->fields = $data;
    }

    /**
     * Dynamically set a field.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->fields[$name] = $value;
    }

    /**
     * Gets a dynamic field.
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * Return the data that should be serialized for Telegram.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->fields;
    }

    /**
     * Perform to json
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this);
    }

    /**
     * Perform to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Get a property from the current Entity
     *
     * @param string $property
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getProperty(string $property, mixed $default = null): mixed
    {
        return $this->$property ?? $default;
    }


    public function __call($method, $args)
    {
        if (str_starts_with($method, 'get')) {
            return $this->getProperty(lcfirst(substr($method, 3)));
        }

        if (str_starts_with($method, 'is') && count($args) === 1) {
            return $this->getProperty(lcfirst(substr($method, 2))) === $args[0];
        }

        return null;
    }
}
