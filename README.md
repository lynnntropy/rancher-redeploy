# rancher-redeploy

`rancher-redeploy` is a convenient CLI tool for redeploying a deployment in Rancher without having to do so via the web UI.

## Requirements

- PHP 7.3

## Installation

Simply download the `.phar` file of the latest [release](https://github.com/omegavesko/rancher-redeploy/releases). You can directly execute this file like so:

```bash
./rancher-redeploy.phar
```

... or, you can install it globally and run it from anywhere:

```bash
sudo mv rancher-redeploy.phar /usr/local/bin/rancher-redeploy
rancher-redeploy --help
```

## Usage

```
Usage:
  rancher-redeploy [options] [--] <name>

Arguments:
  name                         The name of the deployment to redeploy.

Options:
  -s, --namespace[=NAMESPACE]  The Kubernetes namespace the deployment is in.
  -h, --help                   Display this help message
  -q, --quiet                  Do not output any message
  -V, --version                Display this application version
      --ansi                   Force ANSI output
      --no-ansi                Disable ANSI output
  -n, --no-interaction         Do not ask any interactive question
  -v|vv|vvv, --verbose         Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

As an example, if you wanted to redeploy the deployment `test-deployment` in the namespace `test-namespace`, you could do this:

```bash
rancher-redeploy -s test-namespace test-deployment
```

## Authors

- Veselin Romić (omegavesko@gmail.com)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE)
file for details.
