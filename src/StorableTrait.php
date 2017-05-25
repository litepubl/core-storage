<?php

namespace litepubl\core\storage;

trait StorableTrait
{
    protected $baseName = 'data';
    protected $data = [];
    protected $lockCount = 0;
    protected $storage;

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function getData(): array
    {
        $result = $this->data;

        if ($this instance of SubStorablesInterface) {
                $result = $this->getSubData($result);
        }

        return $result;
    }

    public function setData(array $data): void
    {
        if ($this instance of SubStorablesInterface) {
                $data = $this->setSubData($data);
        }

        $this->data = $data;
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
