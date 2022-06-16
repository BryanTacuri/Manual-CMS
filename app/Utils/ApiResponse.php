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
}