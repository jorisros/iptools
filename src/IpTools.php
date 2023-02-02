<?php

namespace JorisRos;

use IPv4\SubnetCalculator;

class IpTools
{
    const SEPARATION_METHOD_WILDCARD = '*';
    const SEPARATION_METHOD_RANGE = '-';
    const SEPARATION_METHOD_SINGE_IP = 'single';
    const SEPARATION_METHOD_SUBNET = '/';
    const SEPARATION_METHOD_NULL = null;

    public static function validateIp(string $ipaddress): bool
    {
        if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {
            return true;
        }

        return false;
    }

    public static function isIpv6(string $ipaddress): bool
    {
        if(filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return true;
        }

        return false;
    }

    public static function isIpv4(string $ipaddress): bool
    {
        if(filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return true;
        }

        return false;
    }

    public static function isIpInRange($ip, $range): bool
    {
        if (!self::validateIp($ip)) {
            return false;
        }

        $ranges = self::determRange($range);

        foreach ($ranges as $range) {
            if (self::isRangeValidAndBetween($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    private static function determRange($range): array
    {
        $range = self::stripAndTrimSpaces($range);

        $result = [];

        foreach (self::detectIfMultipleRanges($range) as $range) {
            switch (self::determSeperation($range)) {
                case self::SEPARATION_METHOD_RANGE:
                    $result[] = IpToolsSeparation::methodRange($range);
                    break;
                case self::SEPARATION_METHOD_WILDCARD:
                    $result[] = IpToolsSeparation::methodWildcard($range);
                    break;
                case self::SEPARATION_METHOD_SINGE_IP:
                    $result[] = IpToolsSeparation::methodSingleIp($range);
                    break;
                case self::SEPARATION_METHOD_SUBNET:
                    $result[] = IpToolsSeparation::methodSubnet($range);
                    break;
                case self::SEPARATION_METHOD_NULL:
                default:
                    $result[] = [];
                    break;
            }
        }

        return $result;
    }


    private static function isRangeValidAndBetween(string $ip, array $range): bool
    {
        if (count($range) > 1 && self::validateIp($range['low']) && self::validateIp($range['high'])) {
            if (ip2long($range['low']) <= ip2long($ip) && ip2long($range['high']) >= ip2long($ip)) {
                return true;
            }
        }

        return false;
    }

    private static function stripAndTrimSpaces(string $string): string
    {
        return trim(str_replace(' ', '', $string));
    }

    private static function determSeperation($string): string
    {
        $method = self::SEPARATION_METHOD_NULL;

        $pos = strpos($string, self::SEPARATION_METHOD_RANGE);

        if ($pos != false) {
            $method = self::SEPARATION_METHOD_RANGE;
        }

        $pos = strpos($string, self::SEPARATION_METHOD_WILDCARD);

        if ($pos != false) {
            $method = self::SEPARATION_METHOD_WILDCARD;
        }

        $pos = strpos($string, self::SEPARATION_METHOD_SUBNET);

        if ($pos != false) {
            $method = self::SEPARATION_METHOD_SUBNET;
        }

        if (self::validateIp($string)) {
            $method = self::SEPARATION_METHOD_SINGE_IP;
        }

        return $method;
    }

    private static function detectIfMultipleRanges($range)
    {
        $pos = strpos($range, ',');

        if ($pos === false) {
            return [$range];
        } else {
            return explode(',', $range);
        }
    }
}
