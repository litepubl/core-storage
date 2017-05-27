<?php

namespace litepubl\core\storage\storables;

trait StorableTrait
{
    protected $baseName = 'data';
    protected $data = [];

    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
