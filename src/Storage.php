<?php
namespace litepubl\core\storage;

use litepubl\core\storage\serializer\SerializerInterface;
use litepubl\core\logmanager\LogManagerInterface;

class Storage implements StorageInterface
{
    protected $serializer;
    protected $logManager = null;
    protected $path = '';
    protected $perm = 0666;

    public function __construct(SerializerInterface $serializer, LogManagerInterface $logManager, string $path, int $perm = 0666)
    {
        $this->serializer = $serializer;
        $this->logManager = $logManager;
        $this->path = rtrim($path, '\/') . '/';
        $this->perm = $perm;
    }

    public function load(Storable $storable): bool
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
            $this->logException(
                $e, [
                'filename' => $fileName,
                'serializer' => get_class($this->serializer),
                ]
            );
        }

        return false;
    }

    public function save(Storable $storable): bool
    {
        return $this->saveFile($this->path . $storable->getBaseName(), $this->serializer->getExt(), $storable->getData());
    }

    public function getFilename(Storable $storable): string
    {
        return $this->path . $storable->getBaseName() .  $this->serializer->getExt();
    }

    public function has(Storable $storable): bool
    {
        return \file_exists($this->getFileName($storable));
    }

    protected function saveFile(string $filename, string $ext, array $data): bool
    {
        $tmp = $filename . '.tmp' . $ext;
        if (!$this->serializer->save($tmp, $data)) {
            $this->error(\sprintf('Error write to file "%s"', $tmp));
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
                    $this->error(sprintf('Error rename temp file "%s" to "%s"', $tmp, $curfile));
            }
        }

        return false;
    }

    public function remove(Storable $storable ): bool
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

    protected function logException(\Throwable $e, array $context = [])
    {
            $this->logManager->logException($e, $context);
    }

    protected function error(string $mesg)
    {
        $this->logManager->trace($mesg);
    }
}
