<?php

namespace LitePubl\Core\Storage\Storables;

interface StorableInterface
{
    public function getBaseName(): string;
    public function getData(): array;
    public function setData(array $data);
}
