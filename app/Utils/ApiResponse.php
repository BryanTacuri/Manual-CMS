<?php

namespace App\Utils;


class ApiResponse
{
    public $statusCode;
    public $message;
    public $data;

    public function __construct(
        $data = [],
        $statusCode = 200,
        $message = 'Se ha procesado correctamente'
    ) {
        $this->data = $data;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
    public function setData($data)
    {
        $this->data = $data;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /*  public function setMessageSucces($message)
    {
        $this->message = $message;
    } */

    public function setMessageError($message)
    {
        $this->message = $message;
        $this->data = null;
        $this->statusCode = 500;
    }

    public function returnData()
    {
        return response()->json(
            [
                'data' => $this->data,
                'message' => $this->message,
                'statusCode' => $this->statusCode,
            ],
        );
    }
}