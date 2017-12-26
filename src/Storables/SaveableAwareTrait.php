<?php

namespace LitePubl\Storage\Storables;

use LitePubl\Storage\Interfaces\SaveableInterface;

trait SaveableAwareTrait
{
    protected $saveable;

    public function getSaveable(): SaveableInterface
    {
        return $this->saveable;
    }

    public function setSaveable(SaveableInterface $saveable)
    {
        $this->saveable = $saveable;
    }
}
