<?php

namespace LitePubl\Storage\Serializer;

use LitePubl\Storage\Interfaces\SerializerInterface;

class Php implements SerializerInterface
{
    private $opcacheEnabled;

    public function __construct()
    {
        $this->opcacheEnabled = \ini_get('opcache.enable') && !\ini_get('opcache.restrict_api');
    }

    public function serialize(array $data): string
    {
        return sprintf('return %s;', \var_export($data, true));
    }

    public function unserialize(string $str)
    {
        return eval($str);
    }

    public function getExt(): string
    {
        return '.inc.php';
    }

    public function load(string $fileName)
    {
        return include $fileName;
    }

    public function save(string $fileName, $data): bool
    {
        $str = $this->serialize($data);
        if (\file_put_contents($fileName, '<?php ' . $str)) {
            if ($this->opcacheEnabled) {
                \opcache_compile_file($fileName);
            }

                return true;
        }

        return false;
    }
}
