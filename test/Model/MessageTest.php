<?php

namespace CalendArt\Adapter\Office365\Model;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrateWithTextBody()
    {
        $data = [
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
        ];

        $message = Message::hydrate($data);

        $this->assertEquals("fdsfh278392hf-9hf9dsh8hs", $message->getId());
        $this->assertEquals("2017-01-05T10:37:21+00:00", $message->getSentDate()->format('c'));
        $this->assertEquals("Example", $message->getSubject());
        $this->assertEquals("example content", $message->getTextBody());
        $this->assertNull($message->getHtmlBody());
        $this->assertEquals("example...", $message->getPreview());
        $this->assertEquals(2, $message->getRecipients()->count());
        $this->assertInstanceOf(User::class, $message->getSender());
        $this->assertEquals('sender@example.com', $message->getSender()->getEmail());
        $this->assertEquals('Sender Example', $message->getSender()->getName());
    }

    public function testHydrateWithHtmlBody()
    {
        $data = [
            'id' => "fdsfh278392hf-9hf9dsh8hs",
            'createdDateTime' => '2017-01-05T10:37:21Z',
            'subject' => 'Example',
            'body' => [
                'contentType' => 'HTML',
                'content' => '<p>example content</p>'
            ],
            'bodyPreview' => 'example...',
            'sender' => [
                'emailAddress' => [
                    'address' => 'sender@example.com',
                    'name' => 'Sender Example'
                ]
            ]
        ];

        $message = Message::hydrate($data);

        $this->assertNull($message->getTextBody());
        $this->assertEquals('<p>example content</p>', $message->getHtmlBody());
    }
}