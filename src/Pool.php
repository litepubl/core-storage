<?php
namespace litepubl\core\storage;

use litepubl\core\Storage\Locker;

class Pool implements StorageInterface, Storable
{
    protected $data;
    protected $objects;
    protected $storage;
    private $locker;
    private $modified;

    public function __construct(StorageInterface $storage, LockerInterface $locker)
    {
        $this->objects = [];
        $this->data = [];
        $this->locker = $locker;
        $this->storage = $storage;
        $storage->load($this);
    }

    public function getBaseName(): string
    {
        return 'storage';
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data + $this->data;
    }

    public function save(Storable $storable): bool
    {
        $this->modified = true;
        $base = $storable->getBaseName();
        $this->objects[$base] = $storable;
        return true;
    }

    public function load(Storable $storable): bool
    {
        $base = $storable->getbasename();
        if (isset($this->data[$base])) {
            $storable->setData($this->data[$base]);
            return true;
        } else {
            return false;
        }
    }

    public function loadData(string $fileName): ? array
    {
        if (isset($this->data[$fileName])) {
                return $this->data[$fileName];
        }

        return null;
    }

    public function saveData(string $fileName, array $data): bool
    {
        $this->data[$fileName] = $data;
            $this->modified = true;
            return true;
    }

    public function remove(Storable $storable): bool
    {
        $base = $storable->getbasename();
        if (isset($this->data[$base])) {
            unset($this->data[$base]);

            if (isset($this->objects[$base])) {
                unset($this->objects[$base]);
            }

            $this->modified = true;
            return true;
        }

        return false;
    }

    public function removeFile(string $fileName): bool
    {
        if (isset($this->data[$fileName)) {
                unset($this->data[$fileName]);
            $this->modified = true;
            return true;
        }

        return false;
    }

    public function commit()
    {
        if (!$this->modified) {
            return false;
        }

        foreach ($this->objects as $name => $storable) {
               $this->data[$name] = $storable->getData();
        }

        $lockfile = $this->getApp()->paths->data . 'storage.lok';
        if ($this->locker->lock()) {
            try {
                      $this->storage->save($this);
                      $this->modified = false;
            } finally {
                $this->locker->unlock();
            }
                  return true;
        } else {

            $this->error('Storage locked, data not saved');
            return false;
        }
    }

    public function isInstalled()
    {
        return count($this->data);
    }
}
