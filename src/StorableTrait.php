<?php

namespace litepubl\core\storage;

trait StorableTrait
{
    protected $data = [];
    protected $storage;
    protected $lockCount = 0;

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

    public function load(): bool
    {
        return $this->getStorage()->load($this);
    }

    public function save(): bool
    {
        if ($this->lockCount == 0) {
            return $this->getStorage()->save($this);
        }

        return false;
    }

    public function lock(): bool
    {
        $this->lockCount++;
        return true;
    }

    public function unlock()
    {
        if (--$this->lockCount <= 0) {
            $this->save();
        }
    }

    public function getLocked(): bool
    {
        return $this->lockCount > 0;
    }
}
