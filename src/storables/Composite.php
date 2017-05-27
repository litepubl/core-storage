<?php

namespace litepubl\core\storage\storables;

class Composite implements StorableInterface
{
    protected $items;
    protected $baseName = 'composite';

    public function __construct(StorableInterface ... $items)
    {
        $this->items = $items;
    }

    public function add(StorableInterface $storable)
    {
        $this->items[] = $storable;
    }

    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function getData(): array
    {
        $result = [];
        foreach ($this->items as $instance) {
                $result[$instance->getBaseName()] = $instance->getData();
        }

        return $result;
    }

    public function setData(array $data)
    {
        foreach ($this->items as $instance) {
                $baseName = $instance->getBaseName();
            if (isset($data[$baseName])) {
                $instance->setData($data[$baseName]);
            }
        }
    }
}
