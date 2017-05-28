<?php

namespace litepubl\core\storage;

trait SaveableItemsTrait
{
    protected $baseName = 'items';

    public function __construct(StorageInterface $storage)
    {
        parent::__construct();
        $this->storage = $storage;
        $this->load();
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function load(): bool
    {
        return $this->getStorage()->load($this);
    }

    public function save(): bool
    {
           return $this->getStorage()->save($this);
    }
}
