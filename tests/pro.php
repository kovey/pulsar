<?php
/**
 * @description producer test
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 11:44:00
 *
 */
require __DIR__ . '/../vendor/autoload.php';

use Kovey\Pulsar\Pulsar;
use Kovey\Pulsar\Message\Publish;
use Kovey\Logger\Debug;
use function Swoole\Coroutine\run;
use Swoole\Timer;
use Swoole\Event;

run(function () {
    Debug::setLevel(Debug::LOG_LEVEL_INFO);

    $pulsar = new Pulsar('ws://127.0.0.1:8080');
    $pulsar->setTenant('tenant')
           ->setNamespace('namespace')
           ->setTopic('topic');

    Timer::tick(5000, function (int $timerId, Pulsar $pulsar) {
        Debug::debug('timer run, timerId: %s', $timerId);

        $publish = new Publish();
        $publish->setPayload('hello')
                ->setProperties(array('test' => 'kovey'));
        $pulsar->push($publish);
    }, $pulsar);
    go (fn (Pulsar $pulsar) => $pulsar->loop(), $pulsar);
});
