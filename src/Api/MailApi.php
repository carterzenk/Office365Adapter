<?php

namespace CalendArt\Adapter\Office365\Api;

use CalendArt\Adapter\MailApiInterface;
use CalendArt\Adapter\Office365\Model\Message;
use CalendArt\Adapter\Office365\Office365Adapter;
use Doctrine\Common\Collections\ArrayCollection;

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

    /** {@inheritDoc} */
    public function getList($search, $pageToken = null)
    {
        $headers = [];

        if (isset($search)) {
            $headers['query']['search'] = $search;
        }

        if (isset($pageToken)) {
            $headers['query']['skipToken'] = $pageToken;
        }

        $result = $this->adapter->sendRequest('get', '/messages', $headers);

        $list = new ArrayCollection;

        foreach ($result['value'] as $item) {
            $list[$item['id']] = Message::hydrate($item);
        }

        return $list;
    }

    /** {@inheritDoc} */
    public function get($identifier)
    {
        $result = $this->adapter->sendRequest('get', sprintf('/messages/%s', $identifier));
        return Message::hydrate($result);
    }
}
