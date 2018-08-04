# Iptools
A set of tools that checks and validates ipaddress.

Detects if ipaddress is allowed in given range, by a minus sign between two ipadresses:
```
\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.12-192.168.192.14');
```

Detects if ipaddress is allowed in given range, by a wildcard sign:
```
\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.*');
```
