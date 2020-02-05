<?php

namespace Espo\Modules\FileStorage\Core\Utils\Storage\Providers\Adapters;

use Espo\Core\Utils\Config;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

class AwsS3
{
    const DEFAULT_BUCKET = 'Crm';

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
    public static function getClient($config)
    {
        return new S3Client($config->get('storageConfig.s3.config'));
    }

    /**
     * $adapter = new AwsS3Adapter($client, 'your-bucket-name');
     */
    public static function getAdapter($config)
    {
        $bucketName = $config->get('storageConfig.s3.bucketName', self::DEFAULT_BUCKET);
        return new AwsS3Adapter(self::getClient(), $bucketName);
    }
}