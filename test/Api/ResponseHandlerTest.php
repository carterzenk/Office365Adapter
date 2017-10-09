<?php

namespace CalendArt\Adapter\Office365\Api;

use Psr\Http\Message\ResponseInterface;

class ResponseHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $response;
    private $api;

    protected function setUp()
    {
        $this->response = $this->getMockBuilder(ResponseInterface::class)
            ->setMethods([
                'getStatusCode',
                'getBody'
            ])
            ->getMockForAbstractClass();

        $this->api = new Api;
    }

    public function testHandleErrorsWithSuccessfulResponse()
    {
        $this->response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $this->api->get($this->response);

        $this->response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(301));

        $this->api->get($this->response);
    }

    /**
     * @dataProvider getResponses
     */
    public function testHandleErrors($statusCode, $errorCode, $exception)
    {
        $this->setExpectedException($exception);

        $this->response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($statusCode));

        $this->response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($this->getBody($errorCode)));

        $this->api->get($this->response);
    }

    public function getResponses()
    {
        return [
            [400, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [400, 'ErrorExecuteSearchStaleData', 'CalendArt\Adapter\Office365\Exception\ErrorExecuteSearchStaleDataException'],
            [401, 'Foo', 'CalendArt\Adapter\Office365\Exception\UnauthorizedException'],
            [403, 'Foo', 'CalendArt\Adapter\Office365\Exception\ForbiddenException'],
            [404, 'Foo', 'CalendArt\Adapter\Office365\Exception\NotFoundException'],
            [405, 'Foo', 'CalendArt\Adapter\Office365\Exception\MethodNotAllowedException'],
            [406, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [409, 'Foo', 'CalendArt\Adapter\Office365\Exception\ConflictException'],
            [410, 'Foo', 'CalendArt\Adapter\Office365\Exception\GoneException'],
            [411, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [412, 'Foo', 'CalendArt\Adapter\Office365\Exception\PreconditionException'],
            [413, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [415, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [416, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [422, 'Foo', 'CalendArt\Adapter\Office365\Exception\BadRequestException'],
            [429, 'Foo', 'CalendArt\Adapter\Office365\Exception\LimitExceededException'],
            [500, 'Foo', 'CalendArt\Adapter\Office365\Exception\InternalServerErrorException'],
            [501, 'Foo', 'CalendArt\Adapter\Office365\Exception\NotFoundException'],
            [503, 'Foo', 'CalendArt\Adapter\Office365\Exception\InternalServerErrorException'],
            [507, 'Foo', 'CalendArt\Adapter\Office365\Exception\LimitExceededException'],
            [509, 'Foo', 'CalendArt\Adapter\Office365\Exception\LimitExceededException'],
        ];
    }

    protected function getBody($errorCode = 'Foo')
    {
        return json_encode([
            'error' => [
                'code' => $errorCode,
                'message' => 'foo'
            ]
        ]);
    }
}

class Api
{
    use ResponseHandler;

    /**
     * Simulate a get method of an API
     */
    public function get(ResponseInterface $response)
    {
        $this->handleResponse($response);
    }
}
