<?php

namespace Espo\Modules\FileStorage\Core\Utils\Storage;

use Espo\Core\Utils\Config;
use Espo\Entities\Attachment;
use Espo\Core\Exceptions\Error;

require_once(__DIR__."Providers/packages/autoload.php");

class FlySystem  extends Base
{
    protected $dependencyList = ['config'];

    private $fileSystem = null;

    const STORAGE_CLIENT_MAPS = [
        "S3" => [
            "Client" => "\\Aws\\S3\\S3Client",
            "Adapter" => "\\League\\Flysystem\\AwsS3v3\\AwsS3Adapter"
        ]
    ];

    const ALLOWED_STORAGE_SERVICES = [
        "S3"
    ];

    protected function getConfig()
    {
        return $this->getInjection('config');
    }

    protected function checkService($serviceName): void
    {
        if (!\in_array($serviceName, self::ALLOWED_STORAGE_SERVICES)) {
            throw new Error("Unsupported: {$serviceName} Storage Service");
        }
    }

    /**
     * $client = new S3Client([
     *       'credentials' => [
     *           'key'    => 'your-key',
     *           'secret' => 'your-secret'
     *       ],
     *       'region' => 'your-region',
     *       'version' => 'latest|version',
     *   ]);
     */
    protected function getClient($serviceName, ...$clientParams)
    {
        $this->checkService($serviceName);

        $className = self::STORAGE_CLIENT_MAPS[$serviceName]['Client'];
        return new $className(...$clientParams);
    }

    /**
     * $adapter = new AwsS3Adapter($client, 'your-bucket-name');
     */
    protected function getFlyAdapter(string $serviceName, array $clientParams, array $adapterParams)
    {
        $this->checkService($serviceName);
        $client = $this->getClient($serviceName, ...$clientParams);

        $className = self::STORAGE_CLIENT_MAPS[$serviceName]['Adapter'];
        return new $className($client, ...$adapterParams);
    }

    public function setFileSystem(string $serviceName, array $clientParams, array $adapterParams)
    {
        $adapter = $this->getFlyAdapter($serviceName, $clientParams, $adapterParams);
        $this->fileSystem = new League\Flysystem\Filesystem($adapter);
    }

    protected function init()
    {
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
