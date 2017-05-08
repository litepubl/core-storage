<?php

namespace litepubl\core\storage;

trait ItemsStorableTrait
{
    protected $storage;
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

    public function getBaseName(): string
    {
        return $this->basename;
    }

    public function getData(): array
    {
        return $this->items;
    }

    public function setData(array $data): void
    {
        $this->items = $data;
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
