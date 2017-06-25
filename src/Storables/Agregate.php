<?php

namespace LitePubl\Core\Storage\Storables;

use LitePubl\Core\Storage\StorageInterface;

class Agregate implements SaveableInterface
{
    use SaveableTrait;

    protected $storable;
    protected $storage;

    public function __construct(StorableInterface $storable, StorageInterface $storage)
    {
        $this->storable = $storable;
        $this->storage = $storage;

        if ($storable instanceof SaveableAwareInterface) {
                $storable->setSaveable($this);
        }

        $this->load();
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
