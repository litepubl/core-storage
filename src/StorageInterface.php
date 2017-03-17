<?php

namespace litepubl\core\storage;

interface StorageInterface
{
    public function load(Storable $storable): bool;
    public function save(Storable $storable): bool;
    public function remove(Storable $storable): bool;
    public function getFileName(Storable $storable): string;
    public function loadData(string $fileName): ? array;
    public function saveData(string $fileName, array $data): bool;
    public function removeFile(string $fileName): bool;
}
