<?php
namespace litepubl\core\storage;

use litepubl\core\storage\storables\StorableInterface;
use litepubl\core\Storage\Locker;

class Pool implements PoolInterface, StorableInterface
{
    protected $baseName = 'storage';
    protected $data;
    protected $items;
    protected $storage;
    protected $modified;
    private $locker;

    public function __construct(StorageInterface $storage, LockerInterface $locker)
    {
        $this->storage = $storage;
        $this->locker = $locker;
        $this->items = [];
        $this->data = [];
        $this->modified = false;
        $storage->load($this);
    }

    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function getData(): array
    {
        foreach ($this->items as $name => $storable) {
               $this->data[$name] = $storable->getData();
        }

        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data + $this->data;
    }

    public function getFilename(StorableInterface $storable): string
    {
        return $storable->getBaseName();
    }

    public function has(StorableInterface $storable): bool
    {
        $base = $storable->getBaseName();
        return isset($this->data[$base]) || isset($this->items[$base]);
    }

    public function save(StorableInterface $storable): bool
    {
        $this->items[$storable->getBaseName()] = $storable;
        return true;
    }

    public function load(StorableInterface $storable): bool
    {
        $base = $storable->getBaseName();
        if (isset($this->data[$base])) {
            $storable->setData($this->data[$base]);
        } elseif (isset($this->items[$base]) && ($storable != $this->items[$base])) {
            $storable->setData($this->items[$base]->getData());
        } else {
                return false;
        }

            return true;
    }

    public function remove(StorableInterface $storable): bool
    {
        $base = $storable->getBaseName();
        if (isset($this->data[$base])) {
            unset($this->data[$base]);
            $result = true;
        }

        if (isset($this->items[$base])) {
            unset($this->items[$base]);
            $result = true;
        }


        if ($result) {
                $this->modified = true;
        }

            return $result;
    }

    public function commit(): bool
    {
        if (count($this->items) || $this->modified) {
            if ($this->locker->lock()) {
                try {
                      $this->storage->save($this);

                     $this->items = [];
                    $this->modified = false;
                } finally {
                    $this->locker->unlock();
                }
                return true;
            } else {
                $this->error('Storage locked, data not saved');
            }
        }

            return false;
    }

    public function getInstalled(): bool
    {
        return count($this->data) > 0;
    }
}
