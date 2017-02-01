<?php

namespace CalendArt\Adapter\Office365\Model;

use Doctrine\Common\Collections\Collection;

class MessageSetTest extends \PHPUnit_Framework_TestCase
{
    protected $data;

    public function setUp()
    {
        parent::setUp();

        $this->data = [
            '@odata.nextLink' => 'https://graph.microsoft.com/v1.0/messages/me?$skiptoken=Ajde9DHUwkxk-sa&$search="example"',
            'value' => [
                [
                    'id' => "fdsfh278392hf-9hf9dsh8hs",
                    'createdDateTime' => '2017-01-05T10:37:21Z',
                    'subject' => 'Example',
                    'body' => [
                        'contentType' => 'Text',
                        'content' => 'example content'
                    ],
                    'bodyPreview' => 'example...',
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => 'test@user.com',
                                'name' => 'Test User'
                            ]
                        ],
                        [
                            'emailAddress' => [
                                'address' => 'example@user.com',
                                'name' => 'Example User'
                            ]
                        ]
                    ],
                    'sender' => [
                        'emailAddress' => [
                            'address' => 'sender@example.com',
                            'name' => 'Sender Example'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function testHydrate()
    {
        $messageSet = MessageSet::hydrate($this->data);

        $this->assertEquals('Ajde9DHUwkxk-sa', $messageSet->getNextPageToken());
        $this->assertInstanceOf(Collection::class, $messageSet->getMessages());
        $this->assertEquals(1, $messageSet->getMessages()->count());
        $this->assertInstanceOf(Message::class, $messageSet->getMessages()->first());
    }
}