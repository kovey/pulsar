<?php
/**
 * @description client base
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 17:25:58
 *
 */
namespace Kovey\Pulsar\Client;

use Kovey\Pulsar\WebSocket\Client;
use Kovey\Pulsar\Exception\ConnectFailure;
use Kovey\Pulsar\Exception\Disconnect;
use Kovey\Pulsar\Message\ReqInterface;
use Kovey\Pulsar\Message\ResInterface;

abstract class Base
{
    protected Client $client;

    protected string $tenant;

    protected string $namespace;

    protected string $topic;

    protected string $url;

    protected string $type;

    final public function __construct(string $url)
    {
        $this->url = $url;
        $this->init();
    }

    public function setTenant(string $tenant) : self
    {
        $this->tenant = $tenant;
        return $this;
    }

    public function setNamespace(string $namespace) : self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function setTopic(string $topic) : self
    {
        $this->topic = $topic;
        return $this;
    }

    protected function getUrl() : string
    {
        return sprintf("%s/ws/v2/%s/persistent/%s/%s/%s", $this->url, $this->type, $this->tenant, $this->namespace, $this->topic);
    }

    public function create() : self
    {
        if (!empty($this->client)) {
            $this->client->close();
        } else {
            $this->client = new Client($this->getUrl());
        }

        if (!$this->client->connect()) {
            throw new ConnectFailure(sprintf('connect to %s failure, error: %s', $this->url, $this->client->getError()));
        }

        return $this;
    }

    public function send(ReqInterface $msg) : self
    {
        if (!$this->client->push($msg->toJson())) {
            throw new Disconnect(sprintf('send failure, connect is disconnect, error: %s', $this->client->getError()));
        }

        return $this;
    }

    final public function __destruct()
    {
        if (empty($this->client)) {
            return;
        }

        $this->client->close();
    }

    abstract public function recv() : ResInterface;

    abstract protected function init() : void;
}
