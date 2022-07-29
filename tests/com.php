<?php
/**
 * @description comsumer test
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 11:44:00
 *
 */
require __DIR__ . '/../vendor/autoload.php';

use Kovey\Pulsar\Client\Comsumer;
use Kovey\Logger\Debug;
use function Swoole\Coroutine\run;
use Swoole\Timer;
use Swoole\Coroutine;
use Kovey\Pulsar\Message\Acknowledge;

run(function () {
    Debug::setLevel(Debug::LOG_LEVEL_INFO);

    $comsumer = new Comsumer('ws://127.0.0.1:8080');
    $comsumer->setTenant('tenant')
             ->setNamespace('namespace')
             ->setTopic('notice_user_gen')
             ->setSubscription('first')
             ->create();
    while (true) {
        $res = $comsumer->recv();
        Debug::debug('recv data: %s, ', $res);
        if (!empty($res->getMessageId())) {
            $ack = new Acknowledge();
            $comsumer->send($ack->setMessageId($res->getMessageId()));
        }

        Coroutine::sleep(0.01);
    }
});
