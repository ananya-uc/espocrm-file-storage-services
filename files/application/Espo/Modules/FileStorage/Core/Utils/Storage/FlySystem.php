<?php

namespace Espo\Modules\FileStorage\Core\Utils\Storage;

use Espo\Core\Utils\Config;
use Espo\Entities\Attachment;
use Espo\Core\Exceptions\Error;
use League\Flysystem\Filesystem;

class FlySystem  extends Base
{
    protected $dependencyList = ['config'];

    private $fileSystem = null;

    const STORAGE_CLIENT_MAPS = [
        "S3" => "Espo\Modules\FileStorage\Core\Utils\Storage\Providers\Adapters\AwsS3"
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
    }

    public function isFile(Attachment $attachment)
    {
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
        $sourceId = $attachment->getSourceId();
        return 'data/upload/' . $sourceId;
    }

    protected function getFilePath(Attachment $attachment)
    {
    }

    public function getDownloadUrl(Attachment $attachment)
    {
        throw new Error();
    }

    public function hasDownloadUrl(Attachment $attachment)
    {
        return false;
    }
}
