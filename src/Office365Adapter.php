<?php
/**
 * This file is part of the CalendArt package
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 *
 * @copyright Wisembly
 * @license   http://www.opensource.org/licenses/MIT-License MIT License
 */

namespace CalendArt\Adapter\Office365;

use Http\Client\HttpClient;

use Http\Client\Common\PluginClient;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;

use Http\Message\UriFactory;
use Http\Message\MessageFactory;
use Http\Message\Authentication\Bearer;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;

use CalendArt\Adapter\AdapterInterface;

use CalendArt\Adapter\Office365\Api\CalendarApi;
use CalendArt\Adapter\Office365\Api\EventApi;
use CalendArt\Adapter\Office365\Api\MailApi;
use CalendArt\Adapter\Office365\Api\ResponseHandler;

/**
 * Office365 Adapter - He knows how to dialog with office 365's calendars !
 *
 * This requires to have an OAuth2 token established with the following scopes :
 * - Calendar.read
 * - Calendar.write
 *
 * @author Baptiste Clavié <baptiste@wisembly.com>
 */
class Office365Adapter implements AdapterInterface
{
    use ResponseHandler;

    /** @var HttpClient */
    private $client;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var CalendarApi */
    private $calendarApi;

    /** @var EventApi */
    private $eventApi;

    /** @var MailApi */
    private $mailApi;

    /**
     * @param string $token access token delivered by azure's oauth system
     * @param HttpClient $client
     * @param MessageFactory $messageFactory
     * @param UriFactory $uriFactory
     */
    public function __construct(
        $token,
        HttpClient $client = null,
        MessageFactory $messageFactory = null,
        UriFactory $uriFactory = null
    ) {
        $uriFactory = $uriFactory ?: UriFactoryDiscovery::find();

        $this->client = new PluginClient(
            $client ?: HttpClientDiscovery::find(),
            [
                new AuthenticationPlugin(new Bearer($token)),
                new BaseUriPlugin($uriFactory->createUri(
                    $uriFactory->createUri('https://graph.microsoft.com/v1.0/me')
                )),
                new RedirectPlugin,
                new ContentLengthPlugin,
                new HeaderDefaultsPlugin([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
            ]
        );

        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();
    }

    /** {@inheritDoc} */
    public function getCalendarApi()
    {
        if ($this->calendarApi === null) {
            $this->calendarApi = new CalendarApi($this);
        }

        return $this->calendarApi;
    }

    /** {@inheritDoc} */
    public function getEventApi()
    {
        if ($this->eventApi === null) {
            $this->eventApi = new EventApi($this);
        }

        return $this->eventApi;
    }

    /** {@inheritDoc} */
    public function getMailApi()
    {
        if ($this->mailApi === null) {
            $this->mailApi = new MailApi($this);
        }

        return $this->mailApi;
    }

    /** {@inheritDoc} */
    public function getTaskApi()
    {
        throw new \Exception('The Microsoft Graph Task API is still in beta');
    }

    /** {@inheritDoc} */
    public function getTaskGroupApi()
    {
        throw new \Exception('The Microsoft Graph Task API is still in beta');
    }

    /**
     * @param $method
     * @param $uri
     * @param array $headers
     * @param null $body
     * @return array
     * @throws Exception\BadRequestException
     * @throws Exception\ConflictException
     * @throws Exception\ErrorExecuteSearchStaleDataException
     * @throws Exception\ForbiddenException
     * @throws Exception\GoneException
     * @throws Exception\InternalServerErrorException
     * @throws Exception\LimitExceededException
     * @throws Exception\MethodNotAllowedException
     * @throws Exception\NotFoundException
     * @throws Exception\PreconditionException
     * @throws Exception\UnauthorizedException
     * @throws \Exception
     */
    public function sendRequest($method, $uri, array $headers = [], $body = null)
    {
        // deal with query string parameters
        if (isset($headers['query'])) {
            $uri = sprintf('%s?%s', $uri, implode('&', array_map(function ($k, $v) {
                $v = is_array($v) ? implode(',', $v) : $v;
                return sprintf('%s=%s', $k, $v);
            }, array_keys($headers['query']), array_values($headers['query']))));
            unset($headers['query']);
        }

        $response = $this->client->sendRequest(
            $this->messageFactory->createRequest($method, $uri, $headers, $body)
        );
        $this->handleResponse($response);
        $result = json_decode($response->getBody(), true);

        if (null === $result) {
            throw new \Exception($response);
        }

        return $result;
    }
}
