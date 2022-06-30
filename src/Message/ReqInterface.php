<?php
/**
 * @description request interface
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 17:39:01
 *
 */
namespace Kovey\Pulsar\Message;

interface ReqInterface
{
    public function toJson() : string;
}
