<?php

namespace CalendArt\Adapter\Office365;

/**
 * Class WindowsTimezoneTest
 * @package Calendart\Adapter\Office365\Test
 * @author Manuel Raynaud <manuel@wisembly.com>
 */
class WindowsTimezoneTest extends \PHPUnit_Framework_TestCase
{
    public function testValidRegisteredTimezone()
    {
        $this->markTestSkipped('Unable to check all the TZ since Asia/Rangoon is now Asia/Yagon');

        $timezoneList = \DateTimeZone::listIdentifiers();

        $windowsTimezone = new WindowsTimezone();

        $reflection = new \ReflectionProperty($windowsTimezone, 'timezone');
        $reflection->setAccessible(true);

        $registeredTimezone = array_values($reflection->getValue($windowsTimezone));

        $intersect = array_intersect($registeredTimezone, $timezoneList);

        $this->assertEquals($intersect, $registeredTimezone);
    }

    /**
     * @expectedException \CalendArt\Adapter\Office365\Exception\InvalidTimezoneException
     */
    public function testGetIanaTimezoneWithUnregisteredWindowsTimezone()
    {
        $windowsTimezone = new WindowsTimezone();
        $windowsTimezone->getIanaTimezone('foo');
    }

    public function testGetIanaTimezoneWithRegisteredWindowsTimezone()
    {
        $windowsTimezone = new WindowsTimezone();
        $timeZone = $windowsTimezone->getIanaTimezone('Samoa Standard Time');

        $this->assertEquals('Pacific/Apia', $timeZone);
    }

    /**
     * @expectedException \CalendArt\Adapter\Office365\Exception\InvalidTimezoneException
     */
    public function testGetWindowsTimezoneWithUnregisteredIanaTimezone()
    {
        $windowsTimezone = new WindowsTimezone();
        $windowsTimezone->getWindowsTimezone('foo');
    }

    public function testGetWindowsTimezoneWithRegisteredIanaTimezone()
    {
        $windowsTimezone = new WindowsTimezone();
        $timeZone = $windowsTimezone->getWindowsTimezone('Pacific/Apia');

        $this->assertEquals('Samoa Standard Time', $timeZone);
    }
}
