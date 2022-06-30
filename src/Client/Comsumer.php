<?php
/**
 * @description comsumer of pulsar
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 17:25:58
 *
 */
namespace Kovey\Pulsar\Client;

use Kovey\Pulsar\Message\ResInterface;
use Kovey\Pulsar\Message\Receive;
use Kovey\Pulsar\Exception\Disconnect;

class Comsumer extends Base
{
    protected string $subscription;

    public function setSubscription(string $subscription) : self
    {
        $this->subscription = $subscription;
        return $this;
    }

    protected function init() : void
    {
        $this->type = 'consumer';
    }

    protected function getUrl() : string
    {
        return sprintf("%s/ws/v2/%s/persistent/%s/%s/%s/%s", $this->url, $this->type, $this->tenant, $this->namespace, $this->topic, $this->subscription);
    }

    public function recv() : ResInterface
    {
        $result = $this->client->recv();
        if (empty($result)) {
            throw new Disconnect(sprintf('connection is disconnect, error: %s', $this->client->getError()));
        }

        $data = json_decode($result, true);
        if (empty($data)) {
            $data = array();
        }

        $receive = new Receive();
        return $receive->decode($data);
    }
}
