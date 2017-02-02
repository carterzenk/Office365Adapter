<?php

namespace CalendArt\Adapter\Office365\Model;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrateDates()
    {
        $event = Event::hydrate([
            'id' => 1,
            'changeKey' => "foo",
            'createdDateTime' => "2016-03-30T13:16:46.2214781Z",
            'lastModifiedDateTime' => "2016-03-30T13:16:51.9873431Z",
            'start' => [
                "dateTime" => "2016-04-05T12:00:00.0000000",
                "timeZone" => "UTC",
            ],
            'end' => [
                "dateTime" => "2016-04-05T15:00:00.0000000",
                "timeZone" => "UTC",
            ],
            "recurrence" => null,
            "isAllDay" => false,
            "isCancelled" => false,
            "categories" => [],
            "importance" => "normal",
            "showAs" => "busy",
            "type" => "singleInstance",
            "organizer" => [
                "emailAddress" => [
                    "name" => "John Doe",
                    "address" => "john.doe@calendart.com",
                ],
            ],
        ]);

        $this->assertEquals('2016-04-05T12:00:00+00:00', $event->getStart()->format('c'));
    }

    public function testHydrateNoOwner()
    {
        $data = [
            'id' => 1,
            'changeKey' => "foo",
            'createdDateTime' => "2016-03-30T13:16:46.2214781Z",
            'lastModifiedDateTime' => "2016-03-30T13:16:51.9873431Z",
            'start' => [
                "dateTime" => "2016-04-05T12:00:00.0000000",
                "timeZone" => "UTC",
            ],
            'end' => [
                "dateTime" => "2016-04-05T15:00:00.0000000",
                "timeZone" => "UTC",
            ],
            "recurrence" => null,
            "isAllDay" => false,
            "isCancelled" => false,
            "categories" => [],
            "importance" => "normal",
            "showAs" => "busy",
            "type" => "singleInstance"
        ];

        $event = Event::hydrate($data);

        $this->assertNull($event->getOwner());
    }

    public function testHydrateOwnerNull()
    {
        $data = [
            'id' => 1,
            'changeKey' => "foo",
            'createdDateTime' => "2016-03-30T13:16:46.2214781Z",
            'lastModifiedDateTime' => "2016-03-30T13:16:51.9873431Z",
            'start' => [
                "dateTime" => "2016-04-05T12:00:00.0000000",
                "timeZone" => "UTC",
            ],
            'end' => [
                "dateTime" => "2016-04-05T15:00:00.0000000",
                "timeZone" => "UTC",
            ],
            "recurrence" => null,
            "isAllDay" => false,
            "isCancelled" => false,
            "categories" => [],
            "importance" => "normal",
            "showAs" => "busy",
            "type" => "singleInstance",
            'organizer' => null
        ];

        $event = Event::hydrate($data);

        $this->assertNull($event->getOwner());
    }

    public function testSetStartWithNoEnd()
    {
        $event = new Event();
        $dt = new \DateTime();
        $event->setStart($dt);
        $this->assertEquals($dt, $event->getStart());
    }

    public function testStartWithEndBeforeThrowsException()
    {
        $event = new Event();
        $event->setEnd(new \DateTime('-1 hours'));
        $this->expectException(\Exception::class);
        $event->setStart(new \DateTime());
    }

    public function testSetEndWithNoStart()
    {
        $event = new Event();
        $dt = new \DateTime();
        $event->setEnd($dt);
        $this->assertEquals($dt, $event->getEnd());
    }

    public function testEndWithStartAfterThrowsException()
    {
        $event = new Event();
        $event->setStart(new \DateTime('+1 hours'));
        $this->expectException(\Exception::class);
        $event->setEnd(new \DateTime());
    }
}
