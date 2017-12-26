<?php

namespace LitePubl\Storage\Storables;

trait CompositeTrait
{

    protected function getItemsData($items): array
    {
        $result = [];
        foreach ($items as $instance) {
                $result[$instance->getBaseName()] = $instance->getData();
        }

        return $result;
    }

    protected function setItemsData($items, array $data)
    {
        foreach ($items as $instance) {
                $baseName = $instance->getBaseName();
            if (isset($data[$baseName])) {
                $instance->setData($data[$baseName]);
            }
        }
    }
}
