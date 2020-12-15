<?php

namespace App\Models;

use __\__;

class Request implements \JsonSerializable
{
    /**
     * @var string
     */
    private string $message;

    /**
     * @var string
     */
    private string $token;

    /**
     * @var string
     */
    private string $status;

    /**
     * Request constructor.
     * @param string $message
     * @param string $token
     * @param string $status
     */
    public function __construct(
        string $message,
        string $token,
        string $status
    ) {
        $this->message = $message;
        $this->token = $token;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param array $data
     * @return Request
     */
    public static function create(array $data): Request
    {
        return new self(
            __::get($data, "message"),
            __::get($data, "token"),
            __::get($data, "status")
        );
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            "message" => $this->getMessage(),
            "token" => $this->getToken(),
            "status" => $this->getStatus()
        ];
    }
}