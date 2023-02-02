<?php

namespace JorisRos;

class IpToolsHelpers
{
    public static function ip2long_v6($ip) {
        $ip_n = inet_pton($ip);
        $bin = '';
        for ($bit = strlen($ip_n) - 1; $bit >= 0; $bit--) {
            $bin = sprintf('%08b', ord($ip_n[$bit])) . $bin;
        }

        if (function_exists('gmp_init')) {
            return gmp_strval(gmp_init($bin, 2), 10);
        } elseif (function_exists('bcadd')) {
            $dec = '0';
            for ($i = 0; $i < strlen($bin); $i++) {
                $dec = bcmul($dec, '2', 0);
                $dec = bcadd($dec, $bin[$i], 0);
            }
            return $dec;
        } else {
            trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
        }
    }

    public static function long2ip_v6($dec) {
        if (function_exists('gmp_init')) {
            $bin = gmp_strval(gmp_init($dec, 10), 2);
        } elseif (function_exists('bcadd')) {
            $bin = '';
            do {
                $bin = bcmod($dec, '2') . $bin;
                $dec = bcdiv($dec, '2', 0);
            } while (bccomp($dec, '0'));
        } else {
            trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
        }

        $bin = str_pad($bin, 128, '0', STR_PAD_LEFT);
        $ip = array();
        for ($bit = 0; $bit <= 7; $bit++) {
            $bin_part = substr($bin, $bit * 16, 16);
            $ip[] = dechex(bindec($bin_part));
        }
        $ip = implode(':', $ip);
        return inet_ntop(inet_pton($ip));
    }
}