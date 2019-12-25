<?php
namespace common\helpers;

use yii\base\Exception;

class Curl
{
    protected $url;
    protected $method = "GET";
    protected $data = [];
    protected $header;

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    public function setData($data)
    {
        if (!empty($data)) {
            $this->data['Data'] = $data;
        }
        return $this;
    }

    public function setHeader($header = [])
    {
        $this->header = array_merge(['Content Type = application/x-www-form-urlencoded'], $header);

    }

    public function __call($name, $argument)
    {
        $this->setMethod($name);
        if (!empty($argument)) {
            foreach ($argument as $arg) {
                $this->setData($arg);
            }
        }
        return $this->curl();
    }

    public function curl()
    {
        if (empty($this->url)) {
            throw new \Exception("URL can't be empty");
        }
        $this->url = rtrim($this->url, '/');
        empty($this->header) && $this->setHeader();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);

        if ($this->method === "POST")
            curl_setopt($ch, CURLOPT_POST, true);

        if (!empty($this->data))
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->data));

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        print_r($result);die;
        //print_r($http_code);die;
        list($headers, $body) = explode("\r\n\r\n", $result, 2);

        $header_array = explode("\n", $headers);
        foreach($header_array as $header_value) {
            $header_pieces = explode(':', $header_value);
            if(count($header_pieces) == 2) {
                $header_http[$header_pieces[0]] = trim($header_pieces[1]);
            }
        }


        $name = explode('/',$this->url);
        $request['name'] = $name[5];
        $request['body'] = $body;

        if (!$result || $http_code != 200 || $http_code == 404)
            throw new Exception(curl_error($ch) ,$http_code);

        curl_close($ch);

        return $request;
    }
}