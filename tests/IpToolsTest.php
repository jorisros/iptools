<?php

require_once ('./src/IpTools.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use JorisRos\IpTools;
use PHPUnit\Framework\TestCase;

class IpToolsTest extends TestCase
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
        $method = new ReflectionMethod('\JorisRos\IpTools', 'determRange');
        $method->setAccessible(true);

        $range = $method->invoke(new JorisRos\IpTools, '127.0.0.1 - 127.0.0.255');
        $this->assertEquals('127.0.0.1', $range[0]['low']);
        $this->assertEquals('127.0.0.255', $range[0]['high']);

        $range = $method->invoke(new JorisRos\IpTools, '127.0.0.1-127.0.0.255');
        $this->assertEquals('127.0.0.1', $range[0]['low']);
        $this->assertEquals('127.0.0.255', $range[0]['high']);

        $range = $method->invoke(new JorisRos\IpTools, ' 127.0.0.1 - 127.0.0.255 ');
        $this->assertEquals('127.0.0.1', $range[0]['low']);
        $this->assertEquals('127.0.0.255', $range[0]['high']);
    }

    public function testIsIpInRange()
    {
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0-192.168.192.255'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0 - 192.168.192.255'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0 - 192.168.192.255,10.27.1.10-10.27.1.16'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '10.27.1.10-10.27.1.16,192.168.192.0 - 192.168.192.255'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.12-192.168.192.14'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.*'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.13'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.13,192.168.192.15'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.15,192.168.192.13'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.0/24'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '10.27.1.*,192.168.192.*'));
        $this->assertTrue(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.*,10.27.1.*'));
        $this->assertFalse(\JorisRos\IpTools::isIpInRange('192.168.2.13', '192.168.192.*,10.27.1.*'));
        $this->assertFalse(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.14-192.168.192.255'));
        $this->assertFalse(\JorisRos\IpTools::isIpInRange('192.168.192.13', '192.168.192.10-192.168.192.12'));
    }

    public function testDetectIfMultipleRanges()
    {

        $method = new ReflectionMethod('\JorisRos\IpTools', 'detectIfMultipleRanges');
        $method->setAccessible(true);

        $this->assertEquals(2, count($method->invoke(new JorisRos\IpTools, '192.168.192.0-192.168.192.255,192.168.192.0-192.168.192.255')));
        $this->assertEquals(1, count($method->invoke(new JorisRos\IpTools, '192.168.192.0-192.168.192.255')));

    }

    public function testStripAndTrimSpaces()
    {
        $method = new ReflectionMethod('\JorisRos\IpTools', 'stripAndTrimSpaces');
        $method->setAccessible(true);
        
        $this->assertEquals(0, strlen($method->invoke(new JorisRos\IpTools, '    ')));
        $this->assertEquals(1, strlen($method->invoke(new JorisRos\IpTools, ' - ')));
        $this->assertEquals(3, strlen($method->invoke(new JorisRos\IpTools, ' A - B ')));
    }

    public function testDetermSeperation()
    {
        $method = new ReflectionMethod('\JorisRos\IpTools', 'determSeperation');
        $method->setAccessible(true);

        $this->assertEquals(\JorisRos\IpTools::SEPARATION_METHOD_RANGE, $method->invoke(new JorisRos\IpTools, 'A-B'));
    }
}
