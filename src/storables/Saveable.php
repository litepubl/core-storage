<?php

namespace litepubl\core\storage\storables;

use litepubl\core\storage\StorageInterface;

class Saveable implements SaveableInterface
{
    use SaveableTrait;

    protected $storable;
    protected $storage;

    public function __construct(StorableInterface $storable, StorageInterface $storage)
    {
        $this->storable = $storable;
        $this->storage = $storage;
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function getStorable(): StorableInterface
    {
        return $this->storable;
    }
}
