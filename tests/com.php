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
use Kovey\Pulsar\Exception\Disconnect;
use Kovey\Pulsar\Exception\ConnectFailure;

run(function () {
    Debug::setLevel(Debug::LOG_LEVEL_INFO);

    $comsumer = new Comsumer('ws://127.0.0.1:8080');
    $comsumer->setTenant('cat')
             ->setNamespace('opt')
             ->setTopic('notice_user_gen')
             ->setSubscription('first')
             ->create();
    while (true) {
        try {
            $res = $comsumer->recv();
            Debug::debug('recv data: %s, ', $res);
            if (!empty($res->getMessageId())) {
                $ack = new Acknowledge();
                $comsumer->send($ack->setMessageId($res->getMessageId()));
            }
        } catch (Disconnect $e) {
            Debug::debug('disconnect: %s', $e->getMessage());
            try {
                $comsumer->create();
            } catch(ConnectFailure $e) {
                Debug::debug('connect failure: %s', $e->getMessage());
            }
        }

        Coroutine::sleep(0.01);
    }
});
