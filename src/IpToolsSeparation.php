<?php

namespace JorisRos;


use IPv4\SubnetCalculator;

class IpToolsSeparation
{
    public static function methodRange(string $range): array
    {
        list($low, $high) = explode(IpTools::SEPARATION_METHOD_RANGE, $range);

        return [
            'low' => $low,
            'high' => $high
        ];
    }

    public static function methodWildcard(string $range): array
    {
        return [
            'low' => str_replace(IpTools::SEPARATION_METHOD_WILDCARD, '0', $range),
            'high' => str_replace(IpTools::SEPARATION_METHOD_WILDCARD, '255', $range),
        ];
    }

    public static function methodSingleIp(string $range): array
    {
        return [
            'low' => $range,
            'high' => $range,
        ];
    }

    public static function methodSubnet(string $range): array
    {
        list($ipRange, $domain) = explode('/', $range);

        $subnet = new SubnetCalculator($ipRange, $domain);
        list($low, $high) = $subnet->getIPAddressRange();

        return [
            'low' => $low,
            'high' => $high,
        ];
    }
}