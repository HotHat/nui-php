<?php

namespace App;

class Response
{
    private int $_status;
    private array $_header;
    private string $_body = '';

    public function __construct(
        $status = 200,
        $headers = array(),
        $body = ''
    ) {
        $this->_status = $status;
        $this->_header = $headers;
        $this->_body = (string)$body;
    }

    public function header($name, $value): static
    {
        $this->_header[$name] = $value;
        return $this;
    }

    public function getStatusCode() {
        return $this->_status;
    }

    public function getHeaders() {
        return $this->_header;
    }

    public function rawBody() {
        return $this->_body;
    }

    public function __toString()
    {
        http_response_code($this->_status);

        foreach ($this->_header as $name => $value) {
            \header("$name: $value");
        }

        // The whole http package
        return $this->_body;
    }
}