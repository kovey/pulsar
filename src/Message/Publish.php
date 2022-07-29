<?php
/**
 * @description publish message
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 10:22:00
 *
 */
namespace Kovey\Pulsar\Message;

class Publish implements ReqInterface
{
    private string $payload;

    private Array | \ArrayObject $properties;

    private string $context;

    private string $key;

    private Array $replicationClusters;

    public function __construct()
    {
        $this->payload = '';
        $this->properties = new \ArrayObject();
        $this->context = '';
        $this->key = '';
        $this->replicationClusters = array();
    }

    public function setPayload(string $payload) : self
    {
        $this->payload = base64_encode($payload);
        return $this;
    }

    public function setProperties(Array | \ArrayObject $properties) : self
    {
        $this->properties = $properties;
        if (empty($this->properties)) {
            $this->properties = new \ArrayObject();
        }

        return $this;
    }

    public function setContext(string $context) : self
    {
        $this->context = $context;
        return $this;
    }

    public function setKey(string $key) : self
    {
        $this->key = $key;
        return $this;
    }

    public function setReplicationClusters(Array $replicationClusters) : self
    {
        $this->replicationClusters = $replicationClusters;
        return $this;
    }

    public function getContext() : string
    {
        return $this->context;
    }

    public function toJson() : string
    {
        return json_encode(array(
            'payload' => $this->payload,
            'properties' => $this->properties,
            'context' => $this->context,
            'key' => $this->key,
            'replicationClusters' => $this->replicationClusters
        ));
    }
}
