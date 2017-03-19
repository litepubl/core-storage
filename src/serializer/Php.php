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

class Php implements SerializerInterface
{
    private $opcacheEnabled;

    public function __construct()
    {
        $this->opcacheEnabled = \ini_get('opcache.enable') && !\ini_get('opcache.restrict_api');
    }

    public function serialize(array $data): string
    {
        return \var_export($data, true);
    }

    public function unserialize(string $str)
    {
        return eval('return ' . $str . ';');
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
        if (\file_put_contents($fileName, \sprintf('<?php return %s;', $str))) {
            if ($this->opcacheEnabled) {
                \opcache_compile_file($filename);
            }

                return true;
        }

        return false;
    }
}
