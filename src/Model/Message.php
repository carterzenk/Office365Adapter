<?php

namespace CalendArt\Adapter\Office365\Model;

use CalendArt\AbstractMessage;

class Message extends AbstractMessage
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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

        $message->sentDate = new \DateTime($data['createdDateTime']);

        if (isset($data['bodyPreview'])) {
            $message->preview = $data['bodyPreview'];
        }

        if (isset($data['sender']['emailAddress'])) {
            $message->sender = User::hydrate($data['sender']['emailAddress']);
        }

        if (isset($data['body']['contentType']) && isset($data['body']['content'])) {
            if ($data['body']['contentType'] === 'Text') {
                $message->textBody = $data['body']['content'];
            } elseif ($data['body']['contentType'] === 'HTML') {
                $message->htmlBody = $data['body']['content'];
            }
        }

        if (isset($data['toRecipients'])) {
            foreach ($data['toRecipients'] as $recipient) {
                if (isset($recipient['emailAddress'])) {
                    $message->addRecipient(User::hydrate($recipient['emailAddress']));
                }
            }
        }

        return $message;
    }
}
