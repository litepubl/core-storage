<?php

namespace LitePubl\Storage\Serializer;

use LitePubl\Storage\Interfaces\SerializerInterface;

class JSon implements SerializerInterface
{
    private $attr = 0;

    public function __construct(int $attr = 0, bool $pretty = false)
    {
        if ($attr) {
            $this->attr = $attr;
        } else {
            $this->attr = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        }

        if ($pretty) {
            $this->attr = $this->attr | JSON_PRETTY_PRINT;
        }
    }

    public function serialize(array $data): string
    {
        return \json_encode($data, $this->attr);
    }

    public function unserialize(string $str)
    {
        return \json_decode($str, true);
    }

    public function getExt(): string
    {
        return '.json';
    }

    public function load(string $fileName)
    {
        return $this->unserialize(\file_get_contents($fileName));
    }

    public function save(string $fileName, $data): bool
    {
        return (bool) \file_put_contents($fileName, $this->serialize($data));
    }
}
