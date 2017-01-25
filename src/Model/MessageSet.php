<?php

namespace CalendArt\Adapter\Office365\Model;

use CalendArt\MessageSet as CalendArtMessageSet;

class MessageSet extends CalendArtMessageSet
{
    /**
     * @param array $data
     * @return self
     */
    public static function hydrate(array $data)
    {
        $skipToken = null;

        if (isset($data['@odata.nextLink'])) {
            $parts = parse_url($data['@odata.nextLink']);
            parse_str($parts['query'], $query);

            if (isset($query['$skipToken'])) {
                $skipToken = $query['$skipToken'];
            }
        }

        $messageSet = new static($skipToken);

        foreach ($data['value'] as $item) {
            $messageSet->addMessage(Message::hydrate($item));
        }

        return $messageSet;
    }
}
