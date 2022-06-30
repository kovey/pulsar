<?php
/**
 * @description receive message
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 10:22:00
 *
 */
namespace Kovey\Pulsar\Message;

class Receive implements ResInterface
{
    private string $payload;

    private Array $properties;

    private string $publishTime;

    private string $key;

    private string $messageId;

    public function __construct()
    {
        $this->payload = '';
        $this->properties = array();
        $this->publishTime = '';
        $this->key = '';
        $this->messageId = '';
    }

    public function getPayload() : string
    {
        return $this->payload;
    }

    public function getProperties() : Array
    {
        return $this->properties;
    }

    public function getPublishTime() : string
    {
        return $this->publishTime;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public function getMessageId() : string
    {
        return $this->messageId;
    }

    public function decode(Array $data) : ResInterface
    {
        $this->payload = $data['payload'] ?? '';
        if (!empty($this->payload)) {
            $this->payload = base64_decode($this->payload);
        }

        $this->properties = $data['properties'] ?? '';
        $this->publishTime = $data['publishTime'] ?? array();
        $this->key = $data['key'] ?? '';
        $this->messageId = $data['messageId'] ?? '';
        if (!empty($this->messageId)) {
            $this->messageId = base64_decode($this->messageId);
        }
        return $this;
    }

    public function __toString() : string
    {
        return json_encode(array(
            'payload' => $this->payload,
            'properties' => $this->properties,
            'publishTime' => $this->publishTime,
            'key' => $this->key,
            'messageId' => base64_encode($this->messageId)
        ));
    }
}
