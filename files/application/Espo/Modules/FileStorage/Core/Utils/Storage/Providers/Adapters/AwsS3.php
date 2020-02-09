<?php

namespace Espo\Modules\FileStorage\Core\Utils\Storage\Providers\Adapters;

use Aws\S3\S3Client;
use Espo\Entities\Integration;
use Espo\Core\Exception\Error;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

class AwsS3
{
    const DEFAULT_BUCKET = 'Crm';

    const AWS_INTEGRATION_ID = 'AwsConsole';

    private $configurations = [];

    private $enabled = false;

    private $bucketName = null;

    public function __construct(Integration $entity)
    {
        $this->configurations = [
            'version' => $entity->get('awsS3Version'),
            'region' => $entity->get('awsRegionName'),
            'signature_version' => $entity->get('awsSignatureVersion'),
            'credentials' => [
                'key' => $entity->get('awsAccessKeyId'),
                'secret' => $entity->get('awsSecretAccessKey'),
            ],
        ];
        $this->bucketName = $entity->get('awsDefaultS3Bucket');
        $this->enabled = $entity->get('enabled');
    }

    public function getClient()
    {
        if (!$this->enabled) {
            throw new Error('Aws Integration is not enabled');
        }

        if (empty($this->configurations)) {
            throw new Error('Aws not configured');
        }

        return new S3Client($this->configurations);
    }

    public function getAdapter()
    {
        return new AwsS3Adapter($this->getClient(), $this->bucketName);
    }

    public function testConnection()
    {
        $status = false;
        try {
            $s3 = $this->getClient();
        } catch (Error $error) {
            return $status;
        }

        try {
            $buckets = $s3->listBuckets();
            $status = true;
        } catch (CredentialsException $credentialsException) {
            $GLOBALS['log']->error("Invalid Credentials, Reason: {$credentialsException->getMessage()}");
        } catch (S3Exception $e) {
            $GLOBALS['log']->error("S3 Exception: $e->getMessage()", $e->getTrace());
        } finally {
            return $status;
        }
    }
}
