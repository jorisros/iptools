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

        if (IpTools::isIpv4($ipRange)) {
            $subnet = new SubnetCalculator($ipRange, $domain);
            list($low, $high) = $subnet->getIPAddressRange();
        }

        if (IpTools::isIpv6($ipRange)) {
            $range  = self::prefixToRange($range);
            var_dump($range);
            $high = $range['high'];
            var_dump(inet_pton($high));
            $low = $range['low'];
        }

        return [
            'low' => $low,
            'high' => $high,
        ];
    }

    private static function prefixToRange($ipAddress, $a_WantBins = false)
    {
        // Validate input superficially with a RegExp and split accordingly
        if(!preg_match('~^([0-9a-f:]+)[[:punct:]]([0-9]+)$~i', trim($ipAddress), $v_Slices)){
            return null;
        }
var_dump($v_Slices);
        $v_PrefixLength = intval($v_Slices[2]);
        if($v_PrefixLength > 128){
            return null; // kind'a stupid :)
        }
        $v_SuffixLength = 128 - $v_PrefixLength;

        // Convert the binary string to a hexadecimal string
        $v_FirstAddressBin = inet_pton($v_Slices[1]);
        $v_FirstAddressHex = bin2hex($v_FirstAddressBin);

        // Build the hexadecimal string of the network mask
        // (if the manually formed binary is too large, base_convert() chokes on it... so we split it up)
        $v_NetworkMaskHex = str_repeat('1', $v_PrefixLength) . str_repeat('0', $v_SuffixLength);
        $v_NetworkMaskHex_parts = str_split($v_NetworkMaskHex, 8);
        foreach($v_NetworkMaskHex_parts as &$v_NetworkMaskHex_part){
            $v_NetworkMaskHex_part = base_convert($v_NetworkMaskHex_part, 2, 16);
            $v_NetworkMaskHex_part = str_pad($v_NetworkMaskHex_part, 2, '0', STR_PAD_LEFT);
        }
        $v_NetworkMaskHex = implode("", $v_NetworkMaskHex_parts);
        unset($v_NetworkMaskHex_part, $v_NetworkMaskHex_parts);
        $v_NetworkMaskBin = inet_pton(implode(':', str_split($v_NetworkMaskHex, 4)));

        // We have the network mask so we also apply it to First Address
        $v_FirstAddressBin &= $v_NetworkMaskBin;

        $v_FirstAddressHex = bin2hex($v_FirstAddressBin);
var_dump(\JorisRos\IpToolsHelpers::ip2long_v6('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
var_dump(\JorisRos\IpToolsHelpers::long2ip_v6('42540766452641154071740215577757643572'));
var_dump(inet_pton('2001:1c02:c1e:b00:f92d:122a:680e:3443'));
die();
        // Convert the last address in hexadecimal
        $v_LastAddressBin = $v_FirstAddressBin | ~$v_NetworkMaskBin;
        $v_LastAddressHex =  bin2hex($v_LastAddressBin);

        // Return a neat object with information
        return [
            'low'  => $v_FirstAddressHex,
            'high'   => $v_LastAddressHex
        ];
    }

}