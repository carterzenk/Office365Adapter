<?php

namespace CalendArt\Adapter\Office365\Api;

use CalendArt\Adapter\Office365\Office365Adapter;

class MailApiTest extends \PHPUnit_Framework_TestCase
{
    private $exampleListResponseData;

    /**
     * @var MailApi
     */
    private $mailApi;

    /**
     * @var Office365Adapter | \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapter;

    public function setUp()
    {
        $this->adapter = $this->getMockBuilder(Office365Adapter::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'sendRequest'
            ])
            ->getMock();

        $this->mailApi = new MailApi($this->adapter);

        $this->exampleListResponseData = [
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

    public function testItUrlEncodesSearch()
    {
        $expectedUri = '/messages?%24search=%22hello+world%22&%24top=10';

        $this->adapter->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo('get'),
                $this->equalTo($expectedUri)
            )
            ->willReturn($this->exampleListResponseData);

        $this->mailApi->getList('hello world');
    }

    public function testItUrlEncodesPageToken()
    {
        $expectedUri = '/messages?%24search=%22hello+world%22&%24skiptoken=MSZZVlF3TkZreVNtMU9lbFUxVFdreGFFNVVRbWxNVkZKdFQxUkpkRmxxWkdwTlV6QXlXVlJhYVU1cVZYbGFSMVV5V1dwSmJXTjZNSGhOUVQwOQ%3D%3D&%24top=10';

        $this->adapter->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo('get'),
                $this->equalTo($expectedUri)
            )
            ->willReturn($this->exampleListResponseData);

        $this->mailApi->getList(
            'hello world',
            'MSZZVlF3TkZreVNtMU9lbFUxVFdreGFFNVVRbWxNVkZKdFQxUkpkRmxxWkdwTlV6QXlXVlJhYVU1cVZYbGFSMVV5V1dwSmJXTjZNSGhOUVQwOQ==',
            10
            );
    }

}