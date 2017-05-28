<?php

namespace litepubl\core\storage\storables;

class Composite implements StorableInterface
{
    use StorableItemsTrait;

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
        return $this->getSttorableData($this->items);
    }

    public function setData(array $data)
    {
        return $this->setStorableData($this->items, $data);
    }
}
