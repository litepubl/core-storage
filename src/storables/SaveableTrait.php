<?php

namespace litepubl\core\storage\storables;

use litepubl\core\storage\StorageInterface;

trait SaveableTrait
{
    protected $lockCount = 0;

    abstract public function getStorage(): StorageInterface;
    abstract public function getStorable(): StorableInterface;

    public function load(): bool
    {
        return $this->getStorage()->load($this->getStorable());
    }

    public function save(): bool
    {
        if ($this->lockCount == 0) {
            return $this->getStorage()->save($this->getStorable);
        }

        return false;
    }

    public function lock(): bool
    {
        $this->lockCount++;
        return true;
    }

    public function unlock(): bool
    {
        if (--$this->lockCount <= 0) {
            return $this->save();
        }

        return false;
    }

    public function getLocked(): bool
    {
        return $this->lockCount > 0;
    }
}
