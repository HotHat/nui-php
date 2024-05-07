<?php

namespace Nui;

use CURLFile;
use CURLStringFile;
use http\Exception\InvalidArgumentException;

class HttpClient
{
    private array $headers = [];
    private $curl;

    private $bodyFormat = 'urlencoded';
    private string $method = 'GET';
    private string | array $reqData;
    private $response;
    private $isSend = false;

    private array $options = [];

    public function __construct()
    {
        $this->curl = \curl_init();
        if ($this->curl === false) {
            // TODO:
        }
        $this->options[CURLOPT_RETURNTRANSFER] = true;
        $this->options[ CURLOPT_HEADER] = true;
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    public function get($url, $query=[]) {
        $this->request( 'GET', $url, $query);

        return $this;
    }

    public function timeout($second) {
        $this->options[CURLOPT_CONNECTTIMEOUT] = $second;
    }

    public function post($url, $data) {
        $this->bodyFormat = 'urlencoded';

        $this->request('POST', $url, $data);
        return $this;
    }

    public function file($name, $data) {
        $this->bodyFormat = 'form-data';
        if (isset($this->reqData[$name])) {
            throw new \Exception('post data name: ' . $name . ' is exist!');
        }
        if (is_array($data)) {
            foreach ($data as $k => $it) {
                $nk = $name.'[' . $k . ']';
                $this->reqData[$nk]= $it;
            }
        } else {
            $this->reqData[$name]= $data;
        }
    }

    public function multiPart($url, $data) {
        $this->bodyFormat = 'form-data';
        $this->request('POST', $url, $data);
        return $this;
    }

    public function json($url, string $data) {
        $this->addHeader('Content-Type', 'application/json');
        $this->bodyFormat = 'raw';
        $this->request('POST', $url, $data);
        return $this;
    }

    public function request(string $method, string $url, string | array $extra = []) {
        if ($method === 'GET') {
            $this->options[CURLOPT_HTTPGET] = true;
        } else if ($method === 'POST') {
            $this->options[CURLOPT_POST] = true;
        } else {
            $this->options[CURLOPT_CUSTOMREQUEST] = $method;
        }
        $this->options[CURLOPT_URL] = $url;
        $this->method = $method;
        $this->reqData = $extra;
        return $this;
    }

    public function proxy($host, $port) {
        $this->options[CURLOPT_PROXY] = sprintf('%s:%s', $host, $port);
        return $this;
    }

    public function addHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setOption($opt, $value) {
        $this->options[$opt] = $value;
        return $this;
    }

    public function getStatusCode() {
        if(!$this->isSend) { $this->send(); }
        return $this->response['response_header']['status'];
    }
    public function getBody() { if(!$this->isSend) { $this->send(); } return $this->response['body']; }
    public function getHeader() {
        if(!$this->isSend) { $this->send(); }
        return $this->response['response_header']['headers'];
    }



    private function send() {

        if ($this->reqData) {
            if ($this->method === 'GET') {
                $this->options[CURLOPT_URL] =  $this->options[CURLOPT_URL] . '?' . http_build_query($this->reqData);
            } else {
                if ($this->bodyFormat === 'form-data') {
                    $this->options[CURLOPT_POSTFIELDS] = $this->reqData;
                } else if ($this->bodyFormat === 'urlencoded'){
                    $this->options[CURLOPT_POSTFIELDS] = http_build_query($this->reqData);
                } else {
                    $this->options[CURLOPT_POSTFIELDS] = (string)$this->reqData;
                }
            }
        }

        curl_setopt_array($this->curl, $this->options);

        curl_setopt(
            $this->curl,
            CURLOPT_HTTPHEADER,
            array_map(function ($key, $value) {
                          return $key . ': ' . $value;
                     },
                array_keys($this->headers), $this->headers
            )
        );



        $ret = curl_exec($this->curl);

        throw_if(
            $ret === false || curl_errno($this->curl),
            \Exception::class,
            'HttpClient error:' . curl_error($this->curl)
        );

        $curlInfo = curl_getinfo($this->curl);
        // var_dump($curlInfo);
        $headStr = substr($ret, 0, $curlInfo['header_size'] - 2);
        // var_dump($headStr);
        // $headers = http_parse_headers($headStr);
        // var_dump($headers);
        $respHeader = $this->paresHeader($headStr);
        // dump($respHeader);

        $body = substr($ret, $curlInfo['header_size']);

        $this->response = [
            'response_header' => $respHeader,
            'body' => $body
        ];

        $this->isSend = true;
    }

    private function paresHeader($str) {
        $lns = explode("\r\n", $str);
        $statusLine = array_shift($lns);
        [$httpVersion, $status, $text] = explode(' ', $statusLine);
        [$http, $version] = explode('/', $httpVersion);
        $headers = [];
        foreach ($lns as $header) {
            if (preg_match('/^([^:]*):\s*(.*)$/', $header, $match)) {
                $headers[$match[1]] = trim($match[2]);
            }
        }

        return [
            'protocol' => $http,
            'version' => $version,
            'status' => $status,
            'status_text' => $text,
            'headers' => $headers
        ];
    }

}