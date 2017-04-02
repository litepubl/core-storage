<?php

namespace litepubl\core\storage;

interface StorageInterface
{
    public function has(Storable $storable): bool;
    public function load(Storable $storable): bool;
    public function save(Storable $storable): bool;
    public function remove(Storable $storable): bool;
    public function getFileName(Storable $storable): string;
}
