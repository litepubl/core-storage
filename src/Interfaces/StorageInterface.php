<?php

namespace LitePubl\Storage\Interfaces;

interface StorageInterface
{
    public function has(StorableInterface $storable): bool;
    public function load(StorableInterface $storable): bool;
    public function save(StorableInterface $storable): bool;
    public function remove(StorableInterface $storable): bool;
    public function getFileName(StorableInterface $storable): string;
}
