<?php

namespace Library\Traits\Common;

use ReflectionClass;

/**
 * @class LoadTrait
 * @package Library\Traits
 */
trait PropertyLoadTrait
{
    /**
     * @param array|object $data
     * @param array $ignore
     * @return static
     */
    public function load(array|object $data, array $ignore = []): static
    {
        $properties = new ReflectionClass($this);
        $properties = $properties->getProperties();

        if (is_array($data)) {
            foreach ($properties as $property) {
                $propertyName = $property->name;

                $this->$property = in_array($propertyName, $ignore) ? null : ($data[$propertyName] ?? null);
            }
        }

        if (is_object($data)) {
            foreach ($properties as $property) {
                $propertyName = $property->name;

                $this->$propertyName = in_array($propertyName, $ignore) ? null : ($data->$propertyName ?? null);
            }
        }

        return $this;
    }
}