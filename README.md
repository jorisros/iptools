![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/jorisros/iptools/tests.yaml)
![Packagist License](https://img.shields.io/packagist/l/jorisros/iptools)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/jorisros/iptools/php)
[![Maintainability](https://api.codeclimate.com/v1/badges/6509bedcbd5997b6db0e/maintainability)](https://codeclimate.com/github/jorisros/iptools/maintainability)



# Iptools
A set of tools that checks and validates ipaddress.

Detect if the ipaddress is valid:
```php
\JorisRos\IpTools::validateIp('192.168.192.13');
```

Detects if ipaddress is allowed in given range, by a minus sign between two ipadresses:
```php
\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.12-192.168.192.14');
```

Detects if ipaddress is allowed in given range, by a wildcard sign:
```php
\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.*,192.168.192.*');
```

Detects if ipaddress is allowed in given range, by a subnet:
```php
\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0/24');
```

# Installation
Installation can be done by composer
```
composer require jorisros/iptools
```
And use the autoloader to load it
```php
<?php
require __DIR__.'/vendor/autoload.php';

$isValid = \JorisRos\IpTools::validateIp('192.168.192.13');

```