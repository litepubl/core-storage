<?php

namespace litepubl\core\storage\storables;

use litepubl\core\storage\StorageAwareInterface;
use litepubl\core\storage\StorageAwareTrait;
use litepubl\core\storage\StorageInterface;

class Data implements StorableInterface, SaveableInterface, StorageAwareInterface
{
    use StorableTrait;
    use SaveableTrait;
    use StorageAwareTrait;
}
