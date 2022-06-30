<?php
/**
 * @description response interface
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 17:39:01
 *
 */
namespace Kovey\Pulsar\Message;

interface ResInterface
{
    public function decode(Array $data) : self;

    public function __toString() : string;
}
