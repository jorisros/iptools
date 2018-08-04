<?php

namespace JorisRos;

class IpTools
{
    const SEPARATION_METHOD_WILDCARD = '*';
    const SEPARATION_METHOD_RANGE = '-';
    const SEPARATION_METHOD_NULL = null;

    public static function validateIp(string $ipaddress) : bool
    {
        if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isIpInRange($ip, $range) : bool
    {
        if (!self::validateIp($ip)) {
            return false;
        }

        $range = self::determRange($range);

        if (self::validateIp($range['low']) && self::validateIp($range['high'])) {
            if (ip2long($range['low']) < ip2long($ip) && ip2long($range['high']) > ip2long($ip)) {
                return true;
            }
        } else {
            return false;
        }
        return false;
    }

    public static function determRange($range) : array
    {
        $range = self::stripAndTrimSpaces($range);

        switch (self::determSeperation($range)) {
            case self::SEPARATION_METHOD_RANGE:
                $arr = explode(self::SEPARATION_METHOD_RANGE, $range);

                return [
                    'low' => $arr[0],
                    'high' => $arr[1]
                ];
                break;
            case self::SEPARATION_METHOD_NULL:
            default:
                break;
        }
        return [];
    }

    public static function stripAndTrimSpaces(string $string) : string
    {
        return trim(str_replace(' ', '', $string));
    }

    public static function determSeperation($string) : string
    {
        $pos = strpos($string, self::SEPARATION_METHOD_RANGE);

        if ($pos === false) {
            return self::SEPARATION_METHOD_NULL;
        } else {
            return self::SEPARATION_METHOD_RANGE;
        }

        $pos = strpos($string, self::SEPARATION_METHOD_WILDCARD);

        if ($pos === false) {
            return self::SEPARATION_METHOD_NULL;
        } else {
            return self::SEPARATION_METHOD_WILDCARD;
        }
    }
}
