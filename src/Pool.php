<?php
namespace litepubl\core\storage;

use litepubl\core\Storage\Locker;

class Pool implements StorageInterface, Storable
{
    protected $data;
    protected $instances;
    protected $storage;
    private $locker;
    private $modified;

    public function __construct(StorageInterface $storage, LockerInterface $locker)
    {
        $this->storage = $storage;
        $this->locker = $locker;
        $this->instances = [];
        $this->data = [];
        $storage->load($this);
    }

    public function getBaseName(): string
    {
        return 'storage';
    }

    public function getData(): array
    {
        foreach ($this->instances as $name => $storable) {
               $this->data[$name] = $storable->getData();
        }

        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data + $this->data;
    }

    public function getFilename(Storable $storable): string
    {
        return $storable->getBaseName();
    }

    public function has(Storable $storable): bool
    {
        $base = $storable->getBaseName();
        return isset($this->data[$base]) || isset($this->instances[$base]);
    }

    public function save(Storable $storable): bool
    {
        $this->modified = true;
        $base = $storable->getBaseName();
        $this->instances[$base] = $storable;
        return true;
    }

    public function load(Storable $storable): bool
    {
        $base = $storable->getbasename();
        if (isset($this->data[$base])) {
            $storable->setData($this->data[$base]);
        } elseif (isset($this->instances[$base]) && ($storable != $this->instances[$base])) {
            $storable->setData($this->instances[$base]->getData());
        } else {
                return false;
        }

            return true;
    }

    public function remove(Storable $storable): bool
    {
        $base = $storable->getbasename();
        if (isset($this->data[$base])) {
            unset($this->data[$base]);
            $result = true;
        }

        if (isset($this->instances[$base])) {
            unset($this->instances[$base]);
            $result = true;
        }

        if ($result) {
            $this->modified = true;
        }

            return $result;
    }

    public function commit(): bool
    {
        if (!$this->modified) {
            return false;
        }

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

    public function isInstalled(): bool
    {
        return count($this->data);
    }
}
