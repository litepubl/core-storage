<?php

namespace LitePubl\Storage\Storables;

use LitePubl\Storage\Interfaces\SaveableInterface;

class Composite implements StorableInterface
{
    use CompositeTrait;

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
        return $this->getItemsData($this->items);
    }

    public function setData(array $data)
    {
        $this->setItemsData($this->items, $data);
    }
}
