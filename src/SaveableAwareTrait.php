<?php

namespace litepubl\core\storage;

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
