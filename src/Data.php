<?php

namespace litepubl\core\storage;

class Data implements StorableInterface, SaveableInterface, StorageAwareInterface
{
    use StorableTrait;
    use SaveableTrait;
    use StorageAwareTrait;
}
