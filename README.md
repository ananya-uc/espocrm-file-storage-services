# EspoCRM File Storage Services

Storage Services For EspoCRM - AWS S3

Work in progress. Don't use

## Development

### AWS Mocking

Use `localstack`. Don't use `sudo`.

```console
$ pip3 install -U pip pipenv aws-cli
$ pipenv install localstack requests
$ export AWS_ACCESS_KEY_ID=foobar
$ export AWS_SECRET_ACCESS_KEY=foobar
$ pipenv run localstack start
```

Load http://localhost:4572/ to see an XML result of S3

Open another terminal

```console
# create new bucket
$ aws --endpoint-url=http://localhost:4572 s3 mb s3://mybucket

# list all the files
$ aws --endpoint-url=http://localhost:4572 s3 ls s3://mybucket

# copy file into s3
$ aws --endpoint-url=http://localhost:4572 s3 cp newfile s3://mybucket/newfile

# copy file from s3
$ aws --endpoint-url=http://localhost:4572 s3 cp s3://mybucket/newfile newfile
```

Load http://localhost:4572/mybucket to see the files in the bucket.
