<?php

namespace App\Data;

class Payload
{
    protected $data = [];
    protected $messages = [];

    protected $redirect = false;
    protected $error = false;
    protected $errorCode = 500;

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

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function addData(string $key, $data)
    {   
        if(is_array($data) || is_object($data)) {
            $this->data[$key] = $data;
            return true;
        }
        return false;
    }

    public function getData()
    {
        $this->data['messages'] = $this->messages;
        return $this->data;
    }

    public function addMessage(string $message = "Message")
    {
        $this->messages[] = [
            'type' => 'info',
            'color' => 'blue',
            'text' => $message
        ];
    }

    public function addSuccessMessage(string $message = "Message")
    {
        $this->messages[] = [
            'type' => 'success',
            'color' => 'green',
            'text' => $message
        ];
    }

    public function throwError(int $code = 500, string $message = "Error")
    {
        $this->error = true;
        $this->errorCode = $code;

        $this->messages[] = [
            'type' => 'danger',
            'color' => 'red',
            'text' => $message
        ];
    }
}
