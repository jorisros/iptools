<?php

require_once ('./src/IpTools.php');

use PHPUnit\Framework\TestCase;

class IpTools extends TestCase
{
    public function testValidateIpAddress()
    {
        $this->assertTrue(\JorisRos\IpTools::validateIp('127.0.0.1'));
        $this->assertTrue(\JorisRos\IpTools::validateIp('255.255.255.255'));
        $this->assertTrue(\JorisRos\IpTools::validateIp('0.0.0.0'));
        $this->assertFalse(\JorisRos\IpTools::validateIp(true));
        $this->assertFalse(\JorisRos\IpTools::validateIp('127.0.0.1.0'));
        $this->assertFalse(\JorisRos\IpTools::validateIp('ipaddress'));
    }

    public function testDetermRange()
    {
        $range = \JorisRos\IpTools::determRange('127.0.0.1 - 127.0.0.255');
        $this->assertEquals('127.0.0.1', $range['low']);
        $this->assertEquals('127.0.0.255', $range['high']);

        $range = \JorisRos\IpTools::determRange('127.0.0.1-127.0.0.255');
        $this->assertEquals('127.0.0.1', $range['low']);
        $this->assertEquals('127.0.0.255', $range['high']);

        $range = \JorisRos\IpTools::determRange(' 127.0.0.1 - 127.0.0.255 ');
        $this->assertEquals('127.0.0.1', $range['low']);
        $this->assertEquals('127.0.0.255', $range['high']);
    }

    public function testIsIpInRange()
    {
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0-192.168.192.255'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0 - 192.168.192.255'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.12-192.168.192.14'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.*'));
        $this->assertFalse(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.14-192.168.192.255'));
        $this->assertFalse(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.10-192.168.192.12'));
    }

    public function testStripAndTrimSpaces()
    {
        $string = \JorisRos\IpTools::stripAndTrimSpaces('    ');
        $this->assertEquals(0, strlen($string));

        $string = \JorisRos\IpTools::stripAndTrimSpaces(' - ');
        $this->assertEquals(1, strlen($string));

        $string = \JorisRos\IpTools::stripAndTrimSpaces(' A - B ');
        $this->assertEquals(3, strlen($string));
    }

    public function testDetermSeperation()
    {
        $this->assertEquals(\JorisRos\IpTools::SEPARATION_METHOD_RANGE, \JorisRos\IpTools::determSeperation('A-B'));
    }
}
