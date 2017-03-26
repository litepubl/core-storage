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
            $fileName = $this->getFileName($storable). $this->serializer->getExt();
            if ($data = $this->loadData($fileName)) {
                $storable->setData($data);
                return true;
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
        return $this->saveFile($this->getFileName($storable), $this->serializer->getExt(), $storable->getData());
    }

    public function getFilename(Storable $storable): string
    {
        return $this->path . $storable->getBaseName();
    }

    public function loadData(string $fileName): ? array
    {
        if (\file_exists($fileName)) {
                return $this->serializer->load($filename);
        }

        return null;
    }

    public function saveData(string $fileName, array $data): bool
    {
        $dot = \strrpos($fileName, '.');
        return $this->saveFile(substr($fileName, 0, $dot), substr($fileName, $dot), $data);
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
                $backfile = $filename . '.bak' . $this->getExt();
                $this->delete($backfile);
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
        $fileName = $this->getFileName($storable);
        $this->removeFile($fileName . '.bak' . $this->serializer->getExt());
        return $this->removeFile($fileName . $this->serializer->getExt());
    }

    public function removeFile(string $fileName): bool
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
