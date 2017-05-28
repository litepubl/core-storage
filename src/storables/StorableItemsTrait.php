<?php

namespace litepubl\core\storage\storables;

trait StorableItemsTrait
{

    protected function getStorableData($items): array
    {
        $result = [];
        foreach ($items as $instance) {
                $result[$instance->getBaseName()] = $instance->getData();
        }

        return $result;
    }

    protected function setStorableData($items, array $data)
    {
        foreach ($items as $instance) {
                $baseName = $instance->getBaseName();
            if (isset($data[$baseName])) {
                $instance->setData($data[$baseName]);
            }
        }
    }
}
