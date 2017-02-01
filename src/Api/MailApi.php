<?php

namespace CalendArt\Adapter\Office365\Api;

use CalendArt\Adapter\MailApiInterface;
use CalendArt\Adapter\Office365\Model\Message;
use CalendArt\Adapter\Office365\Office365Adapter;
use CalendArt\Adapter\Office365\Model\MessageSet;

class MailApi implements MailApiInterface
{
    /**
     * @var Office365Adapter
     */
    private $adapter;

    /**
     * MailApi constructor.
     * @param Office365Adapter $adapter
     */
    public function __construct(Office365Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function getList($search = '', $pageToken = '', $pageSize = 10)
    {
        $url = '/messages';
        $params = [];

        if (!empty($search)) {
            $params['$search'] = $search;
        }

        if (!empty($pageToken)) {
            $params['$skipToken'] = $pageToken;
        }

        if (!empty($pageSize)) {
            $params['$top'] = $pageSize;
        }

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $result = $this->adapter->sendRequest('get', $url);

        return MessageSet::hydrate($result);
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        $result = $this->adapter->sendRequest('get', sprintf('/messages/%s', $identifier));
        return Message::hydrate($result);
    }
}
