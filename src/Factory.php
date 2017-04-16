<?php

namespace litepubl\core\storage;

use litepubl\core\storage\serializer\Php;
use litepubl\core\logmanager\FactoryInterface as LogFactory;
use litepubl\core\logmanager\NullManager;

class Factory inplements FactoryInterface
{
    protected $storage;
    protected $pool;

    public function __construct(string $path, LogFactory $logFactory = null)
    {
        $path = ltrim($path, '\/'). '/';
        if (!$logFactory) {
                $logFactory = new LazyLogFactory(
                    function () {
                        return new NullManager();
                    }
                );
        }

        $this->storage = new Storage(new Php(), $logFactory, $path);
        $this->pool = new Pool($this->storage, new FileLocker($path . 'storage.lok'));
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function getPool(): PoolInterface
    {
        return $this->pool;
    }
}
