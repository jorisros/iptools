<?php

namespace JorisRos\IpTools;

class IpTools
{
    public static function validateIp (string $ipaddress) : bool
    {
        if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isIpInRange ($ip, $range) : bool
    {
        if (!self::validateIp($ip)) {
            return false;
        }

        $range = self::determRange($range);

    }

    public static function determRange ($range) : array
    {

    }
}
