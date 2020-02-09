<?php

namespace Espo\Modules\FileStorage\Core\Utils\Storage;

use Espo\Entities\Attachment;
use Espo\Core\Exceptions\Error;
use League\Flysystem\Filesystem;
use Espo\Core\FileStorage\Storages\Base;

class FlySystem extends Base
{
    protected $dependencyList = ['config'];

    private $fileSystem = null;

    const STORAGE_CLIENT_MAPS = [
        'AwsConsole' => "Espo\Modules\FileStorage\Core\Utils\Storage\Providers\Adapters\AwsS3",
    ];

    protected function getConfig()
    {
        return $this->getInjection('config');
    }

    protected function checkService($serviceName): void
    {
        if (\array_key_exists($serviceName, self::ALLOWED_STORAGE_SERVICES) === false) {
            throw new Error("Unsupported: {$serviceName} Storage Service");
        }
    }

    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    protected function init()
    {
        parent::init();
        $serviceName = $this->getConfig()->get('defaultStorageService');
        if (!$serviceName) {
            throw new Error('defaultStorageService not provided in config.php');
        }

        $this->checkService($serviceName);

        $className = self::STORAGE_CLIENT_MAPS[$serviceName];
        $adapterManager = $className::getAdapter($this->getConfig());

        $this->fileSystem = new FileSystem($adapter);
    }

    public function unlink(Attachment $attachment)
    {
        $response = $this->filesystem->delete($path);
    }

    public function isFile(Attachment $attachment)
    {
        return false;
    }

    public function getContents(Attachment $attachment)
    {
        $this->fileSystem->read($this->getFilePath($attachment), $contents);
    }

    public function putContents(Attachment $attachment, $contents)
    {
        $this->fileSystem->write($this->getFilePath($attachment), $contents);
    }

    public function getLocalFilePath(Attachment $attachment)
    {
        $this->getFilePath($attachment);
    }

    protected function getFilePath(Attachment $attachment)
    {
        $sourceId = $attachment->getSourceId();

        return 'data/upload/'.$sourceId;
    }

    public function getDownloadUrl(Attachment $attachment)
    {
        return "https://s3.amazonaws.com/{$this->fileSystem->getAdapter()->getBucket()}/{$this->getFilePath($attachment)}";
    }

    public function hasDownloadUrl(Attachment $attachment)
    {
        return true;
    }
}
