<?php

namespace App\Data;

class Payload
{
    protected $data = [];

    protected $redirect = false;
    protected $error = false;

    public function __construct()
    {
    }

    public function setRedirect(string $uri)
    {
        $this->redirect = $uri;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

    public function checkForRedirect()
    {
        if ($this->redirect) {
            return true;
        }
        return false;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function addData(string $key, $data)
    {
        $this->data[$key] = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
