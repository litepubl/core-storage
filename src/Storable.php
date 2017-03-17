<?php

namespace litepubl\core\storage;

interface Storable
{
public function getStorage(): StorageInterface;
public function getBaseName(): string;
public function getData(): array;
public function setData(array $data);
}
