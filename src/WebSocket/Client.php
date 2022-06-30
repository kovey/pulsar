<?php
/**
 * @description websocket client
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-28 18:38:32
 *
 */
namespace Kovey\Pulsar\WebSocket;

use Swoole\Coroutine\Http\Client as HC;
use Kovey\Logger\Debug;

class Client
{
    private HC $client;

    private string $host;

    private int $port;

    private bool $isSsl;

    private string $path;

    public function __construct(string $url)
    {
        $info = parse_url($url);
        $this->isSsl = isset($info['scheme']) && $info['scheme'] == 'wss';

        $this->host = $info['host'] ?? '';
        if (!isset($info['port'])) {
            $this->port = $this->isSsl ? 443 : 80;
        } else {
            $this->port = $info['port'];
        }
        $this->path = $info['path'] ?? '/';
    }

    public function connect() : bool
    {
        $this->client = new HC($this->host, $this->port, $this->isSsl);
        Debug::debug('upgrade path: %s', $this->path);
        return $this->client->upgrade($this->path);
    }

    public function push(string $data) : bool
    {
        return $this->client->push($data, WEBSOCKET_OPCODE_TEXT);
    }

    public function recv() : string
    {
        $result = $this->client->recv();

        if (empty($result)) {
            return '';
        }

        return $result->data;
    }

    public function isConnected() : bool
    {
        return $this->client->errCode == 0;
    }

    public function close() : void
    {
        if (empty($this->client)) {
            return;
        }

        $this->client->close();
    }

    public function getError() : string
    {
        return socket_strerror($this->client->errCode);
    }

    public function getStatusCode() : int
    {
        return $this->client->statusCode;
    }
}
