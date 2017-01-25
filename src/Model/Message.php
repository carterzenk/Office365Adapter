<?php

namespace CalendArt\Adapter\Office365\Model;

use CalendArt\AbstractMessage;

class Message extends AbstractMessage
{
    /** @var string */
    protected $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function hydrate(array $data)
    {
        $message = new static();

        $message->id = $data['id'];
        $message->subject = $data['subject'];
        $message->preview = $data['bodyPreview'];

        $message->sentDate = new \DateTime($data['createdDateTime']);

        if (isset($data['sender']) &&
            isset($data['sender']['emailAddress']) &&
            isset($data['sender']['emailAddress']['address'])) {
            $message->sender = $data['sender']['emailAddress']['address'];
        }

        if (isset($data['body'])){
            if (isset($data['body']['contentType']) && isset($data['body']['content'])) {
                $content = $data['body']['content'];
                if ($data['body']['contentType'] == 'Text') {
                    $message->textBody = $content;
                } elseif ($data['body']['contentType'] === 'HTML') {
                    $message->htmlBody = $content;
                }
            }
        }

        if (isset($data['toRecipients'])) {
            foreach ($data['toRecipients'] as $recipient) {
                if (isset($recipient['emailAddress']) && isset($recipient['emailAddress']['address'])) {
                    $message->addRecipient($recipient['emailAddress']['address']);
                }
            }
        }


        return $message;
    }
}