<?php

namespace LitePubl\Storage\Serializer;

use LitePubl\Storage\Interfaces\SerializerInterface;

class Serialize implements SerializerInterface
{

    public function serialize(array $data): string
    {
        return \serialize($data);
    }

    public function unserialize(string $str)
    {
        if ($str) {
            return \unserialize($str);
        }

        return null;
    }

    public function getExt(): string
    {
        return '.php';
    }

    public function load(string $fileName)
    {
        $str = \file_get_contents($fileName);
        return $this->unserialize(\str_replace('**//*/', '*/', \substr($str, 9, \strlen($str) - 9 - 6)));
    }

    public function save(string $fileName, $data): bool
    {
        $str = \sprintf('<?php /* %s */ ?>', \str_replace('*/', '**//*/', $this->serialize($data)));
        return (bool) \file_put_contents($fileName, $str);
    }
}
