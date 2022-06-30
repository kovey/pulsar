<?php
/**
 * @description ack message
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 11:02:34
 *
 */
namespace Kovey\Pulsar\Message;

class Acknowledge implements ReqInterface
{
    private string $messageId;

    public function __construct()
    {
        $this->messageId = '';
    }

    public function setMessageId(string $messageId) : self
    {
        $this->messageId = base64_encode($messageId);
        return $this;
    }

    public function toJson() : string
    {
        return json_encode(array('messageId' => $this->messageId));
    }
}
