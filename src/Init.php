<?php

require __DIR__."/Providers/packages/autoload.php";

use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Aws\S3\S3Client;
use League\Flysystem\Filesystem;

$client = new S3Client([
    'credentials' => [
        'key'    => 'your-key',
        'secret' => 'your-secret'
    ],
    'region' => 'your-region',
    'version' => 'latest|version',
]);

$adapter = new AwsS3Adapter($client, 'your-bucket-name');
$filesystem = new Filesystem($adapter);
