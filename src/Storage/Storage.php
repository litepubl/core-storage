<?php

namespace LitePubl\Storage\Storage;

use LitePubl\LogManager\FactoryInterface as LogFactory;
use LitePubl\Storage\Interfaces\SerializerInterface;
use LitePubl\Storage\Interfaces\StorableInterface;
use LitePubl\Storage\Interfaces\StorageInterface;

class Storage implements StorageInterface
{
    protected $serializer;
    protected $logFactory;
    protected $path = '';
    protected $perm = 0666;

    public function __construct(SerializerInterface $serializer, LogFactory $logFactory, string $path, int $perm = 0666)
    {
        $this->serializer = $serializer;
        $this->logFactory = $logFactory;
        $this->path = rtrim($path, '\/') . '/';
        $this->perm = $perm;
    }

    public function load(StorableInterface $storable): bool
    {
        try {
            $fileName = $this->getFileName($storable);
            if (\file_exists($fileName)) {
                $data = $this->serializer->load($fileName);
                if ($data) {
                    $storable->setData($data);
                    return true;
                }
            }
        } catch (\Throwable $e) {
            $this->logFactory->getLogManager()->logException(
                $e,
                [
                'filename' => $fileName,
                'serializer' => get_class($this->serializer),
                ]
            );
        }

        return false;
    }

    public function save(StorableInterface $storable): bool
    {
        try {
                return $this->saveFile($this->path . $storable->getBaseName(), $this->serializer->getExt(), $storable->getData());
        } catch (\Throwable $e) {
            $this->logFactory->getLogManager()->logException(
                $e,
                [
                'storable' => get_class($storable),
                'serializer' => get_class($this->serializer),
                ]
            );
        }
    }

    public function getFilename(StorableInterface $storable): string
    {
        return $this->path . $storable->getBaseName() .  $this->serializer->getExt();
    }

    public function has(StorableInterface $storable): bool
    {
        return \file_exists($this->getFileName($storable));
    }

    protected function saveFile(string $filename, string $ext, array $data): bool
    {
        $tmp = $filename . '.tmp' . $ext;
        if (!$this->serializer->save($tmp, $data)) {
            $this->logFactory->getLogManager()->error(\sprintf('Error write to file "%s"', $tmp));
        } else {
            if ($this->perm) {
                \chmod($tmp, $this->perm);
            }

                //replace file
                $curfile = $filename . $ext;
            if (\file_exists($curfile)) {
                $backfile = $filename . '.bak' . $ext;
                $this->removeFile($backfile);
                    \rename($curfile, $backfile);
            }

            if (\rename($tmp, $curfile)) {
                return true;
            } else {
                                $this->logFactory->getLogManager()->error(sprintf('Error rename temp file "%s" to "%s"', $tmp, $curfile));
            }
        }

        return false;
    }

    public function remove(StorableInterface $storable): bool
    {
        $fileName = $this->path . $storable->getBaseName();
        $this->removeFile($fileName . '.bak' . $this->serializer->getExt());
        return $this->removeFile($fileName . $this->serializer->getExt());
    }

    protected function removeFile(string $fileName): bool
    {
        if (\file_exists($fileName)) {
            if (!\unlink($fileName)) {
                        \chmod($fileName, $this->perm ? $this->perm : 0666);
                        \unlink($fileName);
            }

            return true;
        }

        return false;
    }
}
