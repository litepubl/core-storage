<?php
/**
 * Lite Publisher CMS
 *
 * @copyright 2010 - 2016 Vladimir Yushko http://litepublisher.com/ http://litepublisher.ru/
 * @license   https://github.com/litepubl/cms/blob/master/LICENSE.txt MIT
 * @link      https://github.com/litepubl\cms
 * @version   7.07
  */

namespace litepubl\core\storage\serializer;

class JSon implements SerializerInterface
{
    private $attr = 0;

    public function __construct(int $attr = 0, bool $pretty = false)
    {
        if ($attr)) {
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
        return \json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | (Config::$debug ? JSON_PRETTY_PRINT : 0));
    }

    public function unserialize(string $str)
    {
        return \json_decode($s, true);
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
