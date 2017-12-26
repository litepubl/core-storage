<?php

namespace LitePubl\Storage\Interfaces;

interface StorableInterface
{
    public function getBaseName(): string;
    public function getData(): array;
    public function setData(array $data);
}
