<?php

namespace litepubl\core\storage;

class StorableTrait implements Storable
{
public $data = [];
protected $storage;

public function getStorage(): StorageInterface
{
return $this->storage;
}

public function getBaseName(): string
{
return 'data';
}

public function getData(): array
{
return $this->data;
}

public function setData(array $data): void
{
$this->data = $data + $this->data;
}
}
