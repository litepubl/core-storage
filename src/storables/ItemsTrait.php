<?php

namespace litepubl\core\storage\storables;

trait ItemsTrait
{
    protected $baseName = 'items';

    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function getData(): array
    {
        return $this->items;
    }

    public function setData(array $data): void
    {
        $this->items = $data;
    }
}
