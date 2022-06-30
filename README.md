## Pulsar Websocket Client With PHP
## Description
### Library
### Usage:
    - composer require kovey/pulsar
### Examples
```php
    use Kovey\Pulsar\Client\Producer;
    use Kovey\Pulsar\Client\Comsumer;
    use Kovey\Pulsar\Message\Publish;
    use Kovey\Pulsar\Message\Acknowledge;
    use function Swoole\Coroutine\run;
    use Swoole\Timer;
    use Swoole\Coroutine;

    run(function () {
        $producer = new Producer('ws://127.0.0.1:8080');
        $producer->setTenant('tenant')
                 ->setNamespace('namespace')
                 ->setTopic('topic')
                 ->create();

        Timer::tick(5000, function (int $timerId, Producer $producer) {
            $publish = new Publish();
            $publish->setPayload('hello')
                    ->setProperties(array('key' => 'value'));

            $producer->send($publish);
            $result = $producer->recv();
            echo sprintf('response: %s', $result) . PHP_EOL;
            if ($result->isSuccess()) {
                echo 'send message success' . PHP_EOL;
            }
        }, $producer);

        go (fn () => comsume());
    });

    function comsume() : void
    {
        $comsumer = new Comsumer('ws://127.0.0.1:8080')
        $comsumer->setTenant('tenant')
                 ->setNamespace('namespace')
                 ->setTopic('topic')
                 ->setSubscription('subscription')
                 ->create();

        while (true) {
            $receive = $comsumer->recv();
            echo sprintf('receive data: %s', $receive) . PHP_EOL;
            if (!empty($receive->getMessageId())) {
                $ack = new Acknowledge();
                $comsumer->send($ack->setMessageId($receive->getMessageId()));
            }

            Coroutine::sleep(0.01);
        }
    }

```
