# EspoCRM File Storage Services

Storage Services For EspoCRM - AWS S3

Work in progress. Don't use

## Development

### AWS Mocking

#### Solution-1

Use `localstack`. Don't use `sudo`.

Add `$HOME/.local/bin` to `$PATH`.

```console
$ export PATH=$PATH:$HOME/.local/bin
$ sudo apt install python3-pip
$ sudo pip3 install --upgrade pip
$ pip3 install pipenv aws
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

#### Solution-2 [The AWS Official Solution]

Use [amplify](https://aws-amplify.github.io/docs/js/start)

1. Create an AWS account
2. Install [nvm](https://github.com/nvm-sh/nvm)
3. `nvm use 10.16.3`
4. `npm i -g @aws-amplify/cli`
5. `amplify configure`
  - Note: This will create an IAM User in your AWS account. So, it's important that you're logged in to aws account in your default browser.
  - While configuring, after creating user, it will ask for access key Id and access key secret Id

> The access key Id and access key secret is kept in `$HOME/.aws/credentials` file. <br/>
> The region, output format and profile information is kept in `$HOME/.aws/config` file <br/>
> The file format for above config and credentials file is ini

Example,

`$HOME/.aws/config` File

```ini
[default]
region=us-east-1
output = json

[profile test]
region=us-east-1
```

`$HOME/.aws/credentials` File

```ini
[amplify]
aws_access_key_id=<yourkey>
aws_secret_access_key=<yoursecretkey>
[default]
aws_access_key_id=<yourkey>
aws_secret_access_key=<yoursecretkey>
```
