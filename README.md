[![Build Status](https://travis-ci.org/jorisros/iptools.svg?branch=master)](https://travis-ci.org/jorisros/iptools)
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

Detects if ipaddress is allowed in given range, by a subnetn:
```php
\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0/24');
```
