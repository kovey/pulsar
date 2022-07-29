<?php
/**
 * @description push data when in custom process
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-28 18:30:20
 *
 */
namespace Kovey\Pulsar;

use Swoole\Coroutine\Channel;
use Kovey\Pulsar\Client\Producer;
use Swoole\Coroutine;
use Kovey\Pulsar\Message\Publish;
use Kovey\Pulsar\Message\Result;
use Kovey\Logger\Debug;
use Kovey\Pulsar\Exception\Disconnect;
use Kovey\Pulsar\Exception\ConnectFailure;

class Pulsar
{
    private Channel $queue;

    private Producer $producer;

    private int $context;

    public function __construct(string $url)
    {
        $this->queue = new Channel(2048);
        $this->producer = new Producer($url);
        $this->context = 1;
    }

    public function setTenant(string $tenant) : self
    {
        $this->producer->setTenant($tenant);
        return $this;
    }

    public function setNamespace(string $namespace) : self
    {
        $this->producer->setNamespace($namespace);
        return $this;
    }

    public function setTopic(string $topic) : self
    {
        $this->producer->setTopic($topic);
        return $this;
    }

    public function push(Publish $data) : self
    {
        $data->setContext($this->context);
        $this->context ++;
        $this->queue->push($data);
        Debug::debug('push data to queue: %s', $data->toJson());
        return $this;
    }

    public function loop() : void
    {
        $this->producer->create();

        while (true) {
            Coroutine::sleep(0.01);
            $data = $this->queue->pop(-1);
            Debug::debug('push data: %s', $data->toJson());
            try {
                $this->producer->send($data);
            } catch (Disconnect $e) {
                Debug::debug('push data failure, error: %s', $e->getMessage());
                try {
                    $this->producer->create()->send($data);
                } catch (ConnectFailure $e) {
                    Debug::debug('connect to server fialure: %s', $e->getMessage());
                }
            }

            $result = $this->producer->recv();
            Debug::debug('recv data: %s', $result);
            if (!$result->isSuccess()) {
                $this->queue->push($data);
            }
        }
    }
}
