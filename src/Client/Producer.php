<?php
/**
 * @description producer of pulsar
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 18:09:11
 *
 */
namespace Kovey\Pulsar\Client;

use Kovey\Pulsar\Message\ResInterface;
use Kovey\Pulsar\Message\Result;
use Kovey\Pulsar\Exception\Disconnect;

class Producer extends Base
{
    protected function init() : void
    {
        $this->type = 'producer';
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

        $res = new Result();
        return $res->decode($data);
    }
}
