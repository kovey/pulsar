<?php
/**
 * @description result message
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-06-29 11:05:46
 *
 */
namespace Kovey\Pulsar\Message;

class Result implements ResInterface
{
    private string $result;

    private string $messageId;

    private string $context;

    private string $errorMsg;

    private int $errorCode;

    private int $schemaVersion;

    public function __construct()
    {
        $this->result = '';
        $this->messageId = '';
        $this->context = '';
        $this->errorMsg = '';
        $this->errorCode = -1;
        $this->schemaVersion = 0;
    }

    public function isSuccess() : bool
    {
        return $this->result == 'ok' && $this->errorCode == 0;
    }

    public function getMessageId() : string
    {
        return $this->messageId;
    }

    public function getErrorMsg() : string
    {
        return $this->errorMsg;
    }

    public function getContext() : string
    {
        return $this->context;
    }

    public function getSchemaVersion() : int
    {
        return $this->schemaVersion;
    }

    public function getErrorCode() : int
    {
        return $this->errorCode;
    }

    public function decode(Array $data) : ResInterface
    {
        $this->result = $data['result'] ?? 'error';
        $this->messageId = $data['messageId'] ?? '';
        if (!empty($this->messageId)) {
            $this->messageId = base64_decode($this->messageId);
        }
        $this->context = $data['context'] ?? '';
        $this->errorMsg = $data['errorMsg'] ?? '';
        $this->errorCode = $data['errorCode'] ?? -1;
        $this->schemaVersion = $data['schemaVersion'] ?? 0;
        return $this;
    }

    public function __toString() : string
    {
        return json_encode(array(
            'result' => $this->result,
            'messageId' => base64_encode($this->messageId),
            'context' => $this->context,
            'errorMsg' => $this->errorMsg,
            'errorCode' => $this->errorCode,
            'schemaVersion' => $this->schemaVersion
        ));
    }
}
