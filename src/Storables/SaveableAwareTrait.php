<?php

namespace LitePubl\Core\Storage\Storables;

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
