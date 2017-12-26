<?php

namespace LitePubl\Storage\Interfaces;

interface SerializerInterface
{
    public function serialize(array $data): string;
    public function unserialize(string $str);
    public function getExt(): string;
    public function load(string $fileName);
    public function save(string $fileName, $data): bool;
}
