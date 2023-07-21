<?php


namespace App\services;


class ServiceResponse
{
    private string $_statusCode;
    private string $_message;
    private array $_data;

    public function __construct($status_code, $_message = '', $data = [])
    {
        $this->_statusCode = $status_code;
        $this->_message = $_message;
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * @return mixed|string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->_statusCode;
    }

}
